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


class UserController extends Controller
{

    protected StripeClient $stripeClient;
    protected DatabaseManager $databaseManager;

    public function __construct(StripeClient $stripeClient, DatabaseManager $databaseManager)
    {
        $this->stripeClient = $stripeClient;
        $this->databaseManager = $databaseManager;
    }

     // it will return pay page based on chosed service

     public function stripe(Service $service)
     {
         return view('stripe',compact('service'));
     }

     // It will return auth user profile.


     public function profile($id)
     {

        $user = User::find($id);

            $balance =  $user->completed_stripe_onboarding ?  $this->stripeClient
            ->balance->retrieve(null, ['stripe_account' => $user->stripe_connect_id])
            ->available[0]
            ->amount : 0;
    
    
            return view('profile', [
                'balance' => $balance
            ]);
     }
   
    public function show(String $user_id)
    {
        $user = new User();

        $user = User::where('id', $user_id)->get();
       
        return view('profile', ['user' => $user]);
    }

    public function store(Request $request,User $user)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        $request->file('image')->store('public/users-avatar');

        // Delete the old profile icon
        unlink(public_path() . '/storage/users-avatar/' .$user->avatar);

        $user->avatar = $request->file('image')->hashName();
        $user->save();
        return redirect('profile/'.$user->id);
    }


    // This function update the player

    public function update(Request $request, User $user)
    {
        $user->update($request->all());
        return redirect('profile/'.$user->id);
    }

    // This functions delete the user and his/her belonged services with their images.

    public function destroy(User $user)
    {
        $services = DB::table('services')->where('owner_id', $user->id)->get()->toArray();
        foreach($services as $service){
            unlink(public_path() . '/storage/images/' .$service->path);
        }
        DB::table('services')->where('owner_id', $user->id)->delete();
        DB::table('stripe_state_tokens')->where('seller_id', $user->id)->delete();
        $user->delete();
        return redirect('/');
    }

    public function redirectToStripe($id)
    {
        $seller = User::find($id);

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
                        'country' => 'PT',
                        'type'    => 'express',
                        'email'   => $seller->email,
                        'business_type' => 'individual',
                    ]);

                    $seller->update(['stripe_connect_id' => $account->id]);
                    $seller->fresh();
                }

                $onboardLink = $this->stripeClient->accountLinks->create([
                    'account'     => $seller->stripe_connect_id,
                    'refresh_url' => route('redirect.stripe', ['id' => $seller->id]),
                    'return_url'  => route('save.stripe', ['token' => $token,'id' => $seller->id]),
                    'type'        => 'account_onboarding'
                ]);

                return redirect($onboardLink->url);

            } catch (\Exception $exception){
                return redirect('profile/'.$seller->id)->withErrors(['message' => $exception->getMessage()]) ;
            }
        }

        try {

            $loginLink = $this->stripeClient->accounts->createLoginLink($seller->stripe_connect_id);
            return redirect($loginLink->url);

        } catch (\Exception $exception){
            return redirect('profile/'.$seller->id)->withErrors(['message' => $exception->getMessage()]) ;
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

        return redirect('profile/'.$seller->id);
    }


    public function purchase(Service $service, Request $request)
    {


        $this->validate($request, [
            'stripeToken' => ['required', 'string']
        ]);

        $seller = User::find($service->owner_id);
     //   $buyer = User::find($buyer_id);

        try {

            // Purchase a product
            $charge = $this->stripeClient->charges->create([
                'amount'      => ($service->price)*100,  
                'currency'    => 'eur',
                'source'      => $request->stripeToken,
                'description' => 'This is an example charge.'
            ]);

            // Transfer funds to seller
            $this->stripeClient->transfers->create([
                'amount'             => ($service->price)*95,  
                'currency'           => 'eur',
                'source_transaction' => $charge->id,
                'destination'        => $seller->stripe_connect_id
            ]);

        } catch (ApiErrorException $exception) {
            return back()->withErrors(['message' => $exception->getMessage()]) ;
        }

        return redirect('/');
    }



}


