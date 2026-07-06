@php
    $cashierLoginErrors = $errors->getBag('cashierLogin');
    $cashierHistoryCollection = collect($cashierHistory ?? []);
    $sidebarItems = collect($sidebar ?? [])
        ->map(function ($item) {
            $href = data_get($item, 'href');
            $active = (bool) data_get($item, 'active', false);

            if ($href === route('cashier.dashboard') || $href === '/dashboard') {
                $active = true;
            }

            return [
                'label' => data_get($item, 'label', 'Menu'),
                'icon' => data_get($item, 'icon', 'circle'),
                'href' => $href,
                'active' => $active,
            ];
        })
        ->all();

    $selectedHistoryKey = data_get($cashierSession ?? [], 'logged_in_at');
    $selectedHistory = $selectedHistoryKey
        ? $cashierHistoryCollection->firstWhere('logged_in_at', $selectedHistoryKey) ??
            $cashierHistoryCollection->first()
        : $cashierHistoryCollection->first();
    $selectedHistory = $selectedHistory ?: [];

    $secondaryRaw = old(
        'secondary_cashier',
        data_get($cashierSession ?? [], 'secondary_cashier', data_get($selectedHistory, 'secondary_active', false)),
    );
    $secondaryEnabled = in_array($secondaryRaw, [true, 1, '1', 'true', 'on'], true);

    $resolvedSystemUser = old(
        'cashier',
        data_get(
            $cashierSession ?? [],
            'system_user',
            data_get($selectedHistory, 'user', data_get($systemLogin ?? [], 'user_id', '')),
        ),
    );
    $resolvedCashierName = old(
        'cashier_display_name',
        data_get(
            $cashierSession ?? [],
            'cashier_name',
            data_get(
                $cashierSession ?? [],
                'display_name',
                data_get($selectedHistory, 'user_name', data_get($systemLogin ?? [], 'display_name', '')),
            ),
        ),
    );
    $resolvedBusinessDate = old(
        'business_date',
        data_get(
            $cashierSession ?? [],
            'business_date',
            data_get($selectedHistory, 'date_value', now()->toDateString()),
        ),
    );
    $resolvedShift = (string) old(
        'shift',
        data_get($cashierSession ?? [], 'shift', data_get($selectedHistory, 'shift', $shifts[0] ?? '1')),
    );
    $resolvedCurrency = old(
        'currency',
        data_get($cashierSession ?? [], 'currency', data_get($selectedHistory, 'currency', 'IDR')),
    );
    $resolvedOpeningBalance = old(
        'opening_balance',
        data_get($cashierSession ?? [], 'opening_balance', data_get($selectedHistory, 'opening_balance', '0')),
    );
    $resolvedStatus = old(
        'status',
        data_get($cashierSession ?? [], 'status', data_get($selectedHistory, 'status', 'OPEN')),
    );
    $resolvedPrimaryCashier = old(
        'primary_cashier_name',
        data_get($cashierSession ?? [], 'primary_cashier_name', data_get($selectedHistory, 'primary_cashier_name', '')),
    );
    $resolvedActualOpen = data_get($selectedHistory, 'actual_open', '');
    $resolvedActualClose = data_get($selectedHistory, 'actual_close', '');

    try {
        if (filled(data_get($cashierSession ?? [], 'actual_opened_at'))) {
            $resolvedActualOpen = \Carbon\Carbon::parse(data_get($cashierSession, 'actual_opened_at'))->format(
                'd-M-Y H:i',
            );
        }
    } catch (\Throwable) {
    }

    try {
        if (filled(data_get($cashierSession ?? [], 'actual_closed_at'))) {
            $resolvedActualClose = \Carbon\Carbon::parse(data_get($cashierSession, 'actual_closed_at'))->format(
                'd-M-Y H:i',
            );
        }
    } catch (\Throwable) {
    }
@endphp

<x-layouts.app title="Open Cashier" body-class="min-h-screen bg-[#dce6e9] font-sans text-slate-700">
    <!-- Cashier Session Setup -->
<x-ui.legacy-style-kit />
    <x-layouts.legacy-page :sidebar="$sidebar" hotel-name="Ibis Makassar City Center" system-name="Front Office System"
        :username="session('cashier_login.display_name', session('system_login.display_name', 'ADHA'))" theme="dark">

       <main class="">


                <div class="border-b border-slate-300 bg-[#eaf1f3] px-4 py-2">
                    <div class="flex items-center gap-1 text-[10px]">
                        <span
                            class="rounded-t border border-slate-300 border-b-white bg-white px-4 py-1.5 font-bold text-[#1f6d96] shadow-sm">
                            Open Cashier
                        </span>
                    </div>
                </div>

                <section class="">
                    <div
                        class="min-h-[calc(100vh-118px)] rounded-sm border border-slate-300 bg-[#f8fbfc] p-3 shadow-sm">
                        @if (session('cashierSessionMessage'))
                            <div
                                class="mb-3 rounded-sm border border-emerald-200 bg-emerald-50 px-3 py-2 text-[10px] font-semibold text-emerald-700">
                                {{ session('cashierSessionMessage') }}
                            </div>
                        @endif

                        @if ($cashierLoginErrors->any())
                            <div
                                class="mb-3 rounded-sm border border-rose-200 bg-rose-50 px-3 py-2 text-[10px] text-rose-700">
                                <ul class="list-disc space-y-1 pl-4">
                                    @foreach ($cashierLoginErrors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('cashier.login.store') }}">
                            @csrf

                            <div
                                class="mb-3 flex max-w-[390px] items-center gap-2 rounded-sm border border-slate-300 bg-[#eef4f5] px-3 py-2">
                                <span class="w-20 text-[10px] font-semibold text-slate-600">User</span>

                                <input id="cashierCode" name="cashier" type="text" value="{{ $resolvedSystemUser }}"
                                    readonly
                                    class="h-6 w-28 border border-slate-300 bg-slate-100 px-2 text-[10px] text-slate-600 outline-none">

                                <input id="cashierName" type="text" value="{{ $resolvedCashierName }}" readonly
                                    class="h-6 flex-1 border border-slate-300 bg-slate-100 px-2 text-[10px] text-slate-600 outline-none">
                            </div>

                            <div class="grid max-w-5xl grid-cols-1 gap-3 xl:grid-cols-[280px_minmax(430px,1fr)]">
                                <section class="overflow-hidden border border-slate-300 bg-white">
                                    <div class="max-h-[470px] overflow-auto">
                                        <table class="w-full border-collapse text-left text-[10px]">
                                            <thead class="sticky top-0 bg-[#dfecef] text-[#5d737b]">
                                                <tr class="border-b border-slate-300">
                                                    <th class="w-6 border-r border-slate-300 px-1 py-2"></th>
                                                    <th class="border-r border-slate-300 px-2 py-2 font-bold">Date</th>
                                                    <th
                                                        class="w-10 border-r border-slate-300 px-2 py-2 text-center font-bold">
                                                        Shift</th>
                                                    <th
                                                        class="w-16 border-r border-slate-300 px-2 py-2 text-center font-bold">
                                                        Status</th>
                                                    <th class="w-12 px-2 py-2 text-center font-bold">2nd</th>
                                                </tr>
                                            </thead>

                                            <tbody id="cashierHistoryBody">
                                                @forelse ($cashierHistoryCollection as $history)
                                                    @php
                                                        $isSelected =
                                                            $history['logged_in_at'] ===
                                                            ($selectedHistory['logged_in_at'] ?? null);
                                                    @endphp
                                                    <tr data-history-row
                                                        data-logged-in-at="{{ $history['logged_in_at'] }}"
                                                        data-date="{{ $history['date'] }}"
                                                        data-date-value="{{ $history['date_value'] }}"
                                                        data-shift="{{ $history['shift'] }}"
                                                        data-status="{{ $history['status'] }}"
                                                        data-secondary="{{ $history['secondary'] }}"
                                                        data-secondary-active="{{ $history['secondary_active'] ? '1' : '0' }}"
                                                        data-user="{{ $history['user'] }}"
                                                        data-user-name="{{ $history['user_name'] }}"
                                                        data-primary-cashier="{{ $history['primary_cashier_name'] }}"
                                                        data-currency="{{ $history['currency'] }}"
                                                        data-opening-balance="{{ $history['opening_balance'] }}"
                                                        data-open-time="{{ $history['open_time'] }}"
                                                        data-actual-open="{{ $history['actual_open'] }}"
                                                        data-actual-close="{{ $history['actual_close'] }}"
                                                        class="cursor-pointer border-b border-slate-200 transition hover:bg-sky-50 {{ $isSelected ? 'bg-[#f6d79c]' : 'bg-white' }}">
                                                        <td class="border-r border-slate-200 px-1 py-2 text-center">
                                                            <span data-row-indicator
                                                                class="{{ $isSelected ? '' : 'invisible' }}">
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
                                                @empty
                                                    <tr class="border-b border-slate-200 bg-white">
                                                        <td colspan="5"
                                                            class="px-3 py-6 text-center text-[10px] text-slate-400">
                                                            Belum ada riwayat cashier.
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </section>

                                <section class="border border-slate-300 bg-[#fbfdfe] p-4">
                                    <div class="grid max-w-xl gap-x-4 gap-y-4 sm:grid-cols-[105px_1fr]">
                                        <label class="pt-1 text-[10px] font-semibold text-slate-600">
                                            Date
                                        </label>

                                        <div class="flex items-center gap-2">
                                            <input id="cashierDate" name="business_date" type="date"
                                                value="{{ $resolvedBusinessDate }}"
                                                class="h-7 w-36 border border-slate-300 bg-white px-2 text-[10px] text-slate-600 outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-100">

                                            <label class="ml-2 text-[10px] font-semibold text-slate-600">
                                                Shift
                                            </label>

                                            <select id="cashierShift" name="shift"
                                                class="h-7 w-16 border border-slate-300 bg-white px-2 text-[10px] text-slate-600 outline-none focus:border-sky-500">
                                                @foreach ($shifts as $shift)
                                                    <option value="{{ $shift }}" @selected($resolvedShift === (string) $shift)>
                                                        {{ $shift }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <label class="pt-1 text-[10px] font-semibold text-slate-600">
                                            Password
                                        </label>

                                        <div class="relative max-w-[220px]">
                                            <input id="cashierPassword" name="password" type="password"
                                                placeholder="Masukkan password"
                                                class="h-7 w-full border border-slate-300 bg-white px-2 pr-8 text-[10px] text-slate-600 outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-100">

                                            <button type="button" id="togglePassword"
                                                class="absolute inset-y-0 right-0 grid w-7 place-items-center text-slate-400 hover:text-sky-600">
                                                <i data-lucide="eye" class="h-3.5 w-3.5"></i>
                                            </button>
                                        </div>

                                        <label
                                            class="flex items-center gap-2 text-[10px] font-semibold text-slate-600">
                                            <input id="secondaryCashierToggle" name="secondary_cashier"
                                                type="checkbox" value="1" @checked($secondaryEnabled)
                                                class="h-3.5 w-3.5 rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                                            Secondary Cashier
                                        </label>

                                        <div></div>

                                        <div id="secondaryCashierFields"
                                            class="space-y-2 rounded-sm border border-slate-300 bg-[#f3f7f8] p-3 {{ $secondaryEnabled ? 'bg-white' : 'opacity-50' }}">
                                            <div class="grid gap-2 sm:grid-cols-[110px_1fr]">
                                                <label class="pt-1 text-[10px] text-slate-500">
                                                    Primary Cashier
                                                </label>

                                                <input id="primaryCashier" name="primary_cashier_name" type="text"
                                                    value="{{ $resolvedPrimaryCashier }}" @disabled(!$secondaryEnabled)
                                                    class="h-6 border border-slate-300 bg-slate-200 px-2 text-[10px] outline-none">
                                            </div>

                                            <div class="grid gap-2 sm:grid-cols-[110px_1fr]">
                                                <label class="pt-1 text-[10px] text-slate-500">
                                                    Password
                                                </label>

                                                <input id="primaryPassword" name="primary_password" type="password"
                                                    @disabled(!$secondaryEnabled)
                                                    class="h-6 border border-slate-300 bg-slate-200 px-2 text-[10px] outline-none">
                                            </div>
                                        </div>

                                        <label class="pt-1 text-[10px] font-semibold text-slate-600">
                                            Beg. Amount
                                        </label>

                                        <div class="flex max-w-sm gap-1">
                                            <select id="currency" name="currency"
                                                class="h-7 w-20 border border-slate-300 bg-white px-2 text-[10px] text-slate-600 outline-none">
                                                <option value="IDR" @selected($resolvedCurrency === 'IDR')>IDR</option>
                                                <option value="USD" @selected($resolvedCurrency === 'USD')>USD</option>
                                            </select>

                                            <input id="beginningAmount" name="opening_balance" type="number"
                                                min="0" step="0.01" value="{{ $resolvedOpeningBalance }}"
                                                class="h-7 flex-1 border border-slate-300 bg-white px-2 text-[10px] text-slate-600 outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-100">

                                            <span class="pt-1 text-[10px] text-slate-500">
                                                (for Primary Cashier)
                                            </span>
                                        </div>

                                        <div class="sm:col-span-2 mt-6 border-t border-slate-200 pt-4">
                                            <div class="grid max-w-sm grid-cols-[105px_1fr] gap-y-2 text-[10px]">
                                                <span class="font-semibold text-slate-600">Status</span>
                                                <input id="cashierStatus" name="status"
                                                    value="{{ $resolvedStatus }}" readonly
                                                    class="h-6 border border-slate-300 bg-slate-100 px-2 text-[10px] text-slate-600 outline-none">

                                                <span class="font-semibold text-slate-600">Actual Open</span>
                                                <input id="actualOpen" value="{{ $resolvedActualOpen }}" readonly
                                                    class="h-6 border border-slate-300 bg-slate-100 px-2 text-[10px] text-slate-600 outline-none">

                                                <span class="font-semibold text-slate-600">Actual Close</span>
                                                <input id="actualClose" value="{{ $resolvedActualClose }}" readonly
                                                    class="h-6 border border-slate-300 bg-slate-100 px-2 text-[10px] text-slate-600 outline-none">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-8 flex flex-wrap items-center gap-2 border-t border-slate-200 pt-3">
                                        <button type="submit" name="action" value="open" id="openCashierButton"
                                            class="min-w-[66px] border border-[#d8be75] bg-[#fff0bf] px-3 py-1.5 text-[10px] font-bold text-[#7c6a31] shadow-sm transition hover:bg-[#ffe79b]">
                                            Open
                                        </button>

                                        <button type="submit" name="action" value="close" id="closeCashierButton"
                                            class="min-w-[66px] border border-slate-300 bg-white px-3 py-1.5 text-[10px] font-bold text-slate-500 shadow-sm transition hover:bg-slate-100">
                                            Close
                                        </button>

                                        <button type="button" id="cancelCloseButton"
                                            class="min-w-[88px] border border-slate-300 bg-slate-100 px-3 py-1.5 text-[10px] font-bold text-slate-400 shadow-sm">
                                            Cancel Close
                                        </button>

                                        <div class="ml-auto flex gap-2">
                                            <button type="submit" name="action" value="save"
                                                id="saveCashierButton"
                                                class="min-w-[66px] border border-[#8baeba] bg-[#e7f6fa] px-3 py-1.5 text-[10px] font-bold text-[#43717f] shadow-sm transition hover:bg-[#d3edf4]">
                                                Save
                                            </button>

                                            <button type="button" id="cancelCashierButton"
                                                class="min-w-[66px] border border-slate-300 bg-white px-3 py-1.5 text-[10px] font-bold text-slate-600 shadow-sm transition hover:bg-slate-100">
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </form>
                    </div>
                </section>
            </main>

    </x-layouts.legacy-page>



    @push('scripts')
        <script>
            (() => {
                window.lucide?.createIcons({
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
                const cashierCode = document.getElementById('cashierCode');
                const cashierName = document.getElementById('cashierName');
                const cashierDate = document.getElementById('cashierDate');
                const cashierShift = document.getElementById('cashierShift');
                const cashierStatus = document.getElementById('cashierStatus');
                const currency = document.getElementById('currency');
                const beginningAmount = document.getElementById('beginningAmount');
                const actualOpen = document.getElementById('actualOpen');
                const actualClose = document.getElementById('actualClose');
                const openCashierButton = document.getElementById('openCashierButton');
                const closeCashierButton = document.getElementById('closeCashierButton');
                const cancelCloseButton = document.getElementById('cancelCloseButton');
                const saveCashierButton = document.getElementById('saveCashierButton');
                const cancelCashierButton = document.getElementById('cancelCashierButton');
                const initialState = {
                    cashierCode: cashierCode?.value ?? '',
                    cashierName: cashierName?.value ?? '',
                    date: cashierDate?.value ?? '',
                    shift: cashierShift?.value ?? '',
                    status: cashierStatus?.value ?? 'OPEN',
                    currency: currency?.value ?? 'IDR',
                    beginningAmount: beginningAmount?.value ?? '0',
                    actualOpen: actualOpen?.value ?? '',
                    actualClose: actualClose?.value ?? '',
                    secondaryEnabled: secondaryToggle?.checked ?? false,
                    primaryCashier: primaryCashier?.value ?? '',
                };

                const formatDateTime = (value = new Date()) => {
                    const formatter = new Intl.DateTimeFormat('en-GB', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: false,
                    });

                    return formatter.format(value).replace(',', '');
                };

                const syncSecondaryState = (enabled, clearWhenDisabled = false) => {
                    if (!secondaryFields || !primaryCashier || !primaryPassword) {
                        return;
                    }

                    primaryCashier.disabled = !enabled;
                    primaryPassword.disabled = !enabled;
                    primaryCashier.required = enabled;
                    primaryPassword.required = enabled;
                    primaryCashier.classList.toggle('bg-slate-200', !enabled);
                    primaryCashier.classList.toggle('bg-white', enabled);
                    primaryPassword.classList.toggle('bg-slate-200', !enabled);
                    primaryPassword.classList.toggle('bg-white', enabled);
                    secondaryFields.classList.toggle('opacity-50', !enabled);
                    secondaryFields.classList.toggle('bg-[#f3f7f8]', !enabled);
                    secondaryFields.classList.toggle('bg-white', enabled);

                    if (!enabled && clearWhenDisabled) {
                        primaryCashier.value = '';
                        primaryPassword.value = '';
                    }
                };

                const selectHistoryRow = (row) => {
                    rows.forEach((item) => {
                        item.classList.remove('bg-[#f6d79c]');
                        item.classList.add('bg-white');
                        item.querySelector('[data-row-indicator]')?.classList.add('invisible');
                    });

                    row.classList.remove('bg-white');
                    row.classList.add('bg-[#f6d79c]');
                    row.querySelector('[data-row-indicator]')?.classList.remove('invisible');

                    if (cashierCode) cashierCode.value = row.dataset.user || cashierCode.value;
                    if (cashierName) cashierName.value = row.dataset.userName || cashierName.value;
                    if (cashierDate) cashierDate.value = row.dataset.dateValue || cashierDate.value;
                    if (cashierShift) cashierShift.value = row.dataset.shift || cashierShift.value;
                    if (cashierStatus) cashierStatus.value = row.dataset.status || 'OPEN';
                    if (currency) currency.value = row.dataset.currency || 'IDR';
                    if (beginningAmount) beginningAmount.value = row.dataset.openingBalance || '0';
                    if (actualOpen) actualOpen.value = row.dataset.actualOpen ||
                        `${row.dataset.date || ''} ${row.dataset.openTime || ''}`.trim();
                    if (actualClose) actualClose.value = row.dataset.actualClose || '';
                    if (primaryCashier) primaryCashier.value = row.dataset.primaryCashier || '';

                    if (secondaryToggle) {
                        secondaryToggle.checked = row.dataset.secondaryActive === '1';
                        syncSecondaryState(secondaryToggle.checked);
                    }
                };

                rows.forEach((row) => {
                    row.addEventListener('click', () => selectHistoryRow(row));
                });

                secondaryToggle?.addEventListener('change', () => {
                    syncSecondaryState(secondaryToggle.checked, !secondaryToggle.checked);
                });

                togglePassword?.addEventListener('click', () => {
                    if (!passwordInput) {
                        return;
                    }

                    const hidden = passwordInput.type === 'password';
                    passwordInput.type = hidden ? 'text' : 'password';
                    togglePassword.innerHTML = hidden ?
                        '<i data-lucide="eye-off" class="h-3.5 w-3.5"></i>' :
                        '<i data-lucide="eye" class="h-3.5 w-3.5"></i>';

                    window.lucide?.createIcons({
                        attrs: {
                            'stroke-width': 1.8
                        }
                    });
                });

                openCashierButton?.addEventListener('click', () => {
                    if (cashierStatus) cashierStatus.value = 'OPEN';
                    if (actualClose) actualClose.value = '';
                    if (actualOpen && !actualOpen.value) actualOpen.value = formatDateTime();
                });

                closeCashierButton?.addEventListener('click', () => {
                    if (cashierStatus) cashierStatus.value = 'CLOSED';
                    if (actualClose) actualClose.value = formatDateTime();
                });

                cancelCloseButton?.addEventListener('click', () => {
                    if (cashierStatus) cashierStatus.value = 'OPEN';
                    if (actualClose) actualClose.value = '';
                });

                saveCashierButton?.addEventListener('click', () => {
                    if (cashierStatus && !cashierStatus.value) cashierStatus.value = 'OPEN';
                });

                cancelCashierButton?.addEventListener('click', () => {
                    if (passwordInput) passwordInput.value = '';
                    if (primaryPassword) primaryPassword.value = '';
                    if (cashierCode) cashierCode.value = initialState.cashierCode;
                    if (cashierName) cashierName.value = initialState.cashierName;
                    if (cashierDate) cashierDate.value = initialState.date;
                    if (cashierShift) cashierShift.value = initialState.shift;
                    if (cashierStatus) cashierStatus.value = initialState.status;
                    if (currency) currency.value = initialState.currency;
                    if (beginningAmount) beginningAmount.value = initialState.beginningAmount;
                    if (actualOpen) actualOpen.value = initialState.actualOpen;
                    if (actualClose) actualClose.value = initialState.actualClose;
                    if (secondaryToggle) secondaryToggle.checked = initialState.secondaryEnabled;
                    if (primaryCashier) primaryCashier.value = initialState.primaryCashier;
                    syncSecondaryState(initialState.secondaryEnabled, !initialState.secondaryEnabled);
                });

                syncSecondaryState(secondaryToggle?.checked ?? false);
            })();
        </script>
    @endpush
</x-layouts.app>
