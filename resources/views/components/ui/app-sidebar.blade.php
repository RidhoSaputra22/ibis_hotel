@props([
    'items' => [],
])

<aside
    class="fixed inset-y-0 left-0 z-30 flex w-[58px] flex-col overflow-hidden border-r border-[#2f6499] bg-[#0b4d8c] shadow-xl"
>
    {{-- Logo --}}
    <div class="flex h-[56px] shrink-0 items-center justify-center border-b border-white/20 bg-[#09457f]">
        <div
            class="grid h-8 w-8 place-items-center rounded-md border border-white/40 bg-white/15 text-xs font-black text-white"
        >
            R
        </div>
    </div>

    {{-- Navigation --}}
    <nav
        class="flex min-h-0 flex-1 flex-col overflow-y-auto py-1"
        aria-label="Main navigation"
    >
        @foreach ($items as $item)
            @php
                $label = data_get($item, 'label', data_get($item, 0, 'Menu'));
                $icon = data_get($item, 'icon', data_get($item, 1, 'circle'));
                $active = (bool) data_get($item, 'active', data_get($item, 2, false));
                $href = data_get($item, 'href');

                $itemClass = $active
                    ? 'border-l-white bg-white/15 text-white'
                    : 'border-l-transparent text-blue-100 hover:bg-white/10 hover:text-white';
            @endphp

            @if ($href)
                <a
                    href="{{ $href }}"
                    title="{{ $label }}"
                    @if ($active) aria-current="page" @endif
                    class="group relative flex min-h-[52px] w-full shrink-0 items-center justify-center border-l-4 py-2 transition duration-150 {{ $itemClass }}"
                >


                     {{-- Label: tinggi item otomatis mengikuti panjang teks --}}
                    <span
                        class=" text-center text-xs font-medium leading-none tracking-wide"
                        style="writing-mode: vertical-rl; text-orientation: mixed; transform: rotate(180deg);"
                    >
                        {{ $label }}
                    </span>
                </a>
            @else
                <button
                    type="button"
                    title="{{ $label }}"
                    class="group relative flex min-h-[52px] w-full shrink-0 items-center justify-center border-l-4 py-2 transition duration-150 {{ $itemClass }}"
                >


                    {{-- Label: tinggi item otomatis mengikuti panjang teks --}}
                    <span
                        class=" text-center text-xs font-medium leading-none tracking-wide"
                        style="writing-mode: vertical-rl; text-orientation: mixed; transform: rotate(180deg);"
                    >
                        {{ $label }}
                    </span>
                </button>
            @endif
        @endforeach
    </nav>
</aside>
