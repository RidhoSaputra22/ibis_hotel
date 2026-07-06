<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Gunakan ini bila Tailwind sudah menggunakan Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://unpkg.com/lucide@latest"></script>

    <title>Open Cashier</title>
</head>

<body class="min-h-screen bg-[#dce6e9] font-sans text-slate-700">

@php
    $sidebarItems = [
        ['label' => 'Favorites', 'icon' => 'star'],
        ['label' => 'Approval', 'icon' => 'circle-check'],
        ['label' => 'Banquet', 'icon' => 'calendar-days'],
        ['label' => 'Department Request', 'icon' => 'clipboard-list'],
        ['label' => 'FB & Shop Cashier', 'icon' => 'shopping-bag', 'active' => true],
        ['label' => 'Sales', 'icon' => 'badge-dollar-sign'],
    ];

    $cashierHistory = [
        [
            'date' => '15-Jun-2026',
            'shift' => '1',
            'status' => 'OPEN',
            'secondary' => '-',
            'user' => 'FBADHA',
            'open_time' => '11:41',
        ],
        [
            'date' => '14-Jun-2026',
            'shift' => '2',
            'status' => 'CLOSED',
            'secondary' => 'FBNURUL',
            'user' => 'FBADHA',
            'open_time' => '15:05',
        ],
    ];
@endphp

<div class="min-h-screen bg-[linear-gradient(135deg,#d7e3e6_0%,#f5f8f9_48%,#dce8eb_100%)]">

    {{-- Sidebar --}}
    <aside class="fixed inset-y-0 left-0 z-30 flex w-[58px] flex-col overflow-hidden border-r border-[#2f6499] bg-[#0b4d8c] shadow-xl">
        <div class="flex h-[66px] shrink-0 items-center justify-center border-b border-white/15 bg-[#083d70]">
            <div class="grid h-9 w-9 place-items-center rounded-md border border-white/35 bg-white/10 text-sm font-black text-white">
                R
            </div>
        </div>

        <nav class="flex min-h-0 flex-1 flex-col overflow-y-auto py-1">
            @foreach ($sidebarItems as $item)
                @php
                    $active = $item['active'] ?? false;
                @endphp

                <button
                    type="button"
                    title="{{ $item['label'] }}"
                    class="group relative flex min-h-[62px] w-full shrink-0 items-center justify-center border-l-4 py-2 transition {{ $active ? 'border-l-white bg-white/15 text-white' : 'border-l-transparent text-blue-100 hover:bg-white/10 hover:text-white' }}"
                >
                    <i
                        data-lucide="{{ $item['icon'] }}"
                        class="absolute left-2 top-1/2 h-3.5 w-3.5 -translate-y-1/2"
                    ></i>

                    <span
                        class="ml-4 text-center text-[8px] font-semibold leading-none tracking-wide"
                        style="writing-mode: vertical-rl; text-orientation: mixed; transform: rotate(180deg);"
                    >
                        {{ $item['label'] }}
                    </span>
                </button>
            @endforeach
        </nav>
    </aside>

    <main class="ml-[58px] min-h-screen">

        {{-- Header --}}
        <header class="flex h-[68px] items-center justify-between border-b border-slate-300 bg-[#091a2b] px-5 text-white shadow-md">
            <div class="flex items-center gap-3">
                <div class="grid h-9 w-9 place-items-center rounded-md bg-[#1a70b8] text-sm font-black">
                    R
                </div>

                <div>
                    <p class="text-xs font-semibold tracking-wide">Ibis Makassar City Center</p>
                    <p class="text-[10px] text-slate-400">Hotel Management System</p>
                </div>
            </div>

            <div class="flex items-center gap-3 text-slate-300">
                <button type="button" title="Search" class="hover:text-white">
                    <i data-lucide="search" class="h-4 w-4"></i>
                </button>

                <button type="button" title="Notification" class="hover:text-white">
                    <i data-lucide="bell" class="h-4 w-4"></i>
                </button>

                <button type="button" title="Minimize" class="hover:text-white">
                    <i data-lucide="minus" class="h-4 w-4"></i>
                </button>

                <button type="button" title="Close" class="hover:text-rose-300">
                    <i data-lucide="x" class="h-4 w-4"></i>
                </button>

                <span class="border-l border-white/10 pl-3 text-[10px] font-semibold">
                    ADHA
                </span>
            </div>
        </header>

        {{-- Tab --}}
        <div class="border-b border-slate-300 bg-[#eaf1f3] px-4 py-2">
            <div class="flex items-center gap-1 text-[10px]">
                <span class="rounded-t border border-slate-300 border-b-white bg-white px-4 py-1.5 font-bold text-[#1f6d96] shadow-sm">
                    Open Cashier
                </span>
            </div>
        </div>

        <section class="p-4">
            <div class="min-h-[calc(100vh-118px)] rounded-sm border border-slate-300 bg-[#f8fbfc] p-3 shadow-sm">

                {{-- User Header --}}
                <div class="mb-3 flex max-w-[390px] items-center gap-2 rounded-sm border border-slate-300 bg-[#eef4f5] px-3 py-2">
                    <span class="w-20 text-[10px] font-semibold text-slate-600">User</span>

                    <input
                        type="text"
                        value="FBADHA"
                        readonly
                        class="h-6 w-28 border border-slate-300 bg-slate-100 px-2 text-[10px] text-slate-600 outline-none"
                    >

                    <input
                        type="text"
                        value="Adha"
                        readonly
                        class="h-6 flex-1 border border-slate-300 bg-slate-100 px-2 text-[10px] text-slate-600 outline-none"
                    >
                </div>

                <div class="grid max-w-5xl grid-cols-1 gap-3 xl:grid-cols-[280px_minmax(430px,1fr)]">

                    {{-- Riwayat Cashier --}}
                    <section class="overflow-hidden border border-slate-300 bg-white">
                        <div class="max-h-[470px] overflow-auto">
                            <table class="w-full border-collapse text-left text-[10px]">
                                <thead class="sticky top-0 bg-[#dfecef] text-[#5d737b]">
                                    <tr class="border-b border-slate-300">
                                        <th class="w-6 border-r border-slate-300 px-1 py-2"></th>
                                        <th class="border-r border-slate-300 px-2 py-2 font-bold">Date</th>
                                        <th class="w-10 border-r border-slate-300 px-2 py-2 text-center font-bold">Shift</th>
                                        <th class="w-16 border-r border-slate-300 px-2 py-2 text-center font-bold">Status</th>
                                        <th class="w-12 px-2 py-2 text-center font-bold">2nd</th>
                                    </tr>
                                </thead>

                                <tbody id="cashierHistoryBody">
                                    @foreach ($cashierHistory as $index => $history)
                                        <tr
                                            data-history-row
                                            data-date="{{ $history['date'] }}"
                                            data-shift="{{ $history['shift'] }}"
                                            data-status="{{ $history['status'] }}"
                                            data-secondary="{{ $history['secondary'] }}"
                                            data-open-time="{{ $history['open_time'] }}"
                                            class="cursor-pointer border-b border-slate-200 transition hover:bg-sky-50 {{ $index === 0 ? 'bg-[#f6d79c]' : 'bg-white' }}"
                                        >
                                            <td class="border-r border-slate-200 px-1 py-2 text-center">
                                                <span data-row-indicator class="{{ $index === 0 ? '' : 'invisible' }}">
                                                    ◆
                                                </span>
                                            </td>

                                            <td class="border-r border-slate-200 px-2 py-2">
                                                {{ $history['date'] }}
                                            </td>

                                            <td class="border-r border-slate-200 px-2 py-2 text-center">
                                                {{ $history['shift'] }}
                                            </td>

                                            <td class="border-r border-slate-200 px-2 py-2 text-center">
                                                {{ $history['status'] }}
                                            </td>

                                            <td class="px-2 py-2 text-center">
                                                {{ $history['secondary'] !== '-' ? '✓' : '' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </section>

                    {{-- Form Open Cashier --}}
                    <section class="border border-slate-300 bg-[#fbfdfe] p-4">
                        <div class="grid max-w-xl gap-x-4 gap-y-4 sm:grid-cols-[105px_1fr]">

                            <label class="pt-1 text-[10px] font-semibold text-slate-600">
                                Date
                            </label>

                            <div class="flex items-center gap-2">
                                <input
                                    id="cashierDate"
                                    type="date"
                                    value="2026-06-15"
                                    class="h-7 w-36 border border-slate-300 bg-white px-2 text-[10px] text-slate-600 outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-100"
                                >

                                <label class="ml-2 text-[10px] font-semibold text-slate-600">
                                    Shift
                                </label>

                                <select
                                    id="cashierShift"
                                    class="h-7 w-16 border border-slate-300 bg-white px-2 text-[10px] text-slate-600 outline-none focus:border-sky-500"
                                >
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                </select>
                            </div>

                            <label class="pt-1 text-[10px] font-semibold text-slate-600">
                                Password
                            </label>

                            <div class="relative max-w-[220px]">
                                <input
                                    id="cashierPassword"
                                    type="password"
                                    placeholder="Masukkan password"
                                    class="h-7 w-full border border-slate-300 bg-white px-2 pr-8 text-[10px] text-slate-600 outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-100"
                                >

                                <button
                                    type="button"
                                    id="togglePassword"
                                    class="absolute inset-y-0 right-0 grid w-7 place-items-center text-slate-400 hover:text-sky-600"
                                >
                                    <i data-lucide="eye" class="h-3.5 w-3.5"></i>
                                </button>
                            </div>

                            <div></div>

                            <label class="flex items-center gap-2 text-[10px] font-semibold text-slate-600">
                                <input
                                    id="secondaryCashierToggle"
                                    type="checkbox"
                                    class="h-3.5 w-3.5 rounded border-slate-300 text-sky-600 focus:ring-sky-500"
                                >
                                Secondary Cashier
                            </label>

                            <div></div>

                            <div
                                id="secondaryCashierFields"
                                class="space-y-2 rounded-sm border border-slate-300 bg-[#f3f7f8] p-3 opacity-50"
                            >
                                <div class="grid gap-2 sm:grid-cols-[110px_1fr]">
                                    <label class="pt-1 text-[10px] text-slate-500">
                                        Primary Cashier
                                    </label>

                                    <input
                                        id="primaryCashier"
                                        type="text"
                                        disabled
                                        class="h-6 border border-slate-300 bg-slate-200 px-2 text-[10px] outline-none"
                                    >
                                </div>

                                <div class="grid gap-2 sm:grid-cols-[110px_1fr]">
                                    <label class="pt-1 text-[10px] text-slate-500">
                                        Password
                                    </label>

                                    <input
                                        id="primaryPassword"
                                        type="password"
                                        disabled
                                        class="h-6 border border-slate-300 bg-slate-200 px-2 text-[10px] outline-none"
                                    >
                                </div>
                            </div>

                            <label class="pt-1 text-[10px] font-semibold text-slate-600">
                                Beg. Amount
                            </label>

                            <div class="flex max-w-sm gap-1">
                                <select
                                    id="currency"
                                    class="h-7 w-20 border border-slate-300 bg-white px-2 text-[10px] text-slate-600 outline-none"
                                >
                                    <option>IDR</option>
                                    <option>USD</option>
                                </select>

                                <input
                                    id="beginningAmount"
                                    type="number"
                                    min="0"
                                    value="0"
                                    class="h-7 flex-1 border border-slate-300 bg-white px-2 text-[10px] text-slate-600 outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-100"
                                >

                                <span class="pt-1 text-[10px] text-slate-500">
                                    (for Primary Cashier)
                                </span>
                            </div>

                            <div class="sm:col-span-2 mt-6 border-t border-slate-200 pt-4">
                                <div class="grid max-w-sm grid-cols-[105px_1fr] gap-y-2 text-[10px]">
                                    <span class="font-semibold text-slate-600">Status</span>
                                    <input
                                        id="cashierStatus"
                                        value="OPEN"
                                        readonly
                                        class="h-6 border border-slate-300 bg-slate-100 px-2 text-[10px] text-slate-600 outline-none"
                                    >

                                    <span class="font-semibold text-slate-600">Actual Open</span>
                                    <input
                                        id="actualOpen"
                                        value="15-Jun-2026 11:41"
                                        readonly
                                        class="h-6 border border-slate-300 bg-slate-100 px-2 text-[10px] text-slate-600 outline-none"
                                    >

                                    <span class="font-semibold text-slate-600">Actual Close</span>
                                    <input
                                        id="actualClose"
                                        value=""
                                        readonly
                                        class="h-6 border border-slate-300 bg-slate-100 px-2 text-[10px] text-slate-600 outline-none"
                                    >
                                </div>
                            </div>
                        </div>

                        {{-- Action --}}
                        <div class="mt-8 flex flex-wrap items-center gap-2 border-t border-slate-200 pt-3">
                            <button
                                type="button"
                                id="openCashierButton"
                                class="min-w-[66px] border border-[#d8be75] bg-[#fff0bf] px-3 py-1.5 text-[10px] font-bold text-[#7c6a31] shadow-sm transition hover:bg-[#ffe79b]"
                            >
                                Open
                            </button>

                            <button
                                type="button"
                                id="closeCashierButton"
                                class="min-w-[66px] border border-slate-300 bg-white px-3 py-1.5 text-[10px] font-bold text-slate-500 shadow-sm transition hover:bg-slate-100"
                            >
                                Close
                            </button>

                            <button
                                type="button"
                                id="cancelCloseButton"
                                class="min-w-[88px] border border-slate-300 bg-slate-100 px-3 py-1.5 text-[10px] font-bold text-slate-400 shadow-sm"
                            >
                                Cancel Close
                            </button>

                            <div class="ml-auto flex gap-2">
                                <button
                                    type="button"
                                    id="saveCashierButton"
                                    class="min-w-[66px] border border-[#8baeba] bg-[#e7f6fa] px-3 py-1.5 text-[10px] font-bold text-[#43717f] shadow-sm transition hover:bg-[#d3edf4]"
                                >
                                    Save
                                </button>

                                <button
                                    type="button"
                                    id="cancelCashierButton"
                                    class="min-w-[66px] border border-slate-300 bg-white px-3 py-1.5 text-[10px] font-bold text-slate-600 shadow-sm transition hover:bg-slate-100"
                                >
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </section>
    </main>
</div>

<script>
    lucide.createIcons({
        attrs: {
            'stroke-width': 1.8
        }
    });

    const rows = [...document.querySelectorAll('[data-history-row]')];
    const secondaryToggle = document.getElementById('secondaryCashierToggle');
    const secondaryFields = document.getElementById('secondaryCashierFields');
    const primaryCashier = document.getElementById('primaryCashier');
    const primaryPassword = document.getElementById('primaryPassword');
    const passwordInput = document.getElementById('cashierPassword');
    const togglePassword = document.getElementById('togglePassword');

    function selectHistoryRow(row) {
        rows.forEach((item) => {
            item.classList.remove('bg-[#f6d79c]');
            item.classList.add('bg-white');

            item.querySelector('[data-row-indicator]')
                ?.classList.add('invisible');
        });

        row.classList.remove('bg-white');
        row.classList.add('bg-[#f6d79c]');

        row.querySelector('[data-row-indicator]')
            ?.classList.remove('invisible');

        document.getElementById('cashierDate').value = row.dataset.date
            .split('-')
            .reverse()
            .join('-');

        document.getElementById('cashierShift').value = row.dataset.shift;
        document.getElementById('cashierStatus').value = row.dataset.status;
        document.getElementById('actualOpen').value =
            `${row.dataset.date} ${row.dataset.openTime}`;
    }

    rows.forEach((row) => {
        row.addEventListener('click', () => selectHistoryRow(row));
    });

    secondaryToggle.addEventListener('change', () => {
        const enabled = secondaryToggle.checked;

        primaryCashier.disabled = !enabled;
        primaryPassword.disabled = !enabled;

        secondaryFields.classList.toggle('opacity-50', !enabled);
        secondaryFields.classList.toggle('bg-[#f3f7f8]', !enabled);
        secondaryFields.classList.toggle('bg-white', enabled);

        if (!enabled) {
            primaryCashier.value = '';
            primaryPassword.value = '';
        }
    });

    togglePassword.addEventListener('click', () => {
        const hidden = passwordInput.type === 'password';

        passwordInput.type = hidden ? 'text' : 'password';
        togglePassword.innerHTML = hidden
            ? '<i data-lucide="eye-off" class="h-3.5 w-3.5"></i>'
            : '<i data-lucide="eye" class="h-3.5 w-3.5"></i>';

        lucide.createIcons({
            attrs: {
                'stroke-width': 1.8
            }
        });
    });

    document.getElementById('openCashierButton').addEventListener('click', () => {
        document.getElementById('cashierStatus').value = 'OPEN';

        const now = new Date();
        document.getElementById('actualOpen').value =
            now.toLocaleDateString('en-GB') + ' ' +
            now.toLocaleTimeString('en-GB', {
                hour: '2-digit',
                minute: '2-digit'
            });

        alert('Cashier berhasil dibuka.');
    });

    document.getElementById('closeCashierButton').addEventListener('click', () => {
        document.getElementById('cashierStatus').value = 'CLOSED';

        const now = new Date();
        document.getElementById('actualClose').value =
            now.toLocaleDateString('en-GB') + ' ' +
            now.toLocaleTimeString('en-GB', {
                hour: '2-digit',
                minute: '2-digit'
            });

        alert('Cashier berhasil ditutup.');
    });

    document.getElementById('cancelCloseButton').addEventListener('click', () => {
        document.getElementById('cashierStatus').value = 'OPEN';
        document.getElementById('actualClose').value = '';
    });

    document.getElementById('saveCashierButton').addEventListener('click', () => {
        alert('Data cashier berhasil disimpan.');
    });

    document.getElementById('cancelCashierButton').addEventListener('click', () => {
        document.getElementById('cashierPassword').value = '';
        document.getElementById('beginningAmount').value = 0;
        secondaryToggle.checked = false;
        secondaryToggle.dispatchEvent(new Event('change'));
    });
</script>

</body>
</html>
