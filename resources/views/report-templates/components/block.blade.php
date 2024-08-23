@php
    $components ??= [];
@endphp

<div id="{{ $id }}" class="{{ $classes }}">
    @foreach($components as $component)
        {{ $component }}
    @endforeach
</div>
