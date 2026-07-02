@props([
    'zones' => [],
])

<aside class="border-b border-slate-200 bg-[#eef5f6] p-2 xl:border-b-0 xl:border-r">
    <p class="mb-2 px-1 text-center text-[9px] font-bold uppercase tracking-wide text-slate-500">Table</p>

    <div class="grid grid-cols-2 gap-1 xl:grid-cols-1">
        @foreach ($zones as $index => $zone)
            <button type="button" data-zone="{{ $zone }}" class="zone-button flex h-9 items-center justify-center rounded-sm border border-[#b7cbd2] bg-[#e3eff2] text-xs font-bold text-[#557984] shadow-[inset_0_1px_0_rgba(255,255,255,.9)] transition hover:border-[#6eafc8] hover:bg-[#d5ebf2] hover:text-[#226884] {{ $index === 0 ? 'ring-2 ring-[#2c88b4] ring-offset-1' : '' }}">{{ $zone }}</button>
        @endforeach
    </div>

    <button type="button" data-open-dialog="moveSplitModal" class="mt-2 w-full rounded-sm border border-slate-300 bg-white px-1 py-2 text-[9px] font-bold text-slate-600 shadow-[inset_0_1px_0_rgba(255,255,255,.9)] hover:bg-slate-50">Move<br>Split</button>
    <button type="button" data-open-dialog="reservationFolioLookupModal" class="mt-1 w-full rounded-sm border border-slate-300 bg-white px-1 py-2 text-[9px] font-bold text-slate-600 shadow-[inset_0_1px_0_rgba(255,255,255,.9)] hover:bg-slate-50">Row List</button>
</aside>
