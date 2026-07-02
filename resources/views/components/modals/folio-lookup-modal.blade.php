{{-- resources/views/components/folio-lookup-modal.blade.php --}}
@props([
    'id' => 'folioLookupModal',
    'folios' => [
        [
            'folio_no' => '0000001702',
            'description' => 'ROOM HEAD RAJA A. 21.06.06',
            'status' => 'Out',
            'pkg_chg' => false,
        ],
        [
            'folio_no' => '0000001712',
            'description' => 'ROOM PROMO CC 21.06.06',
            'status' => 'Out',
            'pkg_chg' => false,
        ],
        [
            'folio_no' => '0000001715',
            'description' => 'EVENT KTC MAKASSAR',
            'status' => 'Reg',
            'pkg_chg' => true,
        ],
        [
            'folio_no' => '0000001716',
            'description' => 'EVENT NUSANTARA LOGISTIC',
            'status' => 'Reg',
            'pkg_chg' => true,
        ],
        [
            'folio_no' => '0000001721',
            'description' => 'EVENT KETAPANG EXPO 14.06.06',
            'status' => 'Reg',
            'pkg_chg' => false,
        ],
        [
            'folio_no' => '0000001727',
            'description' => 'DEPOSIT EVT',
            'status' => 'Reg',
            'pkg_chg' => false,
        ],
    ],
])

<style>
    #{{ $id }}::backdrop {
        background: rgba(15, 23, 42, .48);
        backdrop-filter: blur(2px);
    }
</style>

<dialog
    id="{{ $id }}"
    class="m-auto w-[calc(100%-1.5rem)] max-w-4xl overflow-hidden rounded-sm border border-[#8ba5ae] bg-[#edf4f6] p-0 shadow-2xl"
    aria-labelledby="{{ $id }}Title"
>
    <div class="min-w-0">
        {{-- Header --}}
        <div class="flex h-8 items-center justify-between border-b border-[#b7c8ce] bg-[#e7f1f4] px-3">
            <div class="flex items-center gap-1.5">
                <span class="grid h-4 w-4 place-items-center rounded-[2px] border border-[#6e9daf] bg-[#d9eff6] text-[9px] font-black text-[#28647b]">
                    F
                </span>
                <h2 id="{{ $id }}Title" class="text-[11px] font-bold text-[#536b74]">
                    Folio Lookup
                </h2>
            </div>

            <button
                type="button"
                data-folio-close
                class="grid h-5 w-5 place-items-center rounded-[2px] text-[#6b7f87] transition hover:bg-[#d5e4e8] hover:text-[#244e5f]"
                aria-label="Tutup modal"
                title="Tutup"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 6 6 18M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Optional filter bar --}}
        <div class="flex flex-wrap items-center gap-2 border-b border-[#c7d5d9] bg-[#f6fafb] px-3 py-2">
            <label for="{{ $id }}Search" class="text-[10px] font-semibold text-[#677e87]">
                Search Folio
            </label>

            <div class="relative flex-1 sm:max-w-xs">
                <input
                    id="{{ $id }}Search"
                    type="search"
                    data-folio-search
                    placeholder="Nomor folio atau deskripsi"
                    class="h-7 w-full rounded-[2px] border border-[#b9cbd1] bg-white py-1 pl-2 pr-7 text-[10px] text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-[#5eabc9] focus:ring-2 focus:ring-sky-100"
                >
                <svg xmlns="http://www.w3.org/2000/svg" class="pointer-events-none absolute right-2 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="7"/>
                    <path d="m20 20-3.5-3.5"/>
                </svg>
            </div>

            <label class="ml-auto inline-flex items-center gap-1.5 text-[10px] text-[#637b84]">
                <input
                    type="checkbox"
                    data-folio-pkg-filter
                    class="h-3.5 w-3.5 rounded border-slate-300 text-sky-600 focus:ring-sky-500"
                >
                Package charge saja
            </label>
        </div>

        {{-- Table --}}
        <div class="mx-3 mt-3 overflow-auto border border-[#a9bdc4] bg-white">
            <table class="min-w-[660px] w-full border-collapse text-left text-[10px]">
                <thead class="sticky top-0 z-10 bg-[#dcecef] text-[#586f78]">
                    <tr class="border-b border-[#a9bdc4]">
                        <th class="w-[155px] border-r border-[#bfd0d5] px-2 py-1.5 font-bold">
                            <button type="button" data-sort-key="folio_no" class="inline-flex items-center gap-1 hover:text-[#216784]">
                                Folio No
                                <span class="text-[8px]">▼</span>
                            </button>
                        </th>
                        <th class="border-r border-[#bfd0d5] px-2 py-1.5 font-bold">
                            <button type="button" data-sort-key="description" class="inline-flex items-center gap-1 hover:text-[#216784]">
                                Description
                                <span class="text-[8px]">▼</span>
                            </button>
                        </th>
                        <th class="w-[80px] border-r border-[#bfd0d5] px-2 py-1.5 font-bold">
                            <button type="button" data-sort-key="status" class="inline-flex items-center gap-1 hover:text-[#216784]">
                                Status
                                <span class="text-[8px]">▼</span>
                            </button>
                        </th>
                        <th class="w-[76px] px-2 py-1.5 text-center font-bold">
                            Pkg Chg ?
                        </th>
                    </tr>
                </thead>

                <tbody data-folio-body>
                    @foreach ($folios as $index => $folio)
                        <tr
                            data-folio-row
                            data-folio-no="{{ $folio['folio_no'] }}"
                            data-description="{{ $folio['description'] }}"
                            data-status="{{ $folio['status'] }}"
                            data-pkg-chg="{{ !empty($folio['pkg_chg']) ? '1' : '0' }}"
                            class="cursor-pointer border-b border-[#d3e0e3] text-[#536b74] transition hover:bg-[#e7f3f6] {{ $index === 0 ? 'bg-[#f6dca4]' : 'bg-white' }}"
                        >
                            <td class="border-r border-[#dbe5e8] px-2 py-1.5 font-medium">
                                {{ $folio['folio_no'] }}
                            </td>
                            <td class="border-r border-[#dbe5e8] px-2 py-1.5">
                                {{ $folio['description'] }}
                            </td>
                            <td class="border-r border-[#dbe5e8] px-2 py-1.5">
                                {{ $folio['status'] }}
                            </td>
                            <td class="px-2 py-1 text-center">
                                <input
                                    type="checkbox"
                                    data-pkg-chg
                                    @checked(!empty($folio['pkg_chg']))
                                    class="h-3.5 w-3.5 rounded-[2px] border-slate-300 text-[#4f9bb9] focus:ring-[#73bed7]"
                                    aria-label="Package Charge {{ $folio['folio_no'] }}"
                                >
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <p
            data-folio-empty
            class="mx-3 hidden border border-dashed border-[#b9cbd1] bg-white px-4 py-8 text-center text-xs text-slate-400"
        >
            Data folio tidak ditemukan.
        </p>

        {{-- Bottom bar --}}
        <div class="mt-3 flex flex-wrap items-center justify-between gap-2 border-t border-[#c4d3d7] bg-[#edf4f6] px-3 py-2">
            <p data-folio-summary class="text-[10px] text-[#6e838a]">
                {{ count($folios) }} data folio tersedia.
            </p>

            <div class="flex items-center gap-2">
                <button
                    type="button"
                    data-folio-ok
                    class="inline-flex h-7 min-w-16 items-center justify-center rounded-[2px] border border-[#8eaeb9] bg-[#eef8fa] px-3 text-[10px] font-bold text-[#45707f] shadow-[inset_0_1px_0_rgba(255,255,255,.9)] transition hover:border-[#5f9eb4] hover:bg-[#dceff4] focus:outline-none focus:ring-2 focus:ring-sky-200"
                >
                    OK
                </button>

                <button
                    type="button"
                    data-folio-close
                    class="inline-flex h-7 min-w-16 items-center justify-center rounded-[2px] border border-[#b4c2c6] bg-white px-3 text-[10px] font-bold text-[#63777e] shadow-[inset_0_1px_0_rgba(255,255,255,.9)] transition hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-slate-200"
                >
                    Cancel
                </button>
            </div>
        </div>
    </div>
</dialog>

<script>
    (() => {
        const modal = document.getElementById(@js($id));
        if (!modal || modal.dataset.ready === 'true') return;

        modal.dataset.ready = 'true';

        const searchInput = modal.querySelector('[data-folio-search]');
        const packageFilter = modal.querySelector('[data-folio-pkg-filter]');
        const body = modal.querySelector('[data-folio-body]');
        const emptyMessage = modal.querySelector('[data-folio-empty]');
        const summary = modal.querySelector('[data-folio-summary]');
        const okButton = modal.querySelector('[data-folio-ok]');

        let selectedRow = modal.querySelector('[data-folio-row]');
        let sortState = { key: null, direction: 'asc' };

        function rows() {
            return [...modal.querySelectorAll('[data-folio-row]')];
        }

        function selectRow(row) {
            rows().forEach((item) => {
                item.classList.remove('bg-[#f6dca4]');
                item.classList.add('bg-white');
            });

            row.classList.remove('bg-white');
            row.classList.add('bg-[#f6dca4]');
            selectedRow = row;
        }

        function filterRows() {
            const keyword = (searchInput?.value || '').trim().toLowerCase();
            const packageOnly = Boolean(packageFilter?.checked);
            let visibleCount = 0;

            rows().forEach((row) => {
                const matchesText = (
                    row.dataset.folioNo.toLowerCase().includes(keyword) ||
                    row.dataset.description.toLowerCase().includes(keyword) ||
                    row.dataset.status.toLowerCase().includes(keyword)
                );

                const matchesPackage = !packageOnly || row.dataset.pkgChg === '1';
                const isVisible = matchesText && matchesPackage;

                row.classList.toggle('hidden', !isVisible);
                if (isVisible) visibleCount += 1;
            });

            emptyMessage.classList.toggle('hidden', visibleCount > 0);
            body.parentElement.classList.toggle('hidden', visibleCount === 0);
            summary.textContent = `${visibleCount} data folio ditemukan.`;
        }

        function sortRows(key) {
            const currentRows = rows();
            const direction = sortState.key === key && sortState.direction === 'asc'
                ? 'desc'
                : 'asc';

            currentRows.sort((a, b) => {
                const valueA = (a.dataset[key] || '').toLowerCase();
                const valueB = (b.dataset[key] || '').toLowerCase();

                return direction === 'asc'
                    ? valueA.localeCompare(valueB, 'id', { numeric: true })
                    : valueB.localeCompare(valueA, 'id', { numeric: true });
            });

            currentRows.forEach((row) => body.appendChild(row));
            sortState = { key, direction };
        }

        rows().forEach((row) => {
            row.addEventListener('click', (event) => {
                if (event.target.matches('[data-pkg-chg]')) {
                    row.dataset.pkgChg = event.target.checked ? '1' : '0';
                    return;
                }

                selectRow(row);
            });

            row.addEventListener('dblclick', () => {
                selectRow(row);
                okButton.click();
            });
        });

        modal.querySelectorAll('[data-folio-close]').forEach((button) => {
            button.addEventListener('click', () => modal.close());
        });

        modal.querySelectorAll('[data-sort-key]').forEach((button) => {
            button.addEventListener('click', () => sortRows(button.dataset.sortKey));
        });

        searchInput?.addEventListener('input', filterRows);
        packageFilter?.addEventListener('change', filterRows);

        okButton.addEventListener('click', () => {
            if (!selectedRow) {
                alert('Pilih data folio terlebih dahulu.');
                return;
            }

            const detail = {
                folioNo: selectedRow.dataset.folioNo,
                description: selectedRow.dataset.description,
                status: selectedRow.dataset.status,
                packageCharge: selectedRow.dataset.pkgChg === '1',
            };

            // Tangkap event ini dari halaman parent untuk mengisi field folio.
            window.dispatchEvent(new CustomEvent('folio-selected', { detail }));
            modal.close();
        });

        modal.addEventListener('click', (event) => {
            if (event.target === modal) modal.close();
        });

       
    })();
</script>
