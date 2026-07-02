@props([
    'items' => [],
])

<aside class="fixed inset-y-0 left-0 z-30 flex w-[58px] flex-col border-r border-[#2f6499] bg-[#0b4d8c] shadow-xl">
    <div class="flex h-[68px] items-center justify-center border-b border-white/20">
        <div class="grid h-9 w-9 place-items-center rounded-md border border-white/40 bg-white/15 text-xs font-black text-white">
            R
        </div>
    </div>

    <nav class="flex flex-1 flex-col items-center gap-1 py-3" aria-label="Main navigation">
        @foreach ($items as $item)
            @php
                $label = data_get($item, 'label', data_get($item, 0, 'Menu'));
                $icon = data_get($item, 'icon', data_get($item, 1, 'circle'));
                $active = (bool) data_get($item, 'active', data_get($item, 2, false));
                $href = data_get($item, 'href');
            @endphp

            @if ($href)
                <a
                    href="{{ $href }}"
                    title="{{ $label }}"
                    class="group relative flex h-[72px] w-full flex-col items-center justify-center gap-1 border-l-4 transition {{ $active ? 'border-white bg-white/15 text-white' : 'border-transparent text-blue-100 hover:bg-white/10 hover:text-white' }}"
                >
                    <i data-lucide="{{ $icon }}" class="h-4 w-4"></i>
                    <span class="origin-center -rotate-90 whitespace-nowrap text-[9px] font-semibold tracking-wide">{{ $label }}</span>
                </a>
            @else
                <button
                    type="button"
                    title="{{ $label }}"
                    class="group relative flex h-[72px] w-full flex-col items-center justify-center gap-1 border-l-4 transition {{ $active ? 'border-white bg-white/15 text-white' : 'border-transparent text-blue-100 hover:bg-white/10 hover:text-white' }}"
                >
                    <i data-lucide="{{ $icon }}" class="h-4 w-4"></i>
                    <span class="origin-center -rotate-90 whitespace-nowrap text-[9px] font-semibold tracking-wide">{{ $label }}</span>
                </button>
            @endif
        @endforeach
    </nav>
</aside>
