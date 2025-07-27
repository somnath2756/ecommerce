<nav class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('products.all') }}" class="text-xl font-bold text-gray-800">E-Commerce</a>
                </div>
            </div>
            <div class="flex items-center">
                @auth
                    @if (auth()->user()->hasRole('buyer'))
                        <a href="{{ route('cart.index') }}" class="text-gray-600 hover:text-gray-800 px-3 py-2 rounded-md text-sm font-medium">Cart</a>
                        <a href="{{ route('orders.index') }}" class="text-gray-600 hover:text-gray-800 px-3 py-2 rounded-md text-sm font-medium">Orders</a>
                    @endif
                    
                    @if (auth()->user()->hasRole('seller|admin'))
                        <a href="{{ route('products.index') }}" class="text-gray-600 hover:text-gray-800 px-3 py-2 rounded-md text-sm font-medium">Manage Products</a>
                    @endif
                    @if (auth()->user()->hasRole('admin'))
                        <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-gray-800 px-3 py-2 rounded-md text-sm font-medium">Admin Panel</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-600 hover:text-gray-800 px-3 py-2 rounded-md text-sm font-medium">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-800 px-3 py-2 rounded-md text-sm font-medium">Login</a>
                    <a href="{{ route('register') }}" class="text-gray-600 hover:text-gray-800 px-3 py-2 rounded-md text-sm font-medium">Register</a>
                @endauth
            </div>
        </div>
    </div>
</nav>