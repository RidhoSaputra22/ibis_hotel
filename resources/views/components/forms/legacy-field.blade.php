@props([
    'label' => null,
    'for' => null,
    'labelClass' => 'field-label',
])

<div {{ $attributes->class([]) }}>
    @if ($label)
        <label for="{{ $for }}" class="{{ $labelClass }}">{{ $label }}</label>
    @endif
    {{ $slot }}
</div>
