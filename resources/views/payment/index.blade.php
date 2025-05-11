@extends('layout.master_layout')

@section('content')
    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold text-center text-gray-800 mb-12">Choose Your Subscription</h1>

            <div class="bg-white p-6 rounded-2xl shadow-md text-center">
                <h2 class="text-2xl font-semibold mb-2">Select a Plan</h2>

                <!-- Dropdown to select plan -->
                <select id="subscription-dropdown" class="mb-4 p-2 border rounded w-full">
                    <option value="basic">Basic - $10/mo</option>
                    <option value="pro">Pro - $20/mo</option>
                    <option value="premium">Premium - $30/mo</option>
                </select>

                <!-- Hidden input to store selected plan -->
                <input type="hidden" name="selected_plan" id="selected_plan" value="basic">

                <!-- Plan Info -->
                <div id="plan-info" class="text-gray-500 mb-4">
                    <p>Good for casual users</p>
                    <div class="text-4xl font-bold text-indigo-600 mb-6">$10<span
                            class="text-base font-medium text-gray-600">/mo</span></div>
                    <ul id="plan-features" class="text-gray-700 space-y-2 mb-6">
                        <li>✔ Ad-Free Browsing</li>
                        <li>✔ Custom Profile Themes (limited)</li>
                        <li>✔ Increased Character Limit</li>
                        <li>✔ Scheduled Posts (3/month)</li>
                        <li>✔ 1 Custom Award/month</li>
                        <li>✔ Monthly Coin Allowance (Small)</li>
                    </ul>
                </div>


                <!-- Payment Form -->
                <form id="payment-form" method="POST" action="{{ route('subscribe', ['plan' => 'basic']) }}">
                    @csrf
                    <div id="card-element" class="mb-4"></div>
                    <input type="hidden" name="payment_method" id="payment_method">
                    <button class="w-full bg-indigo-600 text-white py-2 px-4 rounded-lg hover:bg-indigo-700 transition">
                        Subscribe
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe('{{ $stripeKey }}');
        const elements = stripe.elements();

        // Create card element for payment
        const card = elements.create('card');
        card.mount('#card-element');

        // Handle plan change
        const subscriptionDropdown = document.getElementById('subscription-dropdown');
        const planInfo = document.getElementById('plan-info');
        const planFeatures = document.getElementById('plan-features');
        const selectedPlanInput = document.getElementById('selected_plan');
        const paymentForm = document.getElementById('payment-form');

        subscriptionDropdown.addEventListener('change', function() {
            const selectedPlan = subscriptionDropdown.value;

            // Update hidden input with the selected plan
            selectedPlanInput.value = selectedPlan;

            if (selectedPlan === 'basic') {
                planInfo.innerHTML = `
        <p>Good for casual users</p>
        <div class="text-4xl font-bold text-indigo-600 mb-6">$10<span class="text-base font-medium text-gray-600">/mo</span></div>
        <ul class="text-gray-700 space-y-2 mb-6">
            <li>✔ Ad-Free Browsing</li>
            <li>✔ Custom Profile Themes (limited)</li>
            <li>✔ Increased Character Limit</li>
            <li>✔ Scheduled Posts (3/month)</li>
            <li>✔ 1 Custom Award/month</li>
            <li>✔ Monthly Coin Allowance (Small)</li>
        </ul>
    `;
                paymentForm.action = '{{ route('subscribe', ['plan' => 'basic']) }}';
            } else if (selectedPlan === 'pro') {
                planInfo.innerHTML = `
        <p>Ideal for active users and creators</p>
        <div class="text-4xl font-bold text-indigo-600 mb-6">$20<span class="text-base font-medium text-gray-600">/mo</span></div>
        <ul class="text-gray-700 space-y-2 mb-6">
            <li>✔ All Basic features</li>
            <li>✔ Rich Post Format (galleries, embeds)</li>
            <li>✔ Analytics Dashboard</li>
            <li>✔ Early Access to New Features</li>
            <li>✔ Increased Voting Power (+1.5x)</li>
            <li>✔ Unlimited Scheduled Posts</li>
            <li>✔ 2 Custom Awards/month</li>
            <li>✔ Monthly Coin Allowance (Medium)</li>
        </ul>
    `;
                paymentForm.action = '{{ route('subscribe', ['plan' => 'pro']) }}';
            } else if (selectedPlan === 'premium') {
                planInfo.innerHTML = `
        <p>Best for influencers & community founders</p>
        <div class="text-4xl font-bold text-indigo-600 mb-6">$30<span class="text-base font-medium text-gray-600">/mo</span></div>
        <ul class="text-gray-700 space-y-2 mb-6">
            <li>✔ All Pro features</li>
            <li>✔ Animated Avatars & Banners</li>
            <li>✔ Comment Highlighting (1/day)</li>
            <li>✔ Private Moderator Chat</li>
            <li>✔ Larger Upload Limits</li>
            <li>✔ Premium-Only Communities</li>
            <li>✔ Unlimited Custom Awards</li>
            <li>✔ Monthly Coin Allowance (Large)</li>
            <li>✔ Loyalty Badge (Bronze/Silver/Gold)</li>
        </ul>
    `;
                paymentForm.action = '{{ route('subscribe', ['plan' => 'premium']) }}';
            }

        });

        // Handle form submission for any plan
        paymentForm.addEventListener('submit', function(event) {
            event.preventDefault();

            // Add the selected_plan value to the form before submission
            const selectedPlanValue = document.getElementById('selected_plan').value;
            const selectedPlanInput = document.createElement('input');
            selectedPlanInput.type = 'hidden';
            selectedPlanInput.name = 'selected_plan';
            selectedPlanInput.value = selectedPlanValue;
            paymentForm.appendChild(selectedPlanInput);

            // Create the payment method using Stripe
            stripe.createPaymentMethod({
                type: 'card',
                card: card
            }).then(function(result) {
                if (result.error) {
                    console.error(result.error.message);
                } else {
                    document.getElementById('payment_method').value = result.paymentMethod.id;
                    paymentForm.submit(); // Submit the form after adding selected_plan
                }
            });
        });
    </script>
@endsection
