<nav class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('products.all') }}" class="text-xl font-bold text-gray-800 flex items-center">
                        <x-heroicon-s-shopping-bag class="h-6 w-6 mr-2 text-gray-800" />
                        Ojas
                    </a>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                @auth
                    @if (auth()->user()->hasAnyRole(['buyer', 'admin', 'seller']))
                        <a href="{{ route('products.all') }}" class="text-gray-600 hover:text-gray-800 px-3 py-2 rounded-md text-sm font-medium flex items-center" title="All Products">
                            <x-heroicon-o-squares-2x2 class="h-5 w-5 mr-1" />
                            <span class="hidden sm:inline">All Products</span>
                        </a>
                    @endif 

                    @if (auth()->user()->hasRole('buyer'))
                        <a href="{{ route('cart.index') }}" class="text-gray-600 hover:text-gray-800 px-3 py-2 rounded-md text-sm font-medium flex items-center" title="Cart">
                            <x-heroicon-o-shopping-cart class="h-5 w-5 mr-1" />
                            <span class="hidden sm:inline">Cart(<span id="cart-count">0</span>)</span>
                        </a>
                    @endif

                    @if (auth()->user()->hasAnyRole(['buyer', 'admin', 'seller']))
                        <a href="{{ route('orders.index') }}" class="text-gray-600 hover:text-gray-800 px-3 py-2 rounded-md text-sm font-medium flex items-center" title="Orders">
                            <x-heroicon-o-clipboard-document-list class="h-5 w-5 mr-1" />
                            <span class="hidden sm:inline">Orders</span>
                        </a>
                    @endif
                    
                    @if (auth()->user()->hasRole('seller|admin'))
                        <a href="{{ route('products.index') }}" class="text-gray-600 hover:text-gray-800 px-3 py-2 rounded-md text-sm font-medium flex items-center" title="Manage Products">
                            <x-heroicon-o-cog-6-tooth class="h-5 w-5 mr-1" />
                            <span class="hidden sm:inline">Manage Products</span>
                        </a>
                        <a href="{{ route('invoices.index') }}" class="text-gray-600 hover:text-gray-800 px-3 py-2 rounded-md text-sm font-medium flex items-center" title="Invoices">
                            <x-heroicon-o-document-text class="h-5 w-5 mr-1" />
                            <span class="hidden sm:inline">Invoices</span>
                        </a>
                    @endif
                    @if (auth()->user()->hasRole('admin'))
                        <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-gray-800 px-3 py-2 rounded-md text-sm font-medium flex items-center" title="Admin Panel">
                            <x-heroicon-o-shield-check class="h-5 w-5 mr-1" />
                            <span class="hidden sm:inline">Admin Panel</span>
                        </a>
                        
                        <!-- Add this new category navigation link -->
                        <a href="{{ route('admin.categories.index') }}" class="text-gray-600 hover:text-gray-800 px-3 py-2 rounded-md text-sm font-medium flex items-center" title="Categories">
                            <x-heroicon-o-tag class="h-5 w-5 mr-1" />
                            <span class="hidden sm:inline">Categories</span>
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-600 hover:text-gray-800 px-3 py-2 rounded-md text-sm font-medium flex items-center" title="Logout">
                            <x-heroicon-o-arrow-left-on-rectangle class="h-5 w-5 mr-1" />
                            <span class="hidden sm:inline">Logout</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-800 px-3 py-2 rounded-md text-sm font-medium flex items-center" title="Login">
                        <x-heroicon-o-arrow-right-on-rectangle class="h-5 w-5 mr-1" />
                        <span class="hidden sm:inline">Login</span>
                    </a>
                    <a href="{{ route('register') }}" class="text-gray-600 hover:text-gray-800 px-3 py-2 rounded-md text-sm font-medium flex items-center" title="Register">
                        <x-heroicon-o-user-plus class="h-5 w-5 mr-1" />
                        <span class="hidden sm:inline">Register</span>
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
