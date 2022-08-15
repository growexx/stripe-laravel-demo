<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plan;
use Illuminate\Support\Facades\Auth;

class PlanController extends Controller
{
    protected $stripe;

    public function __construct()
    {
        $this->stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function index()
    {
        $plans = Plan::all();
        return view('plans.index', compact('plans'));
    }
    public function create()
    {
        if (Auth::user()->role == 'admin') {
            return view('plans.create');
        } else {
            return redirect()->route('plans.index');
        }
    }


    public function store(Request $request, Plan $plan)
    {
        $data = $request->except('_token');

        $data['slug'] = strtolower($data['name']);
        $price = $data['cost'] * 100;

        //create stripe product
        $stripeProduct = $this->stripe->products->create([
            'name' => $data['name'],
        ]);

        //Stripe Plan Creation
        $stripePlanCreation = $this->stripe->plans->create([
            'amount' => $price,
            'currency' => 'inr',
            'interval' => 'month', //  it can be day,week,month or year
            'product' => $stripeProduct->id,
        ]);

        $data['stripe_plan'] = $stripePlanCreation->id;

        Plan::create($data);

        return redirect()->route('plans.index')->with('success', 'New Plan has been Created...!');
    }

    /**
     * Show the Plan.
     *
     * @return mixed
     */
    public function show(Plan $plan, Request $request)
    {
        $paymentMethods = $request->user()->paymentMethods();

        $intent = $request->user()->createSetupIntent();

        return view('plans.show', compact('plan', 'intent'));
    }
    public function edit($id)
    {
        $plan = Plan::findOrFail($id);

        return view('plans.edit', compact('plan'));
    }

    public function update(Request $request, $id)
    {
        $plan = Plan::findOrFail($id);
        $plan->name = $request->get('name');
        $plan->slug = str_replace(' ', '', strtolower($request->get('name')));
        $plan->description = $request->get('description');
        $plan->save();

        // $price_plan = $this->stripe->plans->retrieve($plan->stripe_plan);
        // dd($price_plan);
        $this->stripe->plans->update($plan->stripe_plan, [
            'amount' => intval($request->get('cost') * 100),
            // 'amount_decimal' => intval($request->get('cost') * 100)
        ]);

        // $price_plan = $this->stripe->prices->update($plan->stripe_plan,[
        //     // 'metadata' => [ 'amount' => intval($request->get('cost') * 100)],
        //     'unit_amount' => [intval($request->get('cost') * 100)],
        //     // 'unit_amount_decimal' => (Float)($request->get('cost') * 100),
        // ]);
        $this->stripe->products->update('prod_MCswrqSsuW1IJd', [
            'name' => $request->get('name'),
            'description' => $request->get('description')
        ]);
        return redirect()->route('plans.index')->with('success', 'Plan Updated successfully...!');
    }
}
