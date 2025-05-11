<?php

namespace App\Http\Controllers\Stripe;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Webhook;
use Stripe\Stripe;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Invoice;
use Illuminate\Support\Facades\Log;

class StripeWebhookController extends Controller
{
    // Webhook for local listner event for payment intent
    public function handleWebhook(Request $request)
    {
        // Get the raw payload and Stripe signature header
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        
        // Your Stripe Webhook Secret from the Stripe CLI (use the secret shown in the Stripe CLI logs)
        $endpointSecret = config('services.stripe.webhook_secret');
        
        // Verify the webhook signature to ensure it's from Stripe
        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (SignatureVerificationException $e) {
            // Invalid signature
            Log::error('Webhook Error: ' . $e->getMessage());
            return response('Invalid signature', 400);
        }

        // Handle the specific event
        switch ($event->type) {
            case 'invoice.payment_succeeded':
                $invoice = $event->data->object; // The invoice object
                $paymentIntent = $invoice->payment_intent; // The payment intent ID
                $customerId = $invoice->customer;

                // Log the payment intent ID for debugging
                Log::info('Payment Intent Succeeded: ' . $paymentIntent);

                break;
            case 'invoice.payment_failed':
                // Handle failed payments
                $invoice = $event->data->object; // The invoice object
                $paymentIntent = $invoice->payment_intent;

                // Log the failed payment
                Log::warning('Payment Failed: ' . $paymentIntent);

                break;
            // Add more cases for other events if necessary
            default:
                // Handle other events if needed
                Log::info('Unhandled Event: ' . $event->type);
        }

        return response('Webhook Handled', 200);
    }
}
