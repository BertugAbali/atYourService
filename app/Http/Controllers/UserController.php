<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\DB;

use Stripe\StripeClient;
use Illuminate\Database\DatabaseManager;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Stripe\Exception\ApiErrorException;

use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{

    protected StripeClient $stripeClient;
    protected DatabaseManager $databaseManager;

    public function __construct(StripeClient $stripeClient, DatabaseManager $databaseManager)
    {
        $this->middleware(['auth', 'verified']);
        $this->stripeClient = $stripeClient;
        $this->databaseManager = $databaseManager;
    }

    // it will return payment page based on chosed service

    public function stripe(Service $service)
    {
        return view('stripe', compact('service'));
    }

    // It will return auth user profile.

    public function profile()
    {

        // If you want to get available balance use this

        /*
        $balance =  Auth::user()->completed_stripe_onboarding ?  $this->stripeClient
        ->balance->retrieve(null, ['stripe_account' => Auth::user()->stripe_connect_id])
        ->available[0]
        ->amount : 0;

        */

        // This is for getting pending balance (use this for testing)


        $balance =  Auth::user()->completed_stripe_onboarding ?  $this->stripeClient
            ->balance->retrieve(null, ['stripe_account' => Auth::user()->stripe_connect_id])
            ->pending[0]
            ->amount : 0;


        return view('profile', [
            'balance' => $balance / 100
        ]);
    }

    // 

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:1000',
        ]);

        $user = new User;
        $user = User::find(Auth::user()->id);

        $request->file('image')->store('public/users-avatar');

        // Delete the old profile icon (if its )
        if ($user->avatar != "avatar.png") {
            unlink(public_path() . '/storage/users-avatar/' . $user->avatar);
        }

        $user->avatar = $request->file('image')->hashName();
        $user->save();
        return redirect('profile');
    }


    // This function update the player

    public function update(Request $request)
    {

        // Validation of edited user infos

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable ', 'string', 'max:255'],
            'phone' => ['nullable ', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:9'],
            'about' => ['nullable ', 'string', 'max:1000'],
        ]);



        $user = new User;
        $user = User::find(Auth::user()->id);
        $user->update($request->all());
        return redirect('profile');
    }

    // This functions delete the user and his/her belonged services with their images.

    public function destroy()
    {
        $user = new User;
        $user = User::find(Auth::user()->id);

        $services = DB::table('services')->where('owner_id', $user->id)->get()->toArray();
        foreach ($services as $service) {
            unlink(public_path() . '/storage/images/' . $service->path);
        }
        if ($user->avatar != "avatar.png") {
            unlink(public_path() . '/storage/users-avatar/' . $user->avatar);
        }
        DB::table('services')->where('owner_id', $user->id)->delete();
        DB::table('stripe_state_tokens')->where('seller_id', $user->id)->delete();
        $user->delete();
        return redirect('/');
    }


    public function redirectToStripe()
    {
        $seller = new User;
        $seller = User::find(Auth::user()->id);

        if (is_null($seller)) {
            abort(404);
        }

        // Complete the onboarding process
        if (!$seller->completed_stripe_onboarding) {

            $token = Str::random();

            $this->databaseManager->table('stripe_state_tokens')->insert([
                'created_at' => now(),
                'updated_at' => now(),
                'seller_id'  => $seller->id,
                'token'      => $token
            ]);

            try {

                // Let's check if they have a stripe connect id
                if (is_null($seller->stripe_connect_id)) {

                    // Create account
                    $account = $this->stripeClient->accounts->create([
                        'country' => $this->country_to_code($seller->country),
                        'type'    => 'express',
                        'email'   => $seller->email,
                        'business_type' => 'individual',
                    ]);

                    $seller->update(['stripe_connect_id' => $account->id]);
                    $seller->fresh();
                }

                $onboardLink = $this->stripeClient->accountLinks->create([
                    'account'     => $seller->stripe_connect_id,
                    'refresh_url' => route('redirect.stripe'),
                    'return_url'  => route('save.stripe', ['token' => $token, 'id' => $seller->id]),
                    'type'        => 'account_onboarding'
                ]);

                return redirect($onboardLink->url);
            } catch (\Exception $exception) {
                return redirect('profile')->withErrors(['message' => $exception->getMessage()]);
            }
        }

        try {

            $loginLink = $this->stripeClient->accounts->createLoginLink($seller->stripe_connect_id);
            return redirect($loginLink->url);
        } catch (\Exception $exception) {
            return redirect('profile')->withErrors(['message' => $exception->getMessage()]);
        }
    }

    public function saveStripeAccount($token)
    {
        $stripeToken = $this->databaseManager->table('stripe_state_tokens')
            ->where('token', '=', $token)
            ->first();

        if (is_null($stripeToken)) {
            abort(404);
        }

        $seller = User::find($stripeToken->seller_id);

        $seller->update([
            'completed_stripe_onboarding' => true
        ]);

        return redirect('profile');
    }


    public function purchase(Service $service, Request $request)
    {

        $this->validate($request, [
            'stripeToken' => ['required', 'string']
        ]);

        $seller = User::find($service->owner_id);
        //   $buyer = User::find($buyer_id);

        if($seller->id == Auth::user()->id){
            return back()->withErrors(['message' => 'You can not buy your own service']);
        }

        try {

            // Purchase a product
            $charge = $this->stripeClient->charges->create([
                'amount'      => ($service->price) * 100,
                'currency'    => 'eur',
                'source'      => $request->stripeToken,
                'description' => 'This is an example charge.'
            ]);

            $fee = 5; // percentage of fee (stripe takes around %3 fee so we take total of 5% fee. Our profit will be %3 of total price)

            // Transfer funds to seller
            $this->stripeClient->transfers->create([
                'amount'             => ($service->price) * (100 - $fee),
                'currency'           => 'eur',
                'source_transaction' => $charge->id,
                'destination'        => $seller->stripe_connect_id
            ]);
        } catch (ApiErrorException $exception) {
            return back()->withErrors(['message' => $exception->getMessage()]);
        }

        return redirect('/')->with('success', 'Succesfully '. $service->title . ' purschased');
    }

    // This function turns country names to country code

    public function country_to_code($country)
    {

        $countryList = array(
            'Aland Islands' => 'AF',
            'Afghanistan' => 'AX',
            'Albania' => 'AL',
            'Algeria' => 'DZ',
            'American Samoa' => 'AS',
            'Andorra' => 'AD',
            'Angola' => 'AO',
            'Anguilla' => 'AI',
            'Antarctica' => 'AQ',
            'Antigua and Barbuda' => 'AG',
            'Argentina' => 'AR',
            'Armenia' => 'AM',
            'Aruba' => 'AW',
            'Australia' => 'AU',
            'Austria' => 'AT',
            'Azerbaijan' => 'AZ',
            'Bahamas the' => 'BS',
            'Bahrain' => 'BH',
            'Bangladesh' => 'BD',
            'Barbados' => 'BB',
            'Belarus' => 'BY',
            'Belgium' => 'BE',
            'Belize' => 'BZ',
            'Benin' => 'BJ',
            'Bermuda' => 'BM',
            'Bhutan' => 'BT',
            'Bolivia' => 'BO',
            'Bosnia and Herzegovina' => 'BA',
            'Botswana' => 'BW',
            'Bouvet Island (Bouvetoya)' => 'BV',
            'Brazil' => 'BR',
            'British Indian Ocean Territory (Chagos Archipelago)' => 'IO',
            'British Virgin Islands' => 'VG',
            'Brunei Darussalam' => 'BN',
            'Bulgaria' => 'BG',
            'Burkina Faso' => 'BF',
            'Burundi' => 'BI',
            'Cambodia' => 'KH',
            'Cameroon' => 'CM',
            'Canada' => 'CA',
            'Cape Verde' => 'CV',
            'Cayman Islands' => 'KY',
            'Central African Republic' => 'CF',
            'Chad' => 'TD',
            'Chile' => 'CL',
            'China' => 'CN',
            'Christmas Island' => 'CX',
            'Cocos (Keeling) Islands' => 'CC',
            'Colombia' => 'CO',
            'Comoros the' => 'KM',
            'Congo' => 'CD',
            'Congo the' => 'CG',
            'Cook Islands' => 'CK',
            'Costa Rica' => 'CR',
            'Cote d\'Ivoire' => 'CI',
            'Croatia' => 'HR',
            'Cuba' => 'CU',
            'Cyprus' => 'CY',
            'Czech Republic' => 'CZ',
            'Denmark' => 'DK',
            'Djibouti' => 'DJ',
            'Dominica' => 'DM',
            'Dominican Republic' => 'DO',
            'Ecuador' => 'EC',
            'Egypt' => 'EG',
            'El Salvador' => 'SV',
            'Equatorial Guinea' => 'GQ',
            'Eritrea' => 'ER',
            'Estonia' => 'EE',
            'Ethiopia' => 'ET',
            'Faroe Islands' => 'FO',
            'Falkland Islands (Malvinas)' => 'FK',
            'Fiji the Fiji Islands' => 'FJ',
            'Finland' => 'FI',
            'France, French Republic' => 'FR',
            'French Guiana' => 'GF',
            'French Polynesia' => 'PF',
            'French Southern Territories' => 'TF',
            'Gabon' => 'GA',
            'Gambia the' => 'GM',
            'Georgia' => 'GE',
            'Germany' => 'DE',
            'Ghana' => 'GH',
            'Gibraltar' => 'GI',
            'Greece' => 'GR',
            'Greenland' => 'GL',
            'Grenada' => 'GD',
            'Guadeloupe' => 'GP',
            'Guam' => 'GU',
            'Guatemala' => 'GT',
            'Guernsey' => 'GG',
            'Guinea' => 'GN',
            'Guinea-Bissau' => 'GW',
            'Guyana' => 'GY',
            'Haiti' => 'HT',
            'Heard Island and McDonald Islands' => 'HM',
            'Holy See (Vatican City State)' => 'VA',
            'Honduras' => 'HN',
            'Hong Kong' => 'HK',
            'Hungary' => 'HU',
            'Iceland' => 'IS',
            'India' => 'IN',
            'Indonesia' => 'ID',
            'Iran' => 'IR',
            'Iraq' => 'IQ',
            'Ireland' => 'IE',
            'Isle of Man' => 'IM',
            'Israel' => 'IL',
            'Italy' => 'IT',
            'Jamaica' => 'JM',
            'Japan' => 'JP',
            'Jersey' => 'JE',
            'Jordan' => 'JO',
            'Kazakhstan' => 'KZ',
            'Kenya' => 'KE',
            'Kiribati' => 'KI',
            'Korea' => 'KP',
            'Korea' => 'KR',
            'Kuwait' => 'KW',
            'Kyrgyz Republic' => 'KG',
            'Lao' => 'LA',
            'Latvia' => 'LV',
            'Lebanon' => 'LB',
            'Lesotho' => 'LS',
            'Liberia' => 'LR',
            'Libyan Arab Jamahiriya' => 'LY',
            'Liechtenstein' => 'LI',
            'Lithuania' => 'LT',
            'Luxembourg' => 'LU',
            'Macao' => 'MO',
            'Macedonia' => 'MK',
            'Madagascar' => 'MG',
            'Malawi' => 'MW',
            'Malaysia' => 'MY',
            'Maldives' => 'MV',
            'Mali' => 'ML',
            'Malta' => 'MT',
            'Marshall Islands' => 'MH',
            'Martinique' => 'MQ',
            'Mauritania' => 'MR',
            'Mauritius' => 'MU',
            'Mayotte' => 'YT',
            'Mexico' => 'MX',
            'Micronesia' => 'FM',
            'Moldova' => 'MD',
            'Monaco' => 'MC',
            'Mongolia' => 'MN',
            'Montenegro' => 'ME',
            'Montserrat' => 'MS',
            'Morocco' => 'MA',
            'Mozambique' => 'MZ',
            'Myanmar' => 'MM',
            'Namibia' => 'NA',
            'Nauru' => 'NR',
            'Nepal' => 'NP',
            'Netherlands Antilles' => 'AN',
            'Netherlands the' => 'NL',
            'New Caledonia' => 'NC',
            'New Zealand' => 'NZ',
            'Nicaragua' => 'NI',
            'Niger' => 'NE',
            'Nigeria' => 'NG',
            'Niue' => 'NU',
            'Norfolk Island' => 'NF',
            'Northern Mariana Islands' => 'MP',
            'Norway' => 'NO',
            'Oman' => 'OM',
            'Pakistan' => 'PK',
            'Palau' => 'PW',
            'Palestinian Territory' => 'PS',
            'Panama' => 'PA',
            'Papua New Guinea' => 'PG',
            'Paraguay' => 'PY',
            'Peru' => 'PE',
            'Philippines' => 'PH',
            'Pitcairn Islands' => 'PN',
            'Poland' => 'PL',
            'Portugal' => 'PT',
            'Puerto Rico' => 'PR',
            'Qatar' => 'QA',
            'Reunion' => 'RE',
            'Romania' => 'RO',
            'Russian Federation' => 'RU',
            'Rwanda' => 'RW',
            'Saint Barthelemy' => 'BL',
            'Saint Helena' => 'SH',
            'Saint Kitts and Nevis' => 'KN',
            'Saint Lucia' => 'LC',
            'Saint Martin' => 'MF',
            'Saint Pierre and Miquelon' => 'PM',
            'Saint Vincent and the Grenadines' => 'VC',
            'Samoa' => 'WS',
            'San Marino' => 'SM',
            'Sao Tome and Principe' => 'ST',
            'Saudi Arabia' => 'SA',
            'Senegal' => 'SN',
            'Serbia' => 'RS',
            'Seychelles' => 'SC',
            'Sierra Leone' => 'SL',
            'Singapore' => 'SG',
            'Slovakia (Slovak Republic)' => 'SK',
            'Slovenia' => 'SI',
            'Solomon Islands' => 'SB',
            'Somalia, Somali Republic' => 'SO',
            'South Africa' => 'ZA',
            'South Georgia and the South Sandwich Islands' => 'GS',
            'Spain' => 'ES',
            'Sri Lanka' => 'LK',
            'Sudan' => 'SD',
            'Suriname' => 'SR',
            'Svalbard & Jan Mayen Islands' => 'SJ',
            'Swaziland' => 'SZ',
            'Sweden' => 'SE',
            'Switzerland, Swiss Confederation' => 'CH',
            'Syrian Arab Republic' => 'SY',
            'Taiwan' => 'TW',
            'Tajikistan' => 'TJ',
            'Tanzania' => 'TZ',
            'Thailand' => 'TH',
            'Timor-Leste' => 'TL',
            'Togo' => 'TG',
            'Tokelau' => 'TK',
            'Tonga' => 'TO',
            'Trinidad and Tobago' => 'TT',
            'Tunisia' => 'TN',
            'Turkey' => 'TR',
            'Turkmenistan' => 'TM',
            'Turks and Caicos Islands' => 'TC',
            'Tuvalu' => 'TV',
            'Uganda' => 'UG',
            'Ukraine' => 'UA',
            'United Arab Emirates' => 'AE',
            'United Kingdom' => 'GB',
            'United States of America' => 'US',
            'United States Minor Outlying Islands' => 'UM',
            'United States Virgin Islands' => 'VI',
            'Uruguay, Eastern Republic of' => 'UY',
            'Uzbekistan' => 'UZ',
            'Vanuatu' => 'VU',
            'Venezuela' => 'VE',
            'Vietnam' => 'VN',
            'Wallis and Futuna' => 'WF',
            'Western Sahara' => 'EH',
            'Yemen' => 'YE',
            'Zambia' => 'ZM',
            'Zimbabwe' => 'ZW',
        );
        return $countryList[$country];
    }

    // It will return page of becoming service provider page.

    public function becomeProvider()
    {
        return view('becomeProvider');
    }

    // It will return page of user edit page.

    public function editProfile()
    {
        return view('editProfile');
    }

    // It will return page of owned services page.

    public function showServices()
    {
        $services = Service::where('owner_id', Auth::user()->id)->paginate(20);
        return view('showServices', compact('services'));
    }


    // It will return chosed user profile.

    public function userProfile($user_id)
    {
        $user = new User();
        $user = DB::table('users')->where('id', $user_id)->first();

        return view('userProfile', compact('user'));
    }
}
