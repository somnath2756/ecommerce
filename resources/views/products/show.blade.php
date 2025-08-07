<x-app-layout>
    <div class="py-12 debug-visible" style="min-height: 500px; background: #f0f0f0;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                        <div class="flex-shrink-0">
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-64 h-64 object-cover rounded-lg shadow-md">
                            @else
                                <div class="w-64 h-64 bg-gray-200 flex items-center justify-center text-gray-500 rounded-lg shadow-md">
                                    <span class="text-lg">No Image</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow">
                            <h2 class="font-bold text-3xl text-gray-800 leading-tight mb-2">
                                {{ $product->name }}
                            </h2>
                            <p class="text-gray-600 text-lg mb-4">₹{{ number_format($product->price, 2) }}</p>
                            <p class="text-gray-700 mb-4">{{ $product->description }}</p>
                            <p class="text-gray-800 font-semibold mb-4">
                                Stock: <span class="{{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }}">{{ $product->stock }}</span>
                            </p>
                            <p class="text-gray-500 text-sm mb-4">Sold by: {{ $product->user->name ?? 'Unknown Seller' }}</p>

                            @if (session('error'))
                                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                                    <span class="block sm:inline">{{ session('error') }}</span>
                                </div>
                            @endif

                            @if ($product->stock > 0)
                                <form action="{{ route('cart.add', $product) }}" method="POST" class="flex items-center space-x-4">
                                    @csrf
                                    <div class="flex items-center">
                                        <label for="quantity" class="mr-2 text-gray-700">Quantity:</label>
                                        <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->stock }}" class="w-20 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </div>
                                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-base text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553L16.5 4H5.455zm7 10a2 2 0 111.732 1A2 2 0 0110 11zm0 0h1a2 2 0 11-1 1.732z" />
                                        </svg>
                                        Add to Cart
                                    </button>
                                </form>
                            @else
                                <p class="text-red-600 font-semibold text-lg">Out of Stock</p>
                            @endif

                            <div class="mt-6">
                                <a href="{{ route('products.all') }}" class="text-indigo-600 hover:text-indigo-900 text-sm">&larr; Back to all products</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            console.log('show.blade.php loaded');
            console.log('Product Name:', '{{ $product->name }}');
            console.log('Product ID:', '{{ $product->id }}');
            console.log('Content Div:', document.querySelector('.py-12') ? 'Found' : 'Not Found');
        </script>
    @endpush
</x-app-layout>