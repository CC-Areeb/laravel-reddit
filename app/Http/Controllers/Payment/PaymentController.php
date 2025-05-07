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

    public function subscribe(Request $request, string $plan)
    {
        $request->validate([
            'payment_method' => 'required',
            'plan' => 'required|in:basic,pro,premium',
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));  // Use secret key for server-side actions

        // Define the available plans
        $plans = [
            'basic' => 'price_1RMCANGfu8ydv2wP5n337CkR',
            'pro' => 'price_123abcPro',
            'premium' => 'price_123abcPremium',
        ];

        if (!array_key_exists($plan, $plans)) {
            return back()->with('error', 'Invalid plan selected.');
        }

        try {
            $user = Auth::user(); // Ensure the user is authenticated

            // If user doesn't have a Stripe ID, create a new customer
            if (!$user->stripe_id) {
                $customer = Customer::create([
                    'email' => $user->email,
                    'name' => $user->name,
                ]);
                $user->stripe_id = $customer->id;
                $user->save();
            }

            // Attach the payment method
            $paymentMethod = \Stripe\PaymentMethod::retrieve($request->payment_method);
            $paymentMethod->attach(['customer' => $user->stripe_id]);

            // Set the default payment method for the customer
            \Stripe\Customer::update($user->stripe_id, [
                'invoice_settings' => ['default_payment_method' => $paymentMethod->id],
            ]);

            // Create the subscription
            $subscription = \Stripe\Subscription::create([
                'customer' => $user->stripe_id,
                'items' => [['price' => $plans[$plan]]],
                'default_payment_method' => $paymentMethod->id,
                'payment_behavior' => 'default_incomplete', // Allows for subscription creation without immediate payment
            ]);

            // Retrieve the latest invoice
            $invoice = \Stripe\Invoice::retrieve($subscription->latest_invoice);

            if ($invoice) {
                // Check if a payment intent is created
                $paymentIntent = $invoice->payment_intent;

                if ($paymentIntent) {
                    // Return the payment intent client secret for frontend to confirm the payment
                    return redirect()->route('subscriptions')
                        ->with('success', 'Subscription created! Please complete your payment.');
                } else {
                    return back()->with('error', 'No payment intent found.');
                }
            } else {
                return back()->with('error', 'Subscription invoice not found.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
