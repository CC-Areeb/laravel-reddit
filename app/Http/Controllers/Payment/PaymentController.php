<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Illuminate\Support\Facades\Log;
use Stripe\Customer;
use Stripe\Invoice;
use Stripe\PaymentMethod;
use Stripe\Subscription;

class PaymentController extends Controller
{
    public function showPlans(): View
    {
        return view('payment.index', [
            'stripeKey' => config('services.stripe.key'),  // Pass Stripe's publishable key to the view
        ]);
    }

    public function subscribe(Request $request, string $plan = null)
    {
        // Validate payment_method and selected plan
        $request->validate([
            'payment_method' => 'required',
            'selected_plan' => 'required|in:basic,pro,premium',
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        $plans = [
            'basic' => 'price_1RMCANGfu8ydv2wP5n337CkR',
            'pro' => 'price_1RMCHiGfu8ydv2wPbpK7fOEi',
            'premium' => 'price_1RMCHzGfu8ydv2wP1exqLxab',
        ];

        // Use selected_plan from the request if no plan is passed in the URL
        $plan = $plan ?: $request->input('selected_plan');

        // Check if the plan exists
        if (!array_key_exists($plan, $plans)) {
            return back()->with('error', 'Invalid plan selected.');
        }

        // Determine the price based on the selected plan
        $planPrice = 0;
        switch ($plan) {
            case 'basic':
                $planPrice = 10; // $10 for Basic
                break;
            case 'pro':
                $planPrice = 20; // $20 for Pro
                break;
            case 'premium':
                $planPrice = 30; // $30 for Premium
                break;
        }

        try {
            $user = Auth::user();

            if (!$user->stripe_id) {
                // Create a new Stripe customer if the user doesn't have a Stripe ID
                $customer = Customer::create([
                    'email' => $user->email,
                    'name' => $user->name,
                ]);
                $user->stripe_id = $customer->id;
                $user->save();
            }

            $paymentMethod = PaymentMethod::retrieve($request->payment_method);
            $paymentMethod->attach(['customer' => $user->stripe_id]);

            // Set the default payment method for the customer
            Customer::update($user->stripe_id, [
                'invoice_settings' => ['default_payment_method' => $paymentMethod->id],
            ]);

            // Create a subscription
            Subscription::create([
                'customer' => $user->stripe_id,
                'items' => [['price' => $plans[$plan]]],
                'default_payment_method' => $paymentMethod->id,
                'payment_settings' => [
                    'payment_method_types' => ['card'],
                    'save_default_payment_method' => 'on_subscription',
                ],
                'expand' => ['latest_invoice'],
            ]);

            return redirect()->back()->with('success', "You have successfully purchased the {$plan} subscription for \${$planPrice}");
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
