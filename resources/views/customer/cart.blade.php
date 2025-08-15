<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Your Cart') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($carts->isEmpty())
                <div class="bg-white p-6 rounded-lg shadow">
                    <p>Your cart is empty</p>
                    <a href="{{ route('customer.menu.index') }}" class="text-primary-600 hover:text-primary-800">Browse Menu</a>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="space-y-4">
                            @foreach($carts as $cart)
                                <div class="flex justify-between items-center p-4 border rounded-lg">
                                    <div>
                                        <h3 class="font-bold">{{ $cart->menu->name }}</h3>
                                        <p>Rp {{ number_format($cart->menu->price, 0) }}</p>
                                        <p>Quantity: {{ $cart->quantity }}</p>
                                        <p>Total: Rp {{ number_format($cart->menu->price * $cart->quantity, 0) }}</p>
                                    </div>
                                    <form action="{{ route('customer.cart.destroy', $cart->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">Remove</button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-6 flex justify-between items-center">
                            <div class="text-xl font-bold">
                                Total: Rp {{ number_format($carts->sum(function($cart) { return $cart->menu->price * $cart->quantity; }), 0) }}
                            </div>
                            <button class="bg-primary-600 text-white px-6 py-2 rounded-lg hover:bg-primary-700">
                                Checkout
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>