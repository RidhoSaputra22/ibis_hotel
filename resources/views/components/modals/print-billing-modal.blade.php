@props([
    'id' => 'printBillingModal',

    'outlet' => 'Ibis Kitchen',
    'date' => '15-Jun-2026 11:49',
    'tableNo' => '01',
    'checkNo' => '0000024388',
    'cashier' => 'FBADHA',
    'paymentType' => 'Full',
    'pax' => 1,

    'items' => [
        [
            'qty' => 1,
            'name' => 'Mie Strawberry',
            'price' => 8264,
        ],
        [
            'qty' => 1,
            'name' => 'Choco Vanila',
            'price' => 8264,
        ],
    ],

    'discount' => 0,
    'service' => 1653,
    'tax' => 1810,
])

@php
    $subTotal = collect($items)->sum(function ($item) {
        return $item['qty'] * $item['price'];
    });

    $grandTotal = $subTotal - $discount + $service + $tax;
    $initialPayload = [
        'outlet' => $outlet,
        'date' => $date,
        'tableNo' => $tableNo,
        'checkNo' => $checkNo,
        'cashier' => $cashier,
        'paymentType' => $paymentType,
        'pax' => $pax,
        'items' => $items,
        'subTotal' => $subTotal,
        'discount' => $discount,
        'service' => $service,
        'tax' => $tax,
        'grandTotal' => $grandTotal,
    ];
@endphp

<style>
    #{{ $id }}::backdrop {
        background: rgba(15, 23, 42, .45);
        backdrop-filter: blur(2px);
    }

    @media print {
        body * {
            visibility: hidden;
        }

        #{{ $id }}Receipt,
        #{{ $id }}Receipt * {
            visibility: visible;
        }

        #{{ $id }}Receipt {
            position: fixed;
            inset: 0;
            width: 100%;
            height: auto;
            overflow: visible;
            border: none;
            background: white;
        }
    }
</style>

<dialog
    id="{{ $id }}"
    class="m-auto w-[calc(100%-1.5rem)] max-w-2xl overflow-hidden rounded-[2px] border border-[#91aab2] bg-[#edf4f6] p-0 shadow-2xl"
>
    <div class="min-w-0">

        {{-- Header --}}
        <div class="flex h-9 items-center justify-between border-b border-[#afc2c8] bg-[linear-gradient(180deg,#eaf5f8_0%,#d5e8ee_100%)] px-3">
            <div class="flex items-center gap-1.5">
                <span class="grid h-4 w-4 place-items-center rounded-[2px] border border-[#78a3b4] bg-[#d9f0f5] text-[9px] font-black text-[#326a7e]">
                    P
                </span>

                <h2 class="text-[11px] font-bold text-[#536b74]">
                    Print Billing
                </h2>
            </div>

            <button
                type="button"
                data-close-print-billing
                class="grid h-5 w-5 place-items-center rounded-[2px] text-[#6b7f87] transition hover:bg-[#c9e0e7] hover:text-[#244e5f]"
            >
                ✕
            </button>
        </div>

        {{-- Area preview --}}
        <div class="bg-[#dce5e8] p-4">
            <div
                id="{{ $id }}Viewport"
                class="h-[430px] overflow-auto rounded-[2px] border border-[#a9b8bd] bg-[#cfd9dc] p-3 shadow-inner"
            >
                <div
                    id="{{ $id }}Receipt"
                    class="min-h-full w-[560px] bg-white px-6 py-7 font-mono text-[11px] leading-[1.55] text-[#3f4d52] shadow-md"
                >
                    <div class="whitespace-pre">
<span id="{{ $id }}Outlet">{{ $outlet }}</span>

Date      : <span id="{{ $id }}Date">{{ $date }}</span>

========================================

Table No  : <span id="{{ $id }}TableNo">{{ $tableNo }}</span>
Check #   : <span id="{{ $id }}CheckNo">{{ $checkNo }}</span>
Cashier   : <span id="{{ $id }}Cashier">{{ $cashier }}</span>
Pmt.Type  : <span id="{{ $id }}PaymentType">{{ $paymentType }}</span>
Pax       : <span id="{{ $id }}Pax">{{ $pax }}</span>

========================================
                    </div>

                    <div id="{{ $id }}Items" class="grid grid-cols-[35px_1fr_90px] gap-x-2">
                        @foreach ($items as $item)
                            <span>{{ $item['qty'] }}</span>
                            <span>{{ $item['name'] }}</span>
                            <span class="text-right">
                                {{ number_format($item['qty'] * $item['price'], 0, ',', '.') }}
                            </span>
                        @endforeach
                    </div>

                    <div class="mt-2 whitespace-pre">
========================================
Sub Total : <span id="{{ $id }}SubTotal">{{ number_format($subTotal, 0, ',', '.') }}</span>
Discount  : <span id="{{ $id }}Discount">{{ number_format($discount, 0, ',', '.') }}</span>
Service   : <span id="{{ $id }}Service">{{ number_format($service, 0, ',', '.') }}</span>
Tax       : <span id="{{ $id }}Tax">{{ number_format($tax, 0, ',', '.') }}</span>
----------------------------------------
Total     : <span id="{{ $id }}GrandTotal">{{ number_format($grandTotal, 0, ',', '.') }}</span>

<span id="{{ $id }}FooterLine">{{ $date }} {{ $cashier }}</span>

Thank you for dining with us.
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer Button --}}
        <div class="flex flex-wrap items-center justify-between gap-3 border-t border-[#c5d4d8] bg-[#edf4f6] px-4 py-3">
            <p id="{{ $id }}ZoomInfo" class="text-[10px] text-[#6d838a]">
                Zoom: 100%
            </p>

            <div class="flex flex-wrap items-center gap-2">
                <button
                    type="button"
                    id="{{ $id }}Print"
                    class="inline-flex h-8 min-w-[70px] items-center justify-center rounded-[2px] border border-[#7caebb] bg-[#e2f3f7] px-4 text-[10px] font-bold text-[#3d6e7e] transition hover:border-[#4f97ae] hover:bg-[#d0eaf1]"
                >
                    Print
                </button>

                <button
                    type="button"
                    id="{{ $id }}Preview"
                    class="inline-flex h-8 min-w-[70px] items-center justify-center rounded-[2px] border border-[#aabec4] bg-white px-4 text-[10px] font-bold text-[#5f747b] transition hover:bg-slate-100"
                >
                    Preview
                </button>

                <button
                    type="button"
                    id="{{ $id }}ZoomIn"
                    class="inline-flex h-8 min-w-[70px] items-center justify-center rounded-[2px] border border-[#aabec4] bg-white px-4 text-[10px] font-bold text-[#5f747b] transition hover:bg-slate-100"
                >
                    Zoom In
                </button>

                <button
                    type="button"
                    id="{{ $id }}ZoomOut"
                    class="inline-flex h-8 min-w-[70px] items-center justify-center rounded-[2px] border border-[#aabec4] bg-white px-4 text-[10px] font-bold text-[#5f747b] transition hover:bg-slate-100"
                >
                    Zoom Out
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

        const receipt = document.getElementById(@js($id . 'Receipt'));
        const outletField = document.getElementById(@js($id . 'Outlet'));
        const dateField = document.getElementById(@js($id . 'Date'));
        const tableNoField = document.getElementById(@js($id . 'TableNo'));
        const checkNoField = document.getElementById(@js($id . 'CheckNo'));
        const cashierField = document.getElementById(@js($id . 'Cashier'));
        const paymentTypeField = document.getElementById(@js($id . 'PaymentType'));
        const paxField = document.getElementById(@js($id . 'Pax'));
        const itemsField = document.getElementById(@js($id . 'Items'));
        const subTotalField = document.getElementById(@js($id . 'SubTotal'));
        const discountField = document.getElementById(@js($id . 'Discount'));
        const serviceField = document.getElementById(@js($id . 'Service'));
        const taxField = document.getElementById(@js($id . 'Tax'));
        const grandTotalField = document.getElementById(@js($id . 'GrandTotal'));
        const footerLineField = document.getElementById(@js($id . 'FooterLine'));
        const zoomInfo = document.getElementById(@js($id . 'ZoomInfo'));

        const printButton = document.getElementById(@js($id . 'Print'));
        const previewButton = document.getElementById(@js($id . 'Preview'));
        const zoomInButton = document.getElementById(@js($id . 'ZoomIn'));
        const zoomOutButton = document.getElementById(@js($id . 'ZoomOut'));
        const initialPayload = @json($initialPayload);

        let zoomLevel = 1;

        function formatAmount(value) {
            return new Intl.NumberFormat('id-ID', {
                maximumFractionDigits: 0,
            }).format(Number(value ?? 0));
        }

        function escapeHtml(value = '') {
            return String(value).replace(/[&<>"']/g, (char) => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;',
            }[char]));
        }

        function updateZoom() {
            receipt.style.transformOrigin = 'top left';
            receipt.style.transform = `scale(${zoomLevel})`;

            zoomInfo.textContent = `Zoom: ${Math.round(zoomLevel * 100)}%`;
        }

        function normalizePayload(payload = {}) {
            const items = Array.isArray(payload.items)
                ? payload.items.map((item) => ({
                    qty: Number(item.qty ?? 0),
                    name: item.name ?? '-',
                    price: Number(item.price ?? 0),
                }))
                : initialPayload.items;
            const subTotal = Number(payload.subTotal ?? items.reduce((sum, item) => sum + (item.qty * item.price), 0));
            const discount = Number(payload.discount ?? initialPayload.discount ?? 0);
            const service = Number(payload.service ?? initialPayload.service ?? 0);
            const tax = Number(payload.tax ?? initialPayload.tax ?? 0);

            return {
                outlet: payload.outlet ?? initialPayload.outlet,
                date: payload.date ?? initialPayload.date,
                tableNo: payload.tableNo ?? initialPayload.tableNo,
                checkNo: payload.checkNo ?? initialPayload.checkNo,
                cashier: payload.cashier ?? initialPayload.cashier,
                paymentType: payload.paymentType ?? initialPayload.paymentType,
                pax: Number(payload.pax ?? initialPayload.pax ?? 1),
                items,
                subTotal,
                discount,
                service,
                tax,
                grandTotal: Number(payload.grandTotal ?? (subTotal - discount + service + tax)),
            };
        }

        function populate(payload = {}) {
            const data = normalizePayload(payload);

            outletField.textContent = data.outlet;
            dateField.textContent = data.date;
            tableNoField.textContent = data.tableNo;
            checkNoField.textContent = data.checkNo;
            cashierField.textContent = data.cashier;
            paymentTypeField.textContent = data.paymentType;
            paxField.textContent = String(data.pax);
            subTotalField.textContent = formatAmount(data.subTotal);
            discountField.textContent = formatAmount(data.discount);
            serviceField.textContent = formatAmount(data.service);
            taxField.textContent = formatAmount(data.tax);
            grandTotalField.textContent = formatAmount(data.grandTotal);
            footerLineField.textContent = `${data.date} ${data.cashier}`;

            itemsField.innerHTML = data.items.length > 0
                ? data.items.map((item) => `
                    <span>${item.qty}</span>
                    <span>${escapeHtml(item.name)}</span>
                    <span class="text-right">${formatAmount(item.qty * item.price)}</span>
                `).join('')
                : `
                    <span></span>
                    <span class="text-slate-400">Tidak ada item.</span>
                    <span></span>
                `;
        }

        modal.querySelectorAll('[data-close-print-billing]').forEach((button) => {
            button.addEventListener('click', () => {
                modal.close();
            });
        });

        zoomInButton.addEventListener('click', () => {
            if (zoomLevel < 1.8) {
                zoomLevel += 0.1;
                updateZoom();
            }
        });

        zoomOutButton.addEventListener('click', () => {
            if (zoomLevel > 0.6) {
                zoomLevel -= 0.1;
                updateZoom();
            }
        });

        previewButton.addEventListener('click', () => {
            zoomLevel = 1;
            updateZoom();

            document.getElementById(@js($id . 'Viewport')).scrollTo({
                top: 0,
                left: 0,
                behavior: 'smooth'
            });
        });

        printButton.addEventListener('click', () => {
            window.print();
        });

        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                modal.close();
            }
        });

        window.addEventListener('print-billing:preview', (event) => {
            populate(event.detail || {});
            zoomLevel = 1;
            updateZoom();

            if (!modal.open && typeof modal.showModal === 'function') {
                modal.showModal();
            }
        });

        populate(initialPayload);
        updateZoom();
    })();
</script>
