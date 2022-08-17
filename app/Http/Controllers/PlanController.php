<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plan;
use Illuminate\Support\Facades\Auth;

class PlanController extends Controller
{
    protected $stripe;

    const ROUTEINDEX = 'plans.index';

    public function __construct()
    {
        $this->stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
    }
    public function index()
    {
        $plans = Plan::all();
        return view($this::ROUTEINDEX, compact('plans'));
    }
    public function create()
    {
        if (Auth::user()->role == 'admin') {
            return view('plans.create');
        } else {
            return redirect()->route($this::ROUTEINDEX);
        }
    }


    public function store(Request $request)
    {
        $data = $request->except('_token');

        $data['slug'] = strtolower($data['name']);
        $price = $data['cost'] * 100;

        $stripeProduct = $this->stripe->products->create([
            'name' => $data['name'],
        ]);

        $stripePlanCreation = $this->stripe->plans->create([
            'amount' => $price,
            'currency' => 'inr',
            'interval' => 'month',
            'product' => $stripeProduct->id,
        ]);

        $data['stripe_plan'] = $stripePlanCreation->id;

        Plan::create($data);

        return redirect()->route($this::ROUTEINDEX)->with('success', 'New Plan has been Created...!');
    }
    public function show(Plan $plan, Request $request)
    {
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
        return redirect()->route($this::ROUTEINDEX)->with('success', 'Plan Updated successfully...!');
    }
}
