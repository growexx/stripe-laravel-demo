<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class RedirectIfSubscribed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user()->role === 'user') {
            $user = Auth::user();
            $cust_id = $user->stripe_id;
            $user_id = $user->id;
            $subscription_id = DB::table('subscriptions')->select('stripe_id')->where('user_id', $user_id)->orderBy('id', 'desc')->first()->stripe_id;
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
            $subscription = $stripe->subscriptions->retrieve($subscription_id, []);
            $current_date = Carbon::now();
            $start_date = Carbon::createFromTimestamp($subscription->current_period_start)->format('M d Y');
            $end_date = Carbon::createFromTimestamp($subscription->current_period_end)->format('M d Y');
            $check = $current_date->between($start_date, $end_date);
            if (!$check) {
                return redirect()->route('plans.index')->with('info', 'Complete your subscription first!');
            }
        }
        return $next($request);
    }
}
