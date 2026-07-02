@props([
    'id' => 'moveSplitModal',
    'tableNo' => '01',
    'orderNo' => '00000017',
    'items' => [
        [
            'seq' => '000100',
            'service_code' => 'FNB-4002',
            'description' => 'Chicken Teriyaki Cup',
            'seat_no' => '1',
            'qty' => 1,
            'split' => 'A',
            'split_qty' => 1,
        ],
        [
            'seq' => '000200',
            'service_code' => 'FNB-4008',
            'description' => 'Peach Strawberry',
            'seat_no' => '1',
            'qty' => 1,
            'split' => 'A',
            'split_qty' => 1,
        ],
    ],
])

<style>
    #{{ $id }}::backdrop {
        background: rgba(15, 23, 42, .45);
        backdrop-filter: blur(2px);
    }
</style>

<dialog
    id="{{ $id }}"
    class="m-auto w-[calc(100%-1.5rem)] max-w-6xl overflow-hidden rounded-[2px] border border-[#91aab2] bg-[#edf4f6] p-0 shadow-2xl"
>
    <div class="min-w-0">

        {{-- Header --}}
        <div class="flex h-9 items-center justify-between border-b border-[#afc2c8] bg-[linear-gradient(180deg,#eaf5f8_0%,#d5e8ee_100%)] px-3">
            <div class="flex items-center gap-1.5">
                <span class="grid h-4 w-4 place-items-center rounded-[2px] border border-[#78a3b4] bg-[#d9f0f5] text-[9px] font-black text-[#326a7e]">
                    M
                </span>

                <h2 class="text-[11px] font-bold text-[#536b74]">
                    Move Split
                </h2>
            </div>

            <button
                type="button"
                data-close-move-split
                class="grid h-5 w-5 place-items-center rounded-[2px] text-[#6b7f87] transition hover:bg-[#c9e0e7] hover:text-[#244e5f]"
            >
                ✕
            </button>
        </div>

        {{-- Bill Info --}}
        <div class="border-b border-[#c5d4d8] bg-[#f4f8f9] px-4 py-3">
            <p class="mb-2 text-[10px] font-bold uppercase tracking-wide text-[#667c84]">
                Bill Info
            </p>

            <div class="grid max-w-md grid-cols-[110px_1fr] gap-x-3 gap-y-2">
                <label class="text-[10px] font-bold uppercase text-[#6b7e85]">
                    Table
                </label>

                <input
                    id="{{ $id }}TableNo"
                    type="text"
                    value="{{ $tableNo }}"
                    readonly
                    class="h-7 rounded-[2px] border border-[#c0d0d5] bg-[#e5edef] px-2 text-[10px] font-semibold text-[#64777e] outline-none"
                >

                <label class="text-[10px] font-bold uppercase text-[#6b7e85]">
                    Order #
                </label>

                <input
                    id="{{ $id }}OrderNo"
                    type="text"
                    value="{{ $orderNo }}"
                    readonly
                    class="h-7 rounded-[2px] border border-[#c0d0d5] bg-[#e5edef] px-2 text-[10px] font-semibold text-[#64777e] outline-none"
                >
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-[1fr_82px]">

            {{-- Tabel item --}}
            <section class="min-w-0 p-3">
                <div class="max-h-[430px] overflow-auto border border-[#a9bdc4] bg-white">
                    <table class="min-w-[830px] w-full border-collapse text-left text-[10px] text-[#516970]">
                        <thead class="sticky top-0 z-10 bg-[#dcecf0]">
                            <tr class="border-b border-[#b7cbd1]">
                                <th class="w-7 border-r border-[#c5d5d9] px-1 py-2"></th>

                                <th class="w-[95px] border-r border-[#c5d5d9] px-2 py-2 font-bold">
                                    Seq
                                </th>

                                <th class="w-[135px] border-r border-[#c5d5d9] px-2 py-2 font-bold">
                                    Serv. Code
                                </th>

                                <th class="border-r border-[#c5d5d9] px-2 py-2 font-bold">
                                    Description
                                </th>

                                <th class="w-[75px] border-r border-[#c5d5d9] px-2 py-2 text-center font-bold">
                                    Seat #
                                </th>

                                <th class="w-[70px] border-r border-[#c5d5d9] px-2 py-2 text-center font-bold">
                                    Qty
                                </th>

                                <th class="w-[95px] border-r border-[#c5d5d9] px-2 py-2 text-center font-bold">
                                    Split
                                </th>

                                <th class="w-[95px] px-2 py-2 text-center font-bold">
                                    Split Qty
                                </th>
                            </tr>
                        </thead>

                        <tbody id="{{ $id }}Body">
                            @foreach ($items as $index => $item)
                                <tr
                                    data-move-row
                                    data-seq="{{ $item['seq'] }}"
                                    data-service-code="{{ $item['service_code'] }}"
                                    data-description="{{ $item['description'] }}"
                                    data-seat-no="{{ $item['seat_no'] }}"
                                    data-qty="{{ $item['qty'] }}"
                                    class="cursor-pointer border-b border-[#d8e3e6] transition hover:bg-[#e7f3f6] {{ $index === 0 ? 'bg-[#f5d69a]' : 'bg-white' }}"
                                >
                                    <td class="border-r border-[#e0e9eb] px-1 py-2 text-center">
                                        <span
                                            data-row-arrow
                                            class="{{ $index === 0 ? '' : 'invisible' }} text-[10px] font-bold text-[#5c6e73]"
                                        >
                                            ◆
                                        </span>
                                    </td>

                                    <td class="border-r border-[#e0e9eb] px-2 py-2 font-medium">
                                        {{ $item['seq'] }}
                                    </td>

                                    <td class="border-r border-[#e0e9eb] px-2 py-2">
                                        {{ $item['service_code'] }}
                                    </td>

                                    <td class="border-r border-[#e0e9eb] px-2 py-2 font-medium">
                                        {{ $item['description'] }}
                                    </td>

                                    <td class="border-r border-[#e0e9eb] px-2 py-1 text-center">
                                        <input
                                            type="number"
                                            min="1"
                                            value="{{ $item['seat_no'] }}"
                                            data-seat-input
                                            class="h-6 w-12 rounded-[2px] border border-transparent bg-transparent text-center text-[10px] outline-none focus:border-[#77b6ce] focus:bg-white"
                                        >
                                    </td>

                                    <td class="border-r border-[#e0e9eb] px-2 py-1 text-center">
                                        <input
                                            type="number"
                                            min="1"
                                            value="{{ $item['qty'] }}"
                                            data-qty-input
                                            class="h-6 w-12 rounded-[2px] border border-transparent bg-transparent text-center text-[10px] outline-none focus:border-[#77b6ce] focus:bg-white"
                                        >
                                    </td>

                                    <td class="border-r border-[#e0e9eb] px-2 py-1 text-center">
                                        <select
                                            data-split-input
                                            class="h-6 w-16 rounded-[2px] border border-transparent bg-transparent text-center text-[10px] outline-none focus:border-[#77b6ce] focus:bg-white"
                                        >
                                            @foreach (range('A', 'J') as $splitZone)
                                                <option
                                                    value="{{ $splitZone }}"
                                                    @selected($item['split'] === $splitZone)
                                                >
                                                    {{ $splitZone }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td class="px-2 py-1 text-center">
                                        <input
                                            type="number"
                                            min="1"
                                            max="{{ $item['qty'] }}"
                                            value="{{ $item['split_qty'] }}"
                                            data-split-qty-input
                                            class="h-6 w-14 rounded-[2px] border border-transparent bg-transparent text-center text-[10px] outline-none focus:border-[#77b6ce] focus:bg-white"
                                        >
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <p class="mt-2 text-[10px] text-[#70858c]">
                    Pilih baris item, tentukan tujuan split pada tombol sebelah kanan, lalu tekan Process.
                </p>
            </section>

            {{-- Tombol tujuan split --}}
            <aside class="border-t border-[#c5d4d8] bg-[#edf4f6] p-3 lg:border-l lg:border-t-0">
                <p class="mb-2 text-center text-[9px] font-bold uppercase tracking-wide text-[#6b8189]">
                    Split To
                </p>

                <div class="grid grid-cols-5 gap-1 lg:grid-cols-1">
                    @foreach (range('A', 'J') as $index => $zone)
                        <button
                            type="button"
                            data-split-zone="{{ $zone }}"
                            class="split-zone-button flex h-9 items-center justify-center rounded-[2px] border border-[#b6c9cf] bg-[#e1eef1] text-[10px] font-bold text-[#50747e] shadow-[inset_0_1px_0_rgba(255,255,255,.9)] transition hover:border-[#69a9c2] hover:bg-[#d3eaf0] hover:text-[#236884] {{ $index === 0 ? 'ring-2 ring-[#4d9dbd] ring-offset-1' : '' }}"
                        >
                            {{ $zone }}
                        </button>
                    @endforeach
                </div>
            </aside>
        </div>

        {{-- Footer --}}
        <div class="flex flex-wrap items-center justify-between gap-3 border-t border-[#c5d4d8] bg-[#edf4f6] px-4 py-3">
            <p id="{{ $id }}SelectedInfo" class="text-[10px] text-[#6d838a]">
                Tujuan split: <strong>A</strong>
            </p>

            <div class="flex items-center gap-2">
                <button
                    type="button"
                    id="{{ $id }}Process"
                    class="inline-flex h-8 min-w-[88px] items-center justify-center rounded-[2px] border border-[#7caebb] bg-[#e2f3f7] px-4 text-[10px] font-bold text-[#3d6e7e] shadow-[inset_0_1px_0_rgba(255,255,255,.95)] transition hover:border-[#4f97ae] hover:bg-[#d0eaf1]"
                >
                    PROCESS
                </button>

                <button
                    type="button"
                    data-close-move-split
                    class="inline-flex h-8 min-w-[88px] items-center justify-center rounded-[2px] border border-[#b2c1c5] bg-white px-4 text-[10px] font-bold text-[#61777f] shadow-[inset_0_1px_0_rgba(255,255,255,.95)] transition hover:bg-slate-100"
                >
                    CANCEL
                </button>
            </div>
        </div>
    </div>
</dialog>

<script>
    (() => {
        const modal = document.getElementById(@js($id));

        if (!modal || modal.dataset.ready === 'true') {
            return;
        }

        modal.dataset.ready = 'true';

        const rows = () => [...modal.querySelectorAll('[data-move-row]')];
        const zoneButtons = [...modal.querySelectorAll('[data-split-zone]')];
        const processButton = document.getElementById(@js($id . 'Process'));
        const selectedInfo = document.getElementById(@js($id . 'SelectedInfo'));

        let selectedRow = modal.querySelector('[data-move-row]');
        let selectedZone = 'A';

        function selectRow(row) {
            rows().forEach((item) => {
                item.classList.remove('bg-[#f5d69a]');
                item.classList.add('bg-white');

                const arrow = item.querySelector('[data-row-arrow]');
                if (arrow) {
                    arrow.classList.add('invisible');
                }
            });

            row.classList.remove('bg-white');
            row.classList.add('bg-[#f5d69a]');

            const arrow = row.querySelector('[data-row-arrow]');
            if (arrow) {
                arrow.classList.remove('invisible');
            }

            selectedRow = row;
        }

        function selectZone(zone) {
            selectedZone = zone;

            zoneButtons.forEach((button) => {
                button.classList.remove(
                    'ring-2',
                    'ring-[#4d9dbd]',
                    'ring-offset-1'
                );
            });

            const activeButton = zoneButtons.find(
                (button) => button.dataset.splitZone === zone
            );

            if (activeButton) {
                activeButton.classList.add(
                    'ring-2',
                    'ring-[#4d9dbd]',
                    'ring-offset-1'
                );
            }

            selectedInfo.innerHTML = `Tujuan split: <strong>${zone}</strong>`;

            if (selectedRow) {
                const splitSelect = selectedRow.querySelector('[data-split-input]');

                if (splitSelect) {
                    splitSelect.value = zone;
                }
            }
        }

        rows().forEach((row) => {
            row.addEventListener('click', (event) => {
                if (
                    event.target.matches('[data-seat-input]') ||
                    event.target.matches('[data-qty-input]') ||
                    event.target.matches('[data-split-input]') ||
                    event.target.matches('[data-split-qty-input]')
                ) {
                    return;
                }

                selectRow(row);
            });

            row.querySelector('[data-split-input]')?.addEventListener('change', (event) => {
                selectRow(row);
                selectZone(event.target.value);
            });
        });

        zoneButtons.forEach((button) => {
            button.addEventListener('click', () => {
                selectZone(button.dataset.splitZone);
            });
        });

        modal.querySelectorAll('[data-close-move-split]').forEach((button) => {
            button.addEventListener('click', () => {
                modal.close();
            });
        });

        processButton.addEventListener('click', () => {
            if (!selectedRow) {
                alert('Pilih item yang ingin dipindahkan terlebih dahulu.');
                return;
            }

            const qty = Number(
                selectedRow.querySelector('[data-qty-input]').value
            );

            const splitQty = Number(
                selectedRow.querySelector('[data-split-qty-input]').value
            );

            if (!splitQty || splitQty < 1 || splitQty > qty) {
                alert('Split Qty harus minimal 1 dan tidak boleh melebihi Qty.');
                return;
            }

            const result = {
                tableNo: document.getElementById(@js($id . 'TableNo')).value,
                orderNo: document.getElementById(@js($id . 'OrderNo')).value,
                destination: selectedZone,
                item: {
                    seq: selectedRow.dataset.seq,
                    serviceCode: selectedRow.dataset.serviceCode,
                    description: selectedRow.dataset.description,
                    seatNo: Number(
                        selectedRow.querySelector('[data-seat-input]').value
                    ),
                    qty,
                    splitQty,
                    splitZone: selectedRow.querySelector('[data-split-input]').value,
                },
            };

            window.dispatchEvent(
                new CustomEvent('move-split-processed', {
                    detail: result
                })
            );

            alert(
                `Item "${result.item.description}" dipindahkan ke split ${result.destination}.`
            );

            modal.close();
        });

        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                modal.close();
            }
        });

       
    })();
</script>
