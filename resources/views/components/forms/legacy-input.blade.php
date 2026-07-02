@props([
    'type' => 'text',
    'value' => null,
    'class' => 'field-control',
])

<input type="{{ $type }}" value="{{ $value }}" {{ $attributes->merge(['class' => $class]) }}>
