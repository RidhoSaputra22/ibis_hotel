@props([
    'label',
    'icon' => 'square',
    'color' => '#11a9ed',
    'modal' => null,
    'href' => null,
    'requiresCashierLogin' => false,
    'loginModal' => 'cashierLoginModal',
])

@php
    $redirectUrl = $href ?: url()->current();
    $baseClass = 'group flex min-h-[110px] flex-col items-center justify-center gap-3 px-2 text-center shadow-[0_13px_28px_rgba(0,0,0,.22)] transition duration-200 hover:-translate-y-1 hover:brightness-110';
@endphp

@if ($requiresCashierLogin)
    @if ($href)
        <a
            href="{{ $href }}"
            data-open-cashier-login="{{ $loginModal }}"
            data-redirect-url="{{ $redirectUrl }}"
            class="{{ $baseClass }}"
            style="background-color: {{ $color }}"
        >
            <i data-lucide="{{ $icon }}" class="h-10 w-10 stroke-[1.8] text-white"></i>
            <span class="text-[10px] font-bold leading-tight text-white">{{ $label }}</span>
        </a>
    @else
        <button
            type="button"
            data-open-cashier-login="{{ $loginModal }}"
            data-redirect-url="{{ $redirectUrl }}"
            class="{{ $baseClass }}"
            style="background-color: {{ $color }}"
        >
            <i data-lucide="{{ $icon }}" class="h-10 w-10 stroke-[1.8] text-white"></i>
            <span class="text-[10px] font-bold leading-tight text-white">{{ $label }}</span>
        </button>
    @endif
@elseif ($href)
    <a href="{{ $href }}" class="{{ $baseClass }}" style="background-color: {{ $color }}">
        <i data-lucide="{{ $icon }}" class="h-10 w-10 stroke-[1.8] text-white"></i>
        <span class="text-[10px] font-bold leading-tight text-white">{{ $label }}</span>
    </a>
@else
    <button type="button" @if($modal) data-open-dialog="{{ $modal }}" @endif class="{{ $baseClass }}" style="background-color: {{ $color }}">
        <i data-lucide="{{ $icon }}" class="h-10 w-10 stroke-[1.8] text-white"></i>
        <span class="text-[10px] font-bold leading-tight text-white">{{ $label }}</span>
    </button>
@endif
