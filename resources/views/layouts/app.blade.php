<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Ojas') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <!-- Scripts -->
        <script>
            let cartCountURL='{{ route('cart.count') }}';
        </script>
        @vite(['resources/css/app.css', 'resources/js/app.js','resources/js/cartCounter.js'])
        
        <!-- {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
        @stack('styles') {{-- <--- THIS IS THE CRUCIAL LINE FOR YOUR INLINE STYLES --}} -->
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
                
            </main>

            {{-- Other scripts like Livewire/Alpine.js if you use them, usually here --}}
            {{-- Example: <script src="{{ asset('js/app.js') }}" defer></script> --}}

            @stack('scripts') {{-- This is where your pushed scripts will render --}}
        </div>
    </body>
</html>
