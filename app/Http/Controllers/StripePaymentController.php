<?php
    
namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Transactions;
use Illuminate\Http\Request;

use Stripe;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Auth;
     
class StripePaymentController extends Controller
{
  
    // it will return pay page based on chosed service

    public function stripe(Service $service)
    {
        return view('stripe',compact('service'));
    }
    
    
    /*

     This funtion will try the paying based on your card informations if the transaction successfull, it will return main page but
     if it not, it will return paying page with an error. Also it will save a transaction to database.

    */

    public function stripePost(Request $request)
    {
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $service = new Service();
        $service = DB::table('services')->where('id', $request->service)->first();
    
        Stripe\Charge::create ([
                "amount" => ($service->price)*100,
                "currency" => "eur",
                "source" => $request->stripeToken,
                "description" => "Service of $service->title purchased" 
        ]);

        $transaction = new Transactions();
        $transaction->buyer_id= 1;
        $transaction->seller_id=$service->owner_id; 
        $transaction->description="Service of $service->title purchased";
        $transaction->token=$request->stripeToken;
        $transaction->amount=$service->price;
        $transaction->save();
              
        return redirect('/');
    }
}