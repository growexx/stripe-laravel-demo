<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AdminSubController extends Controller
{
    protected $stripe;

    public function __construct()
    {
        $this->stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
    }

    public function index()
    {
        if (Auth::user()->role == 'admin') {
            $subscriptionsID = $this->stripe->subscriptions->all(['limit' => 10]);

            $data['subscriptions'] = [];
            foreach ($subscriptionsID as $subscription) {
                $ID = $subscription->items->data[0]->id;
                $subscriptionItem = $this->stripe->subscriptionItems->retrieve($ID, []);
                $productID = $subscriptionItem->plan->product;
                $product = $this->stripe->products->retrieve($productID, []);

                $customerID = $subscription->customer;
                $Customer = $this->stripe->customers->retrieve($customerID, []);

                $data['subscriptions'][] = [
                    'id' => $subscription->id,
                    'name' => $Customer->name,
                    'email' => $Customer->email,
                    'status' => $subscription->status,
                    'productName' => $product->name,
                    'createdAt' => Carbon::createFromTimestamp($subscription->created)->format('M d Y'),
                ];
            }
            return view('subscriptions.index', $data);
        } else {
            return view('404');
        }
    }

    public function show($id)
    {
        if (Auth::user()->role == 'admin') {
            $subscriptionItem = $this->stripe->subscriptions->retrieve($id, []);
            $productID = $subscriptionItem->plan->product;
            $product = $this->stripe->products->retrieve($productID, []);
            $customerID = $subscriptionItem->customer;
            $Customer = $this->stripe->customers->retrieve($customerID, []);
            $data['subscriptions'] = [
                'id' => $subscriptionItem->id,
                'name' => $Customer->name,
                'email' => $Customer->email,
                'status' => $subscriptionItem->status,
                'productName' => $product->name,
                'createdAt' => Carbon::createFromTimestamp($subscriptionItem->created)->format('M d Y'),
            ];
            return view('subscriptions.show', $data);
        } else {
            return view('404');
        }
    }
}
