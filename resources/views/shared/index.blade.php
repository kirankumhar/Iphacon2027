<x-delegate.layout>
    {!! $slot ?? '' !!}
    @yield('delegate-content')
    @yield('content')
</x-delegate.layout>
