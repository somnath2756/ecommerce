<x-app-layout>
    @php
        $errors = session()->get('errors', new \Illuminate\Support\MessageBag);
    @endphp

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Invoice') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('invoices.store') }}" method="POST">
                        @csrf

                        <div class="mb-4" x-data="customerSearch()">
                            <label for="customer_search" class="block text-sm font-medium text-gray-700">Search Customer</label>
                            <div class="relative">
                                <input
                                    type="text"
                                    id="customer_search"
                                    x-model="search"
                                    @input.debounce.300ms="searchCustomers()"
                                    placeholder="Search by name or phone number..."
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >
                                
                                <input type="hidden" name="customer_id" :value="selectedCustomerId">
                                
                                <!-- Dropdown for results -->
                                <div
                                    x-show="isOpen"
                                    @click.away="isOpen = false"
                                    class="absolute z-10 w-full mt-1 bg-white rounded-md shadow-lg"
                                >
                                    <ul class="max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm">
                                        <template x-for="customer in customers" :key="customer.id">
                                            <li
                                                @click="selectCustomer(customer)"
                                                class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-indigo-50"
                                            >
                                                <div class="flex items-center">
                                                    <span x-text="customer.name" class="font-normal block truncate"></span>
                                                    <span x-text="customer.phone" class="ml-2 text-gray-500"></span>
                                                </div>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </div>
                            
                            <!-- Selected customer details -->
                            <div x-show="selectedCustomer !== null" class="mt-2 p-3 border rounded-md bg-gray-50">
                                <h4 class="font-medium text-gray-900">Selected Customer:</h4>
                                <p class="text-sm text-gray-600" x-text="selectedCustomer ? selectedCustomer.name : ''"></p>
                                <p class="text-sm text-gray-600" x-text="selectedCustomer ? selectedCustomer.email : ''"></p>
                                <p class="text-sm text-gray-600" x-text="selectedCustomer ? selectedCustomer.phone : ''"></p>
                            </div>
                            
                            <x-input-error :messages="$errors->get('customer_id')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="invoice_date" class="block text-sm font-medium text-gray-700">Invoice Date</label>
                                <input type="date" name="invoice_date" id="invoice_date" value="{{ old('invoice_date', date('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <x-input-error :messages="$errors->get('invoice_date')" class="mt-2" />
                            </div>
                            <div>
                                <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                                <input type="date" name="due_date" id="due_date" value="{{ old('due_date', date('Y-m-d', strtotime('+30 days'))) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Invoice Items</label>
                            <div id="invoice-items" class="space-y-4">
                                <div class="item-row grid grid-cols-1 md:grid-cols-4 gap-4 p-4 border rounded-lg">
                                    <div>
                                        <label class="block text-xs text-gray-500">Product</label>
                                        <select name="items[0][product_id]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 product-select" required>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" data-price="{{ $product->price }}">{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="product-error"></div>
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-500">Description</label>
                                        <input type="text" name="items[0][description]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-500">Quantity</label>
                                        <input type="number" name="items[0][quantity]" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-500">Unit Price</label>
                                        <input type="number" step="0.01" name="items[0][unit_price]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                        <div class="subtotal text-sm text-gray-600 mt-1"></div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" id="add-item" class="mt-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                                Add Item
                            </button>
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        </div>

                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('invoices.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                                Cancel
                            </a>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                Create Invoice
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function customerSearch() {
            return {
                search: '',
                customers: [],
                selectedCustomer: null,
                selectedCustomerId: '',
                isOpen: false,
                
                async searchCustomers() {
                    if (this.search.length < 2) {
                        this.customers = [];
                        this.isOpen = false;
                        return;
                    }
                    
                    try {
                        const response = await fetch(`/api/customers/search?q=${encodeURIComponent(this.search)}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                // Add CSRF token for Laravel
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });
                        
                        if (!response.ok) throw new Error('Search failed');
                        
                        const data = await response.json();
                        this.customers = data;
                        this.isOpen = this.customers.length > 0;
                    } catch (error) {
                        console.error('Error searching customers:', error);
                        this.customers = [];
                        this.isOpen = false;
                    }
                },
                
                selectCustomer(customer) {
                    if (!customer) return;
                    
                    this.selectedCustomer = customer;
                    this.selectedCustomerId = customer.id;
                    this.search = customer.name;
                    this.isOpen = false;
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const itemsContainer = document.getElementById('invoice-items');
            const addItemButton = document.getElementById('add-item');
            let itemCount = 1;

            // Function to check for duplicate products
            function isDuplicateProduct(selectedProductId, currentRow) {
                const allSelects = itemsContainer.querySelectorAll('.product-select');
                for (let select of allSelects) {
                    if (select.closest('.item-row') !== currentRow && select.value === selectedProductId) {
                        return true;
                    }
                }
                return false;
            }

            // Modified product selection handler
            function handleProductSelection(select) {
                const row = select.closest('.item-row');
                const selectedProductId = select.value;
                const errorDiv = row.querySelector('.product-error');

                // Clear previous error
                errorDiv.innerHTML = '';

                if (isDuplicateProduct(selectedProductId, row)) {
                    // Create error component
                    const error = document.createElement('div');
                    error.innerHTML = `
                        <x-input-error :messages="['This product is already in the invoice. Please select a different product.']" class="mt-2" />
                    `;
                    errorDiv.appendChild(error);
                    select.value = ''; // Reset selection
                    return;
                }

                const priceInput = row.querySelector('input[name$="[unit_price]"]');
                const selectedOption = select.options[select.selectedIndex];
                priceInput.value = selectedOption.dataset.price;
                calculateRowTotal(row);
            }

            // Function to calculate row total
            function calculateRowTotal(row) {
                const quantity = row.querySelector('input[name$="[quantity]"]').value || 0;
                const price = row.querySelector('input[name$="[unit_price]"]').value || 0;
                const subtotal = quantity * price;
                
                // If you want to display subtotal (add this div to your HTML structure)
                const subtotalDiv = row.querySelector('.subtotal');
                if (subtotalDiv) {
                    subtotalDiv.textContent = `Subtotal: $${subtotal.toFixed(2)}`;
                }
            }

            // Add event listeners to first row
            const firstRow = document.querySelector('.item-row');
            firstRow.querySelector('.product-select').addEventListener('change', (e) => handleProductSelection(e.target));
            firstRow.querySelector('input[name$="[quantity]"]').addEventListener('input', (e) => calculateRowTotal(e.target.closest('.item-row')));
            firstRow.querySelector('input[name$="[unit_price]"]').addEventListener('input', (e) => calculateRowTotal(e.target.closest('.item-row')));

            // Add item button handler
            addItemButton.addEventListener('click', function() {
                const template = document.querySelector('.item-row').cloneNode(true);
                const inputs = template.querySelectorAll('input, select');
                
                inputs.forEach(input => {
                    const name = input.getAttribute('name');
                    input.setAttribute('name', name.replace('[0]', `[${itemCount}]`));
                    input.value = '';
                });

                // Add event listeners to new row
                template.querySelector('.product-select').addEventListener('change', (e) => handleProductSelection(e.target));
                template.querySelector('input[name$="[quantity]"]').addEventListener('input', (e) => calculateRowTotal(e.target.closest('.item-row')));
                template.querySelector('input[name$="[unit_price]"]').addEventListener('input', (e) => calculateRowTotal(e.target.closest('.item-row')));

                itemsContainer.appendChild(template);
                itemCount++;
            });

            // Initialize price for first product
            const firstSelect = document.querySelector('.product-select');
            if (firstSelect) {
                handleProductSelection(firstSelect);
            }
        });
    </script>
    @endpush
</x-app-layout>