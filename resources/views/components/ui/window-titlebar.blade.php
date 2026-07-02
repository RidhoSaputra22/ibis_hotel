@props([
    'title' => 'Window',
    'symbol' => 'R',
    'closeAttribute' => 'data-modal-close',
])

<div class="flex h-9 items-center justify-between border-b border-[#afc2c8] bg-[linear-gradient(180deg,#eaf5f8_0%,#d5e8ee_100%)] px-3">
    <div class="flex items-center gap-1.5">
        <span class="grid h-4 w-4 place-items-center rounded-[2px] border border-[#78a3b4] bg-[#d9f0f5] text-[9px] font-black text-[#326a7e]">{{ $symbol }}</span>
        <h2 class="text-[11px] font-bold text-[#536b74]">{{ $title }}</h2>
    </div>

    @isset($actions)
        {{ $actions }}
    @else
        <button type="button" {{ $attributes->merge(['class' => 'grid h-5 w-5 place-items-center rounded-[2px] text-[#6b7f87] transition hover:bg-[#c9e0e7] hover:text-[#244e5f]']) }} {{ $closeAttribute }} aria-label="Tutup">
            ✕
        </button>
    @endisset
</div>
