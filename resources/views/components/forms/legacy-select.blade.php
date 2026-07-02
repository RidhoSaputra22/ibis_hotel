@props([
    'options' => [],
    'selected' => null,
    'class' => 'field-control',
])

<select {{ $attributes->merge(['class' => $class]) }}>
    @foreach ($options as $key => $label)
        @php
            $optionValue = is_array($label) ? data_get($label, 'value') : (is_int($key) ? $label : $key);
            $optionLabel = is_array($label) ? data_get($label, 'label', $optionValue) : $label;
        @endphp
        <option value="{{ $optionValue }}" @selected((string) $selected === (string) $optionValue)>{{ $optionLabel }}</option>
    @endforeach
</select>
