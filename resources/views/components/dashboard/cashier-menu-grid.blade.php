@props([
    'master' => [],
    'transaction' => [],
    'batch' => [],
    'reports' => [],
])

<section class="px-5 py-5 lg:px-7">
    <div class="mb-6 flex items-center gap-3">
        <button type="button" class="grid h-8 w-8 place-items-center rounded-full border border-white/30 text-white transition hover:bg-white/10" title="Kembali">
            <i data-lucide="arrow-left" class="h-4 w-4"></i>
        </button>
        <div>
            <h1 class="text-xl font-bold tracking-tight text-white">FB &amp; Shop Cashier</h1>
            <p class="mt-0.5 text-xs text-slate-400">Kelola transaksi, outlet, kasir, dan laporan.</p>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-[180px_minmax(360px,1fr)_130px_minmax(430px,1.35fr)]">
        <x-ui.menu-tile-section title="Master" :items="$master" color="#f5837d" grid-class="grid-cols-2" />
        <x-ui.menu-tile-section title="Transaction" :items="$transaction" color="#11a9ed" grid-class="grid-cols-2 sm:grid-cols-3 lg:grid-cols-4" />
        <x-ui.menu-tile-section title="Batch" :items="$batch" color="#9f79b7" grid-class="grid-cols-1" />
        <x-ui.menu-tile-section title="Report" :items="$reports" color="#07913d" grid-class="grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5" />
    </div>
</section>

<footer class="flex flex-wrap items-center justify-between gap-3 border-t border-white/10 px-5 py-3 text-[10px] text-slate-400 lg:px-7">
    <span>Cashier Dashboard · Tailwind UI</span>
    <span>{{ now()->format('H:i') }} · {{ now()->format('d/m/Y') }}</span>
</footer>
