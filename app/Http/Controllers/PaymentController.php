<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    protected $stripe;

    public function __construct()
    {
        $this->stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
    }

    public function index()
    {
        $invoices = $this->stripe->invoices->all(['limit' => 10]);
        $data['invoices'] = [];
        foreach ($invoices as $invoice) {
            $product = [];
            if ($invoice->lines->data[0]->subscription) {
                $subscriptionId = $invoice->lines->data[0]->subscription_item;
                $subscriptionItem = $this->stripe->subscriptionItems->retrieve($subscriptionId, []);
                $productID = $subscriptionItem->plan->product;
                $product = $this->stripe->products->retrieve($productID, []);
                $customerID = $invoice->customer;
                if (Auth::user()->stripe_id == $customerID) {
                    $data['invoices'][] = [
                        'id' => $invoice->id,
                        'invno' => $invoice->number,
                        'cust_name' => $invoice->customer_name,
                        'cust_email' => $invoice->customer_email,
                        'desc' => ($product) ? $product->name : 'N/A',
                        'amount' => ($invoice->amount_paid / 100),
                        'createdAt' => Carbon::createFromTimestamp($invoice->created)->format('M d Y'),
                        'invoice_pdf' => $invoice->invoice_pdf
                    ];
                }
            }
        }

        return view('payments.index',$data);
    }

    public function show($id)
    {

        $invoice = $this->stripe->invoices->retrieve($id, []);
        $product = [];
        if ($invoice->lines->data[0]->subscription) {
            $subscriptionId = $invoice->lines->data[0]->subscription_item;
            $subscriptionItem = $this->stripe->subscriptionItems->retrieve($subscriptionId, []);
            $productID = $subscriptionItem->plan->product;
            $product = $this->stripe->products->retrieve($productID, []);
            $customerID = $invoice->customer;
        }
        if (Auth::user()->stripe_id == $customerID) {
            $data['invoice'] = [
                'id' => $invoice->id,
                'invno' => $invoice->number,
                'cust_name' => $invoice->customer_name,
                'cust_email' => $invoice->customer_email,
                'desc' => ($product) ? $product->name : 'N/A',
                'amount' => ($invoice->amount_paid / 100),
                'createdAt' => Carbon::createFromTimestamp($invoice->created)->format('M d Y'),
                'invoice_pdf' => $invoice->invoice_pdf
            ];
        }
        return view('payments.show', $data);
    }
}
