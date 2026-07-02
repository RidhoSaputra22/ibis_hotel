@props([
    'hotelName' => 'Ibis Makassar City Center',
    'systemName' => 'Restaurant & Cashier System',
    'username' => 'ADHA',
    'dark' => false,
    'showDepartmentBadges' => false,
])

<header class="flex h-[68px] items-center justify-between border-b {{ $dark ? 'border-white/10 bg-[#06111d]/80 backdrop-blur' : 'border-slate-300 bg-[#0a1a2a]' }} px-5 text-white shadow-md">
    <div class="flex items-center gap-3">
        <div class="grid h-9 w-9 place-items-center rounded-md bg-[#1b70b8] text-sm font-black shadow">R</div>
        <div>
            <p class="text-xs font-semibold tracking-wide">{{ $hotelName }}</p>
            <p class="text-[10px] text-slate-400">{{ $systemName }}</p>
        </div>
    </div>

    @if ($showDepartmentBadges)
        <div class="hidden items-center gap-2 sm:flex">
            <span class="grid h-8 w-8 place-items-center rounded-full bg-[#a85f5c] text-xs font-bold">F</span>
            <span class="grid h-8 w-8 place-items-center rounded-full bg-slate-500 text-xs font-bold">B</span>
            <span class="grid h-8 w-8 place-items-center rounded-full bg-[#85835c] text-xs font-bold">S</span>
        </div>
    @endif

    <div class="flex items-center gap-3 text-slate-300">
        <button type="button" class="transition hover:text-white" title="Cari"><i data-lucide="search" class="h-4 w-4"></i></button>
        <button type="button" class="transition hover:text-white" title="Notifikasi"><i data-lucide="bell" class="h-4 w-4"></i></button>
        <button type="button" class="hidden transition hover:text-white sm:inline-flex" title="Minimalkan"><i data-lucide="minus" class="h-4 w-4"></i></button>
        <button type="button" class="hidden transition hover:text-white sm:inline-flex" title="Maksimalkan"><i data-lucide="square" class="h-3.5 w-3.5"></i></button>
        <button type="button" class="transition hover:text-rose-300" title="Tutup"><i data-lucide="x" class="h-4 w-4"></i></button>
        @if ($username)
            <span class="hidden border-l border-white/10 pl-3 text-[10px] font-semibold text-slate-300 lg:inline">{{ $username }}</span>
        @endif
    </div>
</header>
