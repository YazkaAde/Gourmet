<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    </head>
    <body class="font-sans antialiased">
        @php
            $role = Auth::check() ? Auth::user()->role : null;
        @endphp

        {{-- layout navbar --}}
        @if($role === 'customer')
            <div class="min-h-screen bg-gray-100">
                @include('layouts.navigation')
                
                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="py-6 px-4 sm:px-6 lg:px-8">
                    {{ $slot }}
                </main>
            </div>
        @else
            {{-- layout sidebar --}}
            @include('layouts.navigasi')
            
            <div class="sm:ml-64 bg-gray-100 min-h-screen">
                <div class="fixed w-full">
                    <!-- Page Heading -->
                    @isset($header)
                        <div class="bg-white shadow p-6 mb-6">
                            <h1 class="text-2xl font-bold text-gray-800">{{ $header }}</h1>
                        </div>
                    @endisset
                </div>
                <!-- Page Content -->
                <main class="p-4 pt-10">
                    {{ $slot }}
                </main>
            </div>
        @endif
        @stack('scripts')
    </body>
</html>