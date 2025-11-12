@props(['class' => ''])

<img src="{{ asset('images/gourmet.png') }}" 
     alt="{{ config('app.name', 'Restaurant') }}"
     class="object-contain {{ $class }}">