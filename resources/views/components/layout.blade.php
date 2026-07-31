<x-delegate.layout {{ $attributes }}>
    @if(isset($title))
        <x-slot:title>{{ $title }}</x-slot:title>
    @endif
    {{ $slot }}
</x-delegate.layout>
