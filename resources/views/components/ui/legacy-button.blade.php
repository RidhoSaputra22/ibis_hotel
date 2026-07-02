@props([
    'as' => 'button',
    'variant' => 'default',
    'icon' => null,
])

@php
    $variants = [
        'default' => 'border-[#b4c6cb] bg-white text-[#5f767e] hover:border-[#6ba9c0] hover:bg-[#e6f4f7] hover:text-[#236984]',
        'primary' => 'border-[#79aebf] bg-[#d8edf3] text-[#3e6c7c] hover:border-[#4e99b5] hover:bg-[#c7e6ef]',
        'danger' => 'border-rose-300 bg-rose-50 text-rose-700 hover:bg-rose-100',
        'warning' => 'border-[#d1a54d] bg-[#f7d577] text-[#71541d] hover:bg-[#f5c95a]',
    ];
@endphp

@if ($as === 'a')
    <a {{ $attributes->class(['inline-flex min-h-8 items-center justify-center gap-1.5 rounded-[2px] border px-3 py-1.5 text-[10px] font-bold shadow-[inset_0_1px_0_rgba(255,255,255,.95)] transition', $variants[$variant] ?? $variants['default']]) }}>
        @if ($icon)<i data-lucide="{{ $icon }}" class="h-3.5 w-3.5"></i>@endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $attributes->get('type', 'button') }}" {{ $attributes->except('type')->class(['inline-flex min-h-8 items-center justify-center gap-1.5 rounded-[2px] border px-3 py-1.5 text-[10px] font-bold shadow-[inset_0_1px_0_rgba(255,255,255,.95)] transition', $variants[$variant] ?? $variants['default']]) }}>
        @if ($icon)<i data-lucide="{{ $icon }}" class="h-3.5 w-3.5"></i>@endif
        {{ $slot }}
    </button>
@endif
