<x-app-layout>
    <div class="min-h-screen">
        {{--@include('navigation')--}}

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">All Products</h1>

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($products as $product)
                    <div class="bg-white shadow-md rounded-lg overflow-hidden">
                        @if ($product->image)
                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-500">No Image</span>
                            </div>
                        @endif
                        <div class="p-6">
                            <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ $product->name }}</h2>
                            <p class="text-gray-600 mb-4">{{ Str::limit($product->description, 100) }}</p>
                            <p class="text-lg font-bold text-gray-900 mb-4">${{ number_format($product->price, 2) }}</p>
                            <p class="text-sm text-gray-500 mb-4">In Stock: {{ $product->stock }}</p>
                            @auth
                                @if (auth()->user()->hasRole('buyer'))
                                    <form action="{{ route('cart.add', $product) }}" method="POST">
                                        @csrf
                                        <div class="flex items-center space-x-4">
                                            <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="w-20 p-2 border rounded-md">
                                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                                Add to Cart
                                            </button>
                                        </div>
                                    </form>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                    Login to Add to Cart
                                </a>
                            @endauth
                        </div>
                    </div>
                @empty
                    <p class="text-gray-600">No products available.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>