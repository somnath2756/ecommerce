<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Invoice Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Invoice #{{ $invoice->id }}</h3>
                        
                        <div class="mt-4 grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Customer Name</p>
                                <p class="text-base font-medium">{{ $invoice->customer_name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Invoice Date</p>
                                <p class="text-base font-medium">{{ $invoice->invoice_date }}</p>
                            </div>
                        </div>

                        <div class="mt-6">
                            <p class="text-sm text-gray-600">Items</p>
                            <pre class="mt-1 p-4 bg-gray-50 rounded-lg">{{ $invoice->items }}</pre>
                        </div>

                        <div class="mt-6">
                            <p class="text-sm text-gray-600">Total Amount</p>
                            <p class="text-xl font-bold">${{ number_format($invoice->total, 2) }}</p>
                        </div>
                    </div>

                    <div class="mt-8 flex space-x-4">
                        <a href="{{ route('invoices.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Back to List
                        </a>
                        <a href="{{ route('invoices.edit', $invoice->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Edit Invoice
                        </a>
                        <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Are you sure you want to delete this invoice?')">
                                Delete Invoice
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
