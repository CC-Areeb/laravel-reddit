@extends('layout.master_layout')

@section('content')
    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold text-center text-gray-800 mb-12">Choose Your Subscription</h1>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Basic Plan -->
                <div class="bg-white p-6 rounded-2xl shadow-md text-center">
                    <h2 class="text-2xl font-semibold mb-2">Basic</h2>
                    <p class="text-gray-500 mb-4">Good for starters</p>
                    <div class="text-4xl font-bold text-indigo-600 mb-6">$10<span
                            class="text-base font-medium text-gray-600">/mo</span></div>
                    <ul class="text-gray-700 space-y-2 mb-6">
                        <li>✔ 1 Project</li>
                        <li>✔ Community Support</li>
                        <li>✖ Analytics</li>
                    </ul>
                    <form id="payment-form" method="POST" action="{{ route('subscribe', ['plan' => 'basic']) }}">
                        @csrf

                        <label for="plan" class="block mb-2 text-lg font-medium">Select a Plan:</label>
                        <select name="plan" id="plan" class="mb-4 p-2 border rounded w-full">
                            <option value="basic">Basic - $10/mo</option>
                            <option value="pro">Pro - $30/mo</option>
                            <option value="premium">Premium - $60/mo</option>
                        </select>

                        <div id="card-element" class="mb-4"></div>

                        <input type="hidden" name="payment_method" id="payment_method">
                        <button class="w-full bg-indigo-600 text-white py-2 px-4 rounded-lg hover:bg-indigo-700 transition">
                            Subscribe
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe('{{ $stripeKey }}');
        const elements = stripe.elements();
        const card = elements.create('card');
        card.mount('#card-element');

        document.getElementById('payment-form').addEventListener('submit', function(event) {
            event.preventDefault();

            stripe.createPaymentMethod({
                type: 'card',
                card: card
            }).then(function(result) {
                if (result.error) {
                    console.error(result.error.message);
                } else {
                    document.getElementById('payment_method').value = result.paymentMethod.id;
                    event.target.submit();
                }
            });
        });
    </script>
@endsection
