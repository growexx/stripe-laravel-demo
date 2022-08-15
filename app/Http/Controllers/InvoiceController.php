<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    protected $stripe;

    public function __construct()
    {
        $this->stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
    }

    public function index()
    {
        if (Auth::user()->role == 'admin') {
            $invoices = $this->stripe->invoices->all(['limit' => 10]);
            $data['invoices'] = [];
            foreach ($invoices as $invoice) {
                $product = [];
                if ($invoice->lines->data[0]->subscription) {
                    $subscriptionId = $invoice->lines->data[0]->subscription_item;
                    $subscriptionItem = $this->stripe->subscriptionItems->retrieve($subscriptionId, []);
                    $productID = $subscriptionItem->plan->product;
                    $product = $this->stripe->products->retrieve($productID, []);
                }
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
            return view('invoice.index', $data);
        } else {
            return view('404');
        }
    }

    public function show($id)
    {
        if (Auth::user()->role == 'admin') {
            $invoice = $this->stripe->invoices->retrieve($id, []);
            // dd($invoice);
            $product = [];
            if ($invoice->lines->data[0]->subscription) {
                $subscriptionId = $invoice->lines->data[0]->subscription_item;
                $subscriptionItem = $this->stripe->subscriptionItems->retrieve($subscriptionId, []);
                $productID = $subscriptionItem->plan->product;
                $product = $this->stripe->products->retrieve($productID, []);
            }
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
            return view('invoice.show', $data);
        } else {
            return view('404');
        }
    }
}
