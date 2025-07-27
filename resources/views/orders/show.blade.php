<x-app-layout>
    <div class="min-h-screen">

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Order #{{ $order->id }}</h1>

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-md rounded-lg p-6">
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-800">Order Details</h2>
                    <p class="text-gray-600">Date: {{ $order->created_at->format('Y-m-d H:i') }}</p>
                    <p class="text-gray-600">Status: {{ ucfirst($order->status) }}</p>
                    <p class="text-gray-600">Total: ${{ number_format($order->total_amount, 2) }}</p>
                </div>

                <h2 class="text-xl font-semibold text-gray-800 mb-4">Items</h2>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($order->items as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $item->product->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">${{ number_format($item->price, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $item->quantity }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">${{ number_format($item->quantity * $item->price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-6">
                    <a href="{{ route('orders.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Back to Orders</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>