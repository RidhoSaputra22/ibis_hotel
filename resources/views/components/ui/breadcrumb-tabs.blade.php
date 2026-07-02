@props([
    'items' => [],
    'id' => null,
])

<div class="border-b border-slate-300 bg-[#eaf1f3] px-5 py-2">
    <div class="flex flex-wrap items-center gap-1 text-[10px] font-medium">
        @foreach ($items as $item)
            @php
                $label = data_get($item, 'label', $item);
                $href = data_get($item, 'href');
                $current = (bool) data_get($item, 'current', false);
                $itemId = data_get($item, 'id');
            @endphp

            @if (! $loop->first)
                <span class="text-slate-400">›</span>
            @endif

            @if ($current)
                <span id="{{ $itemId ?? $id }}" data-current-tab class="rounded-t border border-b-white border-slate-300 bg-white px-3 py-1.5 font-bold text-[#1871ad] shadow-sm">
                    {{ $label }}
                </span>
            @elseif ($href)
                <a href="{{ $href }}" class="rounded-t border border-slate-300 bg-white px-3 py-1.5 text-slate-600 shadow-sm hover:text-[#1871ad]">
                    {{ $label }}
                </a>
            @else
                <span class="rounded-t border border-slate-300 bg-white px-3 py-1.5 text-slate-600 shadow-sm">{{ $label }}</span>
            @endif
        @endforeach
    </div>
</div>
