@props([
    'id' => 'restaurantTableList',
    'tables' => [],
])

<section class="p-4 lg:p-6">
    <div class="min-h-[calc(100vh-145px)] overflow-hidden rounded-sm border border-slate-300 bg-[#f8fbfc] shadow-sm">
        <div class="relative flex min-h-14 items-center justify-center border-b border-slate-200 bg-white px-4 py-3">
            <h1 class="text-sm font-bold tracking-wide text-slate-600">TABLE LIST</h1>
            <div class="absolute right-4 top-1/2 hidden -translate-y-1/2 items-center gap-2 lg:flex">
                <label for="{{ $id }}Filter" class="text-[10px] text-slate-500">Filter by table:</label>
                <select id="{{ $id }}Filter" class="h-7 w-36 rounded-sm border border-slate-300 bg-white px-2 text-[10px] text-slate-600 outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-100">
                    <option value="all">Semua Table</option>
                    <option value="available">Available</option>
                    <option value="occupied">Occupied</option>
                </select>
            </div>
        </div>

        <div class="grid min-h-[calc(100vh-203px)] grid-cols-1 lg:grid-cols-[200px_1fr]">
            <aside class="border-b border-slate-200 bg-[#f2f7f8] p-4 lg:border-b-0 lg:border-r">
                <label for="{{ $id }}Search" class="sr-only">Cari table</label>
                <div class="relative">
                    <input id="{{ $id }}Search" type="search" placeholder="Cari nomor meja" class="h-9 w-full rounded-sm border border-slate-300 bg-white pl-8 pr-3 text-xs text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:ring-2 focus:ring-sky-100">
                    <i data-lucide="search" class="pointer-events-none absolute left-2.5 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-400"></i>
                </div>
                <button type="button" data-find-table class="mt-3 w-full rounded-sm border border-[#8db4c6] bg-[#e7f3f7] px-3 py-2 text-[10px] font-bold uppercase text-[#396a80] shadow-sm transition hover:bg-[#d5ebf2] active:translate-y-px">Find Table</button>
                <div class="mt-6 border-t border-slate-200 pt-4">
                    <p class="text-[10px] font-semibold uppercase tracking-wide text-slate-500">Informasi</p>
                    <p data-selected-info class="mt-2 text-xs text-slate-600">Belum ada meja dipilih.</p>
                </div>
            </aside>

            <section class="p-5 lg:p-7">
                <div data-table-grid class="grid max-w-[360px] grid-cols-3 gap-1.5" aria-label="Daftar meja restoran">
                    @foreach ($tables as $table)
                        @php $status = in_array((int) $table, [3, 7, 12, 18]) ? 'occupied' : 'available'; @endphp
                        <button type="button" data-table-number="{{ $table }}" data-table-status="{{ $status }}" class="table-card group flex h-14 items-center justify-center rounded-sm border border-[#abc0c9] bg-[#e7f2f5] text-sm font-bold text-[#497485] shadow-[inset_0_1px_0_rgba(255,255,255,.9)] transition duration-150 hover:-translate-y-0.5 hover:border-[#5497b8] hover:bg-[#d4ebf3] hover:text-[#175f82] focus:outline-none focus:ring-4 focus:ring-sky-100 {{ $loop->first ? 'ring-2 ring-[#247ead] ring-offset-1' : '' }}">{{ $table }}</button>
                    @endforeach
                </div>
                <p data-empty-message class="hidden py-10 text-center text-sm text-slate-400">Nomor meja tidak ditemukan.</p>
            </section>
        </div>
    </div>
</section>

@push('scripts')
<script>
    (() => {
        const root = document.getElementById(@js($id)) || document;
        const searchInput = document.getElementById(@js($id . 'Search'));
        const filterSelect = document.getElementById(@js($id . 'Filter'));
        const cards = [...root.querySelectorAll('.table-card')];
        const selectedInfo = root.querySelector('[data-selected-info]');
        const emptyMessage = root.querySelector('[data-empty-message]');
        const findButton = root.querySelector('[data-find-table]');
        if (!searchInput || !filterSelect) return;

        function filterTables() {
            const keyword = searchInput.value.trim().toLowerCase();
            const selectedFilter = filterSelect.value;
            let visibleTotal = 0;
            cards.forEach((card) => {
                const visible = card.dataset.tableNumber.toLowerCase().includes(keyword) && (selectedFilter === 'all' || card.dataset.tableStatus === selectedFilter);
                card.classList.toggle('hidden', !visible);
                if (visible) visibleTotal++;
            });
            emptyMessage.classList.toggle('hidden', visibleTotal > 0);
        }

        searchInput.addEventListener('input', filterTables);
        filterSelect.addEventListener('change', filterTables);
        findButton?.addEventListener('click', () => { searchInput.focus(); filterTables(); });
        cards.forEach((card) => card.addEventListener('click', () => {
            cards.forEach((item) => item.classList.remove('ring-2', 'ring-[#247ead]', 'ring-offset-1'));
            card.classList.add('ring-2', 'ring-[#247ead]', 'ring-offset-1');
            selectedInfo.innerHTML = `Meja <strong>${card.dataset.tableNumber}</strong> dipilih.<br>Status: <strong>${card.dataset.tableStatus === 'available' ? 'Available' : 'Occupied'}</strong>`;
        }));
    })();
</script>
@endpush
