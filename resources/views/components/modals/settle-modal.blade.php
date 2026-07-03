@props([
    'id' => 'settleModal',
    'billNo' => '0000024593',
    'totalAmount' => 20000,
    'currency' => 'IDR',

    'creditCards' => [
        ['code' => 'CC001', 'name' => 'BCA Visa'],
        ['code' => 'CC002', 'name' => 'BCA Master'],
        ['code' => 'CC003', 'name' => 'BCA Card'],
        ['code' => 'CC004', 'name' => 'BCA Debit'],
        ['code' => 'CC005', 'name' => 'MANDIRI Visa'],
        ['code' => 'CC006', 'name' => 'MANDIRI Master'],
        ['code' => 'CC007', 'name' => 'MANDIRI Debit'],
        ['code' => 'CC008', 'name' => 'QRIS BCA'],
        ['code' => 'CC009', 'name' => 'BNI Visa'],
        ['code' => 'CC010', 'name' => 'BNI Master'],
        ['code' => 'CC011', 'name' => 'BNI Debit'],
        ['code' => 'CC012', 'name' => 'QRIS BNI'],
        ['code' => 'CC013', 'name' => 'BRI Visa'],
        ['code' => 'CC014', 'name' => 'BRI Master'],
        ['code' => 'CC015', 'name' => 'BRI Debit'],
        ['code' => 'CC016', 'name' => 'QRIS Static BCA'],
    ],
])

@php
    $generalMethods = [
        'CASH',
        'ROOM',
        'GRP MASTER',
        'PERMANENT FOLIO',
        'GOLF PERSONAL',
        'GOLF GROUP',
        'BANQUET',
        'CITY LEDGER',
        'HOUSE LEDGER',
        'DEPOSIT',
        'VOUCHER',
        'HOUSE USE',
        'COMPLIMENT',
        'ENTERTAIN',
        'PACKAGE',
    ];

    $debitCards = [
        'BCA Debit',
        'MANDIRI Debit',
        'BNI Debit',
        'BRI Debit',
        'QRIS',
        'Other',
    ];
@endphp

<style>
    #{{ $id }}::backdrop,
    #{{ $id }}CardLookup::backdrop,
    #{{ $id }}PaymentList::backdrop,
    #{{ $id }}ConfirmPrint::backdrop,
    #{{ $id }}Printing::backdrop {
        background: rgba(15, 23, 42, .48);
        backdrop-filter: blur(2px);
    }
</style>

{{-- =========================
     MODAL UTAMA SETTLE
========================= --}}
<dialog
    id="{{ $id }}"
    class="m-auto w-[calc(100%-1.5rem)] max-w-6xl overflow-hidden rounded-[2px] border border-[#8fa6ae] bg-[#edf4f6] p-0 shadow-2xl"
>
    <div class="min-w-0">

        {{-- Header --}}
        <div class="flex h-9 items-center justify-between border-b border-[#afc2c8] bg-[linear-gradient(180deg,#eaf5f8_0%,#d5e8ee_100%)] px-3">
            <div class="flex items-center gap-1.5">
                <span class="grid h-4 w-4 place-items-center rounded-[2px] border border-[#78a3b4] bg-[#d9f0f5] text-[9px] font-black text-[#326a7e]">
                    S
                </span>

                <h2 class="text-[11px] font-bold text-[#536b74]">
                    Settle
                </h2>
            </div>

            <button
                type="button"
                data-settle-close
                class="grid h-5 w-5 place-items-center rounded-[2px] text-[#6b7f87] transition hover:bg-[#c9e0e7] hover:text-[#244e5f]"
            >
                ✕
            </button>
        </div>

        <div class="grid grid-cols-1 gap-4 p-4 lg:grid-cols-[0.93fr_1.07fr]">

            {{-- =========================
                 BAGIAN KIRI
            ========================== --}}
            <section class="flex min-h-[430px] flex-col justify-between gap-5">

                {{-- Bill Info --}}
                <div class="rounded-[2px] border border-[#b7c9cf] bg-[#f8fbfc] p-4">
                    <h3 class="mb-4 text-[11px] font-bold uppercase tracking-wide text-[#5e737b]">
                        Bill Info
                    </h3>

                    <div class="grid grid-cols-[130px_1fr] gap-x-3 gap-y-2.5">
                        <label class="text-[10px] font-bold uppercase text-[#687e86]">
                            Bill #
                        </label>

                        <input
                            id="{{ $id }}BillNo"
                            type="text"
                            value="{{ $billNo }}"
                            readonly
                            class="h-8 rounded-[2px] border border-[#c4d3d7] bg-[#dfe7e9] px-2 text-[11px] font-semibold text-[#64767d] outline-none"
                        >

                        <label class="text-[10px] font-bold uppercase text-[#687e86]">
                            Total Amount
                        </label>

                        <input
                            id="{{ $id }}TotalAmount"
                            type="text"
                            readonly
                            class="h-8 rounded-[2px] border border-[#c4d3d7] bg-[#dfe7e9] px-2 text-[11px] font-bold text-[#526b74] outline-none"
                        >

                        <label class="text-[10px] font-bold uppercase text-[#687e86]">
                            Remaining
                        </label>

                        <input
                            id="{{ $id }}Remaining"
                            type="text"
                            readonly
                            class="h-8 rounded-[2px] border border-[#c4d3d7] bg-[#dfe7e9] px-2 text-[11px] font-bold text-[#526b74] outline-none"
                        >
                    </div>
                </div>

                {{-- Panel Payment --}}
                <div class="rounded-[2px] border border-[#9fb6bd] bg-[#f8fbfc] p-4">
                    <h3 class="mb-4 text-[11px] font-bold uppercase tracking-wide text-[#5e737b]">
                        Payment
                    </h3>

                    <div class="grid grid-cols-[130px_1fr] items-center gap-x-3 gap-y-3">
                        <label class="text-[10px] font-bold uppercase text-[#687e86]">
                            Payment
                        </label>

                        <div class="flex gap-1">
                            <select
                                id="{{ $id }}Currency"
                                class="h-8 w-20 rounded-[2px] border border-[#bdcdd2] bg-white px-2 text-[10px] font-semibold text-[#5c7179] outline-none focus:border-[#67aec9]"
                            >
                                <option value="{{ $currency }}">{{ $currency }}</option>
                            </select>

                            <input
                                id="{{ $id }}PaymentAmount"
                                type="text"
                                inputmode="decimal"
                                value="0.00"
                                class="h-8 min-w-0 flex-1 rounded-[2px] border border-[#92b9c6] bg-white px-2 text-[11px] font-bold text-[#247292] outline-none focus:ring-2 focus:ring-sky-200"
                            >
                        </div>

                        <label class="text-[10px] font-bold uppercase text-[#687e86]">
                            Payment Type
                        </label>

                        <input
                            id="{{ $id }}PaymentType"
                            type="text"
                            value="CASH"
                            readonly
                            class="h-8 rounded-[2px] border border-[#c4d3d7] bg-[#e6eef0] px-2 text-[10px] font-bold text-[#5d737b] outline-none"
                        >

                        <label class="text-[10px] font-bold uppercase text-[#687e86]">
                            Tips
                        </label>

                        <input
                            id="{{ $id }}Tips"
                            type="text"
                            inputmode="decimal"
                            value="0.00"
                            class="h-8 rounded-[2px] border border-[#bdcdd2] bg-white px-2 text-[11px] font-semibold text-[#526b74] outline-none focus:border-[#67aec9]"
                        >

                        <label class="text-[10px] font-bold uppercase text-[#687e86]">
                            Change
                        </label>

                        <input
                            id="{{ $id }}Change"
                            type="text"
                            value="0.00"
                            readonly
                            class="h-8 rounded-[2px] border border-[#c4d3d7] bg-[#e6eef0] px-2 text-[11px] font-bold text-[#526b74] outline-none"
                        >
                    </div>

                    <p
                        id="{{ $id }}Status"
                        class="mt-4 rounded-[2px] border border-[#d8e4e7] bg-[#f3f8f9] px-3 py-2 text-[10px] text-[#698087]"
                    >
                        Pilih metode pembayaran dan masukkan nominal pembayaran.
                    </p>
                </div>

                {{-- Footer kiri --}}
                <div class="flex gap-2">
                    <button
                        type="button"
                        id="{{ $id }}Save"
                        class="inline-flex h-10 min-w-[88px] items-center justify-center rounded-[2px] border border-[#d1a54d] bg-[#f7d577] px-4 text-[10px] font-bold text-[#71541d] shadow-[inset_0_1px_0_rgba(255,255,255,.8)] transition hover:bg-[#f5c95a]"
                    >
                        SAVE
                    </button>

                    <button
                        type="button"
                        data-settle-close
                        class="inline-flex h-10 min-w-[88px] items-center justify-center rounded-[2px] border border-[#b3c3c7] bg-white px-4 text-[10px] font-bold text-[#60767e] shadow-[inset_0_1px_0_rgba(255,255,255,.9)] transition hover:bg-slate-100"
                    >
                        CANCEL
                    </button>
                </div>
            </section>

            {{-- =========================
                 BAGIAN KANAN
            ========================== --}}
            <section class="space-y-3">

                {{-- Tab Payment --}}
                <div class="rounded-[2px] border border-[#aabec4] bg-[#edf4f6] p-3">
                    <div class="mb-3 flex gap-1 border-b border-[#bfd0d5]">
                        <button
                            type="button"
                            data-payment-tab="general"
                            class="payment-tab rounded-t-[2px] border border-b-0 border-[#a8bec5] bg-white px-3 py-2 text-[10px] font-bold text-[#52717c]"
                        >
                            GENERAL
                        </button>

                        <button
                            type="button"
                            data-payment-tab="credit"
                            class="payment-tab rounded-t-[2px] border border-b-0 border-transparent px-3 py-2 text-[10px] font-bold text-[#71858c] hover:bg-white"
                        >
                            CREDIT CARD
                        </button>

                        <button
                            type="button"
                            data-payment-tab="debit"
                            class="payment-tab rounded-t-[2px] border border-b-0 border-transparent px-3 py-2 text-[10px] font-bold text-[#71858c] hover:bg-white"
                        >
                            DEBIT CARD
                        </button>
                    </div>

                    {{-- General --}}
                    <div data-payment-panel="general" class="grid grid-cols-2 gap-2 sm:grid-cols-4">
                        @foreach ($generalMethods as $method)
                            <button
                                type="button"
                                data-payment-method="{{ $method }}"
                                class="payment-method-button flex min-h-[52px] items-center justify-center rounded-[2px] border border-[#b5c9cf] bg-[#e4f0f3] px-2 text-center text-[10px] font-bold leading-tight text-[#4e707c] shadow-[inset_0_1px_0_rgba(255,255,255,.9)] transition hover:border-[#67a9c3] hover:bg-[#d6ebf1] hover:text-[#236984] {{ $method === 'CASH' ? 'ring-2 ring-[#55a3c3] ring-offset-1 text-rose-700' : '' }}"
                            >
                                {{ $method }}
                            </button>
                        @endforeach
                    </div>

                    {{-- Credit Card --}}
                    <div data-payment-panel="credit" class="hidden grid-cols-2 gap-2 sm:grid-cols-4">
                        @foreach (collect($creditCards)->take(12) as $card)
                            <button
                                type="button"
                                data-card-name="{{ $card['name'] }}"
                                data-card-code="{{ $card['code'] }}"
                                class="credit-card-button flex min-h-[52px] items-center justify-center rounded-[2px] border border-[#b5c9cf] bg-[#e4f0f3] px-2 text-center text-[10px] font-bold leading-tight text-[#4e707c] shadow-[inset_0_1px_0_rgba(255,255,255,.9)] transition hover:border-[#67a9c3] hover:bg-[#d6ebf1] hover:text-[#236984]"
                            >
                                {{ $card['name'] }}
                            </button>
                        @endforeach

                        <button
                            type="button"
                            data-open-card-lookup
                            class="flex min-h-[52px] items-center justify-center rounded-[2px] border border-[#8db5c2] bg-[#d9edf3] px-2 text-center text-[10px] font-bold text-[#286b85] shadow-[inset_0_1px_0_rgba(255,255,255,.9)] transition hover:bg-[#c8e5ed]"
                        >
                            Other / Lookup
                        </button>
                    </div>

                    {{-- Debit Card --}}
                    <div data-payment-panel="debit" class="hidden grid-cols-2 gap-2 sm:grid-cols-4">
                        @foreach ($debitCards as $card)
                            <button
                                type="button"
                                data-debit-name="{{ $card }}"
                                class="debit-card-button flex min-h-[52px] items-center justify-center rounded-[2px] border border-[#b5c9cf] bg-[#e4f0f3] px-2 text-center text-[10px] font-bold leading-tight text-[#4e707c] shadow-[inset_0_1px_0_rgba(255,255,255,.9)] transition hover:border-[#67a9c3] hover:bg-[#d6ebf1] hover:text-[#236984]"
                            >
                                {{ $card }}
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Numeric Keypad --}}
                <div class="grid grid-cols-5 overflow-hidden rounded-[2px] border border-[#92abb3] bg-[#e9f2f4]">
                    @foreach ([
                        ['7', 'digit'], ['8', 'digit'], ['9', 'digit'], ['0', 'digit'], ['Clear', 'clear'],
                        ['4', 'digit'], ['5', 'digit'], ['6', 'digit'], ['00', 'digit'], ['Clear All', 'clear-all'],
                        ['1', 'digit'], ['2', 'digit'], ['3', 'digit'], ['000', 'digit'], ['.', 'decimal'],
                    ] as $key)
                        <button
                            type="button"
                            data-key-action="{{ $key[1] }}"
                            data-key-value="{{ $key[0] }}"
                            class="flex h-12 items-center justify-center border-b border-r border-[#a7bcc3] bg-[#e1eef1] text-[10px] font-bold text-[#4c6d77] transition hover:bg-[#d1e8ef] hover:text-[#216984]"
                        >
                            {{ $key[0] }}
                        </button>
                    @endforeach
                </div>

                <div class="flex justify-end">
                    <button
                        type="button"
                        id="{{ $id }}PaymentListButton"
                        class="inline-flex h-10 min-w-[88px] items-center justify-center rounded-[2px] border border-[#a9bec4] bg-[#edf5f7] px-4 text-[10px] font-bold text-[#55737e] shadow-[inset_0_1px_0_rgba(255,255,255,.9)] transition hover:bg-[#dceff4]"
                    >
                        PMT LIST
                    </button>
                </div>
            </section>
        </div>
    </div>
</dialog>

<x-modals.settlement.card-lookup-modal :id="$id" :credit-cards="$creditCards" />
<x-modals.settlement.payment-list-modal :id="$id" />
<x-modals.settlement.confirm-print-modal :id="$id" />
<x-modals.settlement.printing-modal :id="$id" />

<script>
    (() => {
        const modal = document.getElementById(@js($id));

        if (!modal || modal.dataset.ready === 'true') {
            return;
        }

        modal.dataset.ready = 'true';

        const cardLookupModal = document.getElementById(@js($id . 'CardLookup'));
        const paymentListModal = document.getElementById(@js($id . 'PaymentList'));
        const confirmPrintModal = document.getElementById(@js($id . 'ConfirmPrint'));
        const printingModal = document.getElementById(@js($id . 'Printing'));

        const totalAmount = Number(@js((float) $totalAmount));
        const currency = @js($currency);

        const totalInput = document.getElementById(@js($id . 'TotalAmount'));
        const remainingInput = document.getElementById(@js($id . 'Remaining'));
        const paymentAmountInput = document.getElementById(@js($id . 'PaymentAmount'));
        const paymentTypeInput = document.getElementById(@js($id . 'PaymentType'));
        const tipsInput = document.getElementById(@js($id . 'Tips'));
        const changeInput = document.getElementById(@js($id . 'Change'));
        const statusText = document.getElementById(@js($id . 'Status'));

        const saveButton = document.getElementById(@js($id . 'Save'));
        const paymentListButton = document.getElementById(@js($id . 'PaymentListButton'));
        const paymentListBody = document.getElementById(@js($id . 'PaymentListBody'));

        const cardLookupOk = document.getElementById(@js($id . 'CardLookupOk'));
        const confirmYes = document.getElementById(@js($id . 'ConfirmPrintYes'));
        const confirmNo = document.getElementById(@js($id . 'ConfirmPrintNo'));

        const printingStatus = document.getElementById(@js($id . 'PrintingStatus'));
        const printingCancel = document.getElementById(@js($id . 'PrintingCancel'));
        const printingCancelTop = document.getElementById(@js($id . 'PrintingCancelTop'));
        const printingLoader = document.getElementById(@js($id . 'PrintingLoader'));
        const printingSuccessIcon = document.getElementById(@js($id . 'PrintingSuccessIcon'));

        const methodButtons = [...modal.querySelectorAll('[data-payment-method]')];
        const creditButtons = [...modal.querySelectorAll('.credit-card-button')];
        const debitButtons = [...modal.querySelectorAll('.debit-card-button')];
        const tabButtons = [...modal.querySelectorAll('[data-payment-tab]')];
        const panels = [...modal.querySelectorAll('[data-payment-panel]')];
        const keypadButtons = [...modal.querySelectorAll('[data-key-action]')];

        let remaining = totalAmount;
        let selectedMethod = 'CASH';
        let selectedCard = '';
        let paymentRecords = [];
        let selectedCardRow = cardLookupModal.querySelector('[data-card-lookup-row]');
        let printingTimer = null;

        function formatMoney(value) {
            return new Intl.NumberFormat('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            }).format(Number(value || 0));
        }

        function parseMoney(value) {
            const clean = String(value || '')
                .replace(/,/g, '')
                .replace(/[^\d.]/g, '');

            const number = Number(clean);

            return Number.isFinite(number) ? number : 0;
        }

        function updateAmountInputs() {
            totalInput.value = formatMoney(totalAmount);
            remainingInput.value = formatMoney(remaining);
            changeInput.value = formatMoney(0);
        }

        function setStatus(message) {
            statusText.textContent = message;
        }

        function selectMethod(method, cardName = '') {
            selectedMethod = method;
            selectedCard = cardName;

            const label = cardName ? `${method} - ${cardName}` : method;

            paymentTypeInput.value = label;
            paymentAmountInput.value = formatMoney(remaining);

            methodButtons.forEach((button) => {
                button.classList.remove(
                    'ring-2',
                    'ring-[#55a3c3]',
                    'ring-offset-1',
                    'bg-[#d7eef4]'
                );
            });

            creditButtons.forEach((button) => {
                button.classList.remove(
                    'ring-2',
                    'ring-[#55a3c3]',
                    'ring-offset-1',
                    'bg-[#d7eef4]'
                );
            });

            debitButtons.forEach((button) => {
                button.classList.remove(
                    'ring-2',
                    'ring-[#55a3c3]',
                    'ring-offset-1',
                    'bg-[#d7eef4]'
                );
            });

            const activeMethod = methodButtons.find(
                (button) => button.dataset.paymentMethod === method
            );

            const activeCredit = creditButtons.find(
                (button) => button.dataset.cardName === cardName
            );

            const activeDebit = debitButtons.find(
                (button) => button.dataset.debitName === cardName
            );

            const activeButton = activeMethod || activeCredit || activeDebit;

            if (activeButton) {
                activeButton.classList.add(
                    'ring-2',
                    'ring-[#55a3c3]',
                    'ring-offset-1',
                    'bg-[#d7eef4]'
                );
            }

            setStatus(`Metode pembayaran aktif: ${label}.`);
        }

        function activateTab(tabName) {
            tabButtons.forEach((button) => {
                const active = button.dataset.paymentTab === tabName;

                button.classList.toggle('bg-white', active);
                button.classList.toggle('border-[#a8bec5]', active);
                button.classList.toggle('text-[#52717c]', active);

                button.classList.toggle('border-transparent', !active);
                button.classList.toggle('text-[#71858c]', !active);
            });

            panels.forEach((panel) => {
                const active = panel.dataset.paymentPanel === tabName;

                panel.classList.toggle('hidden', !active);
                panel.classList.toggle('grid', active);
            });
        }

        function renderPaymentList() {
            if (paymentRecords.length === 0) {
                paymentListBody.innerHTML = `
                    <tr>
                        <td colspan="3" class="px-3 py-10 text-center text-slate-400">
                            Belum ada pembayaran.
                        </td>
                    </tr>
                `;
                return;
            }

            paymentListBody.innerHTML = paymentRecords.map((payment, index) => `
                <tr class="border-b border-[#d7e3e6]">
                    <td class="border-r border-[#e0e9eb] px-3 py-2">${index + 1}</td>
                    <td class="border-r border-[#e0e9eb] px-3 py-2 font-medium">${payment.label}</td>
                    <td class="px-3 py-2 text-right font-bold text-[#2a6b84]">
                        ${currency} ${formatMoney(payment.amount)}
                    </td>
                </tr>
            `).join('');
        }

        function resetSettlement() {
            remaining = totalAmount;
            paymentRecords = [];
            selectedMethod = 'CASH';
            selectedCard = '';

            updateAmountInputs();
            paymentAmountInput.value = formatMoney(remaining);
            paymentTypeInput.value = 'CASH';
            tipsInput.value = '0.00';

            selectMethod('CASH');
            renderPaymentList();

            setStatus('Pembayaran dibatalkan. Data kembali ke kondisi awal.');
        }

        function closeAllChildren() {
            [cardLookupModal, paymentListModal, confirmPrintModal, printingModal].forEach((child) => {
                if (child?.open) {
                    child.close();
                }
            });
        }

        methodButtons.forEach((button) => {
            button.addEventListener('click', () => {
                selectMethod(button.dataset.paymentMethod);
            });
        });

        creditButtons.forEach((button) => {
            button.addEventListener('click', () => {
                selectMethod('CREDIT CARD', button.dataset.cardName);
            });
        });

        debitButtons.forEach((button) => {
            button.addEventListener('click', () => {
                if (button.dataset.debitName === 'Other') {
                    cardLookupModal.showModal();
                    return;
                }

                selectMethod('DEBIT CARD', button.dataset.debitName);
            });
        });

        tabButtons.forEach((button) => {
            button.addEventListener('click', () => {
                activateTab(button.dataset.paymentTab);
            });
        });

        modal.querySelector('[data-open-card-lookup]')?.addEventListener('click', () => {
            cardLookupModal.showModal();
        });

        keypadButtons.forEach((button) => {
            button.addEventListener('click', () => {
                const action = button.dataset.keyAction;
                const value = button.dataset.keyValue;

                let current = paymentAmountInput.value
                    .replace(/,/g, '')
                    .replace(/[^\d.]/g, '');

                if (action === 'clear') {
                    current = current.slice(0, -1);
                }

                if (action === 'clear-all') {
                    current = '';
                }

                if (action === 'digit') {
                    if (current === '0.00' || current === '0') {
                        current = '';
                    }

                    current += value;
                }

                if (action === 'decimal' && !current.includes('.')) {
                    current += '.';
                }

                paymentAmountInput.value = current || '0';
            });
        });

        paymentAmountInput.addEventListener('focus', () => {
            paymentAmountInput.value = paymentAmountInput.value.replace(/,/g, '');
        });

        paymentAmountInput.addEventListener('blur', () => {
            paymentAmountInput.value = formatMoney(parseMoney(paymentAmountInput.value));
        });

        tipsInput.addEventListener('focus', () => {
            tipsInput.value = tipsInput.value.replace(/,/g, '');
        });

        tipsInput.addEventListener('blur', () => {
            tipsInput.value = formatMoney(parseMoney(tipsInput.value));
        });

        saveButton.addEventListener('click', () => {
            const enteredAmount = parseMoney(paymentAmountInput.value);
            const tips = parseMoney(tipsInput.value);

            if (!selectedMethod) {
                setStatus('Pilih metode pembayaran terlebih dahulu.');
                return;
            }

            if (enteredAmount <= 0) {
                setStatus('Nominal pembayaran harus lebih dari 0.');
                return;
            }

            const remainingBeforePayment = remaining;
            const acceptedAmount = Math.min(enteredAmount, remainingBeforePayment);
            const paymentLabel = selectedCard
                ? `${selectedMethod} - ${selectedCard}`
                : selectedMethod;

            paymentRecords.push({
                label: paymentLabel,
                amount: acceptedAmount,
            });

            remaining = Math.max(0, remaining - enteredAmount);

            const change = selectedMethod === 'CASH'
                ? Math.max(0, enteredAmount - remainingBeforePayment)
                : 0;

            changeInput.value = formatMoney(change);
            remainingInput.value = formatMoney(remaining);

            renderPaymentList();

            window.dispatchEvent(new CustomEvent('settlement-updated', {
                detail: {
                    billNo: document.getElementById(@js($id . 'BillNo')).value,
                    paymentType: paymentLabel,
                    enteredAmount,
                    acceptedAmount,
                    tips,
                    remaining,
                    change,
                    payments: paymentRecords,
                }
            }));

            if (remaining > 0) {
                paymentAmountInput.value = formatMoney(remaining);

                setStatus(
                    `Pembayaran tersimpan. Sisa tagihan: ${currency} ${formatMoney(remaining)}.`
                );

                return;
            }

            paymentAmountInput.value = '0.00';

            setStatus(
                `Settlement selesai. Change: ${currency} ${formatMoney(change)}.`
            );

            window.dispatchEvent(new CustomEvent('settlement-completed', {
                detail: {
                    billNo: document.getElementById(@js($id . 'BillNo')).value,
                    totalAmount,
                    paymentType: paymentLabel,
                    tips,
                    change,
                    payments: paymentRecords,
                }
            }));

            confirmPrintModal.showModal();
        });

        paymentListButton.addEventListener('click', () => {
            renderPaymentList();
            paymentListModal.showModal();
        });

        modal.querySelectorAll('[data-settle-close]').forEach((button) => {
            button.addEventListener('click', () => {
                closeAllChildren();
                modal.close();
            });
        });

        modal.addEventListener('close', () => {
            closeAllChildren();
        });

        cardLookupModal.querySelectorAll('[data-card-lookup-close]').forEach((button) => {
            button.addEventListener('click', () => {
                cardLookupModal.close();
            });
        });

        const cardRows = () => [
            ...cardLookupModal.querySelectorAll('[data-card-lookup-row]')
        ];

        function selectCardRow(row) {
            cardRows().forEach((item) => {
                item.classList.remove('bg-[#f6d69a]');
                item.classList.add('bg-white');

                item.querySelector('[data-card-arrow]')?.classList.add('invisible');
            });

            row.classList.remove('bg-white');
            row.classList.add('bg-[#f6d69a]');
            row.querySelector('[data-card-arrow]')?.classList.remove('invisible');

            selectedCardRow = row;
        }

        cardRows().forEach((row) => {
            row.addEventListener('click', () => {
                selectCardRow(row);
            });

            row.addEventListener('dblclick', () => {
                selectCardRow(row);
                cardLookupOk.click();
            });
        });

        cardLookupOk.addEventListener('click', () => {
            if (!selectedCardRow) {
                return;
            }

            activateTab('credit');

            selectMethod(
                'CREDIT CARD',
                selectedCardRow.dataset.cardName
            );

            cardLookupModal.close();
        });

        paymentListModal.querySelectorAll('[data-payment-list-close]').forEach((button) => {
            button.addEventListener('click', () => {
                paymentListModal.close();
            });
        });

        confirmPrintModal.querySelectorAll('[data-confirm-print-close]').forEach((button) => {
            button.addEventListener('click', () => {
                confirmPrintModal.close();
            });
        });

        confirmNo.addEventListener('click', () => {
            confirmPrintModal.close();
            modal.close();
        });

        confirmYes.addEventListener('click', () => {
            confirmPrintModal.close();
            printingModal.showModal();

            setPrintingState(true);
            printingStatus.textContent = 'Sending settlement to printer...';
            printingCancel.textContent = 'Cancel';

            window.dispatchEvent(new CustomEvent('settlement-print-requested', {
                detail: {
                    billNo: document.getElementById(@js($id . 'BillNo')).value,
                    payments: paymentRecords,
                }
            }));

            printingTimer = setTimeout(() => {
                setPrintingState(false);
                printingStatus.textContent = 'Settlement successfully sent to printer.';
                printingCancel.textContent = 'OK';
            }, 1500);
        });

        function setPrintingState(isLoading) {
            printingLoader?.classList.toggle('animate-spin', isLoading);
            printingLoader?.classList.toggle('border-[#84b5c5]', isLoading);
            printingLoader?.classList.toggle('border-t-[#2b7896]', isLoading);
            printingLoader?.classList.toggle('border-emerald-500', !isLoading);
            printingLoader?.classList.toggle('bg-emerald-500', !isLoading);
            printingSuccessIcon?.classList.toggle('hidden', isLoading);
        }

        function closePrinting() {
            if (printingTimer) {
                clearTimeout(printingTimer);
            }

            if (printingModal.open) {
                printingModal.close();
            }

            modal.close();
        }

        printingCancel.addEventListener('click', closePrinting);
        printingCancelTop.addEventListener('click', closePrinting);

        [cardLookupModal, paymentListModal, confirmPrintModal, printingModal].forEach((child) => {
            child.addEventListener('click', (event) => {
                if (event.target === child) {
                    child.close();
                }
            });
        });

        updateAmountInputs();
        paymentAmountInput.value = formatMoney(remaining);
        renderPaymentList();
        activateTab('general');
        selectMethod('CASH');

    })();
</script>
