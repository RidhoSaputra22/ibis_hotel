@props([
    'selectedTable' => '01',
])

@push('scripts')
<script>
    (() => {
        const orderItems = [];
        const splitZones = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
        const orderItemsBody = document.getElementById('orderItemsBody');
        const grandTotal = document.getElementById('grandTotal');
        const itemCountBadge = document.getElementById('itemCountBadge');
        const activeSplitBadge = document.getElementById('activeSplitBadge');
        const serviceSearch = document.getElementById('serviceSearch');
        const serviceCards = [...document.querySelectorAll('.service-card')];
        const categoryButtons = [...document.querySelectorAll('.category-button')];
        const selectedCategoryTitle = document.getElementById('selectedCategoryTitle');
        const noServiceMessage = document.getElementById('noServiceMessage');
        const zoneButtons = [...document.querySelectorAll('.zone-button')];
        const orderTabs = [...document.querySelectorAll('.order-tab')];
        const waiterModal = document.getElementById('waiterModal');
        const waiterInput = document.getElementById('waiterInput');
        const paxInput = document.getElementById('paxInput');
        const guestNameInput = document.getElementById('guestNameInput');
        const chargeGuestNameInput = document.getElementById('chargeGuestNameInput');
        const roomChargeReferenceInput = document.getElementById('roomChargeReferenceInput');
        const discountTypeInput = document.getElementById('discountTypeInput');
        const docNoInput = document.getElementById('docNoInput');
        const timeZoneInput = document.getElementById('timeZoneInput');
        const groupChargeInput = document.getElementById('groupChargeInput');
        const activeTableTab = document.getElementById('transactionTableTab');
        const billingButtons = [...document.querySelectorAll('[data-open-dialog="printBillingModal"]')];
        const settleButtons = [...document.querySelectorAll('[data-open-dialog="settleModal"]')];
        const moveSplitButtons = [...document.querySelectorAll('[data-open-dialog="moveSplitModal"]')];
        const baseTableNo = @json($selectedTable);
        const cashierSession = {
            cashierCode: @json(session('cashier_login.cashier', session('cashier_login.display_name', 'ADHA'))),
            cashierName: @json(session('cashier_login.display_name', 'ADHA')),
            outletName: @json(session('cashier_login.outlet', 'Ibis Kitchen')),
            sessionStartedAt: @json(session('cashier_login.logged_in_at', now()->toIso8601String())),
        };
        const outletCodeMap = {
            'Ibis Kitchen': '10',
            'Restaurant Terrace': '20',
            'Room Service': '30',
            'Banquet': '40',
            'Pool Bar': '50',
        };
        const sessionStorageKey = `ibis-hotel:cashier-session:${cashierSession.sessionStartedAt}:${cashierSession.cashierCode}`;
        const transactionsStorageKey = `${sessionStorageKey}:transactions`;
        const sequenceStorageKey = `${sessionStorageKey}:sequence`;
        const settledTransactionsStorageKey = 'ibis-hotel:restaurant:settled-transactions';

        if (!orderItemsBody || !grandTotal || !itemCountBadge) return;

        let activeCategory = categoryButtons[0]?.dataset.category ?? 'Appetizer';
        let activeSplitZone = zoneButtons.find((button) => button.classList.contains('ring-2'))?.dataset.zone ?? 'A';
        let currentDraftIdentities = {};
        let lastCompletedTransaction = null;

        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                maximumFractionDigits: 0,
            }).format(number);
        }

        function formatShortDateTime(date) {
            const datePart = new Intl.DateTimeFormat('en-GB', {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
            }).format(date).replace(/ /g, '-');
            const timePart = new Intl.DateTimeFormat('en-GB', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false,
            }).format(date);

            return `${datePart} ${timePart}`;
        }

        function toBusinessDate(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');

            return `${year}-${month}-${day}`;
        }

        function normalizeText(value, fallback = '-') {
            const text = String(value ?? '').trim();
            return text || fallback;
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

        function parseStorageArray(storageKey) {
            try {
                const parsed = JSON.parse(localStorage.getItem(storageKey) ?? '[]');
                return Array.isArray(parsed) ? parsed : [];
            } catch (error) {
                return [];
            }
        }

        function loadTransactions() {
            return parseStorageArray(transactionsStorageKey);
        }

        function saveTransactions(transactions) {
            localStorage.setItem(transactionsStorageKey, JSON.stringify(transactions));
        }

        function loadSettledTransactions() {
            return parseStorageArray(settledTransactionsStorageKey);
        }

        function saveSettledTransactions(transactions) {
            localStorage.setItem(settledTransactionsStorageKey, JSON.stringify(transactions));
        }

        function loadSequenceState() {
            try {
                const parsed = JSON.parse(localStorage.getItem(sequenceStorageKey) ?? '{}');
                return parsed && typeof parsed === 'object' ? parsed : {};
            } catch (error) {
                return {};
            }
        }

        function nextSequenceValue(key, seed, increment = 1) {
            const sequenceState = loadSequenceState();
            const nextNumber = Number(sequenceState[key] ?? seed);

            sequenceState[key] = nextNumber + increment;
            localStorage.setItem(sequenceStorageKey, JSON.stringify(sequenceState));

            return nextNumber;
        }

        function nextDocumentNumber(key, seed) {
            return String(nextSequenceValue(key, seed)).padStart(10, '0');
        }

        function nextLineSequence() {
            return String(nextSequenceValue('lineSeq', 100, 100)).padStart(6, '0');
        }

        function extractTableNo() {
            return normalizeText(baseTableNo, '01');
        }

        function normalizeZone(zone, fallback = 'A') {
            const normalized = String(zone ?? '').trim().toUpperCase();
            return splitZones.includes(normalized) ? normalized : fallback;
        }

        function getItemsForZone(zone = activeSplitZone) {
            return orderItems.filter((item) => item.splitZone === zone);
        }

        function getVisibleItems() {
            return [...getItemsForZone(activeSplitZone)].sort((left, right) => {
                return Number(left.seq) - Number(right.seq);
            });
        }

        function getFirstOccupiedZone() {
            return splitZones.find((zone) => getItemsForZone(zone).length > 0) ?? null;
        }

        function getDraftIdentity(zone) {
            return currentDraftIdentities[zone] ?? null;
        }

        function ensureDraftIdentity(zone = activeSplitZone) {
            if (!currentDraftIdentities[zone]) {
                currentDraftIdentities[zone] = {
                    checkNo: nextDocumentNumber('checkNo', 24358),
                    billNo: nextDocumentNumber('billNo', 24593),
                };
            }

            return currentDraftIdentities[zone];
        }

        function resolveServiceCode(code, name) {
            const normalizedCode = String(code ?? '').trim();

            if (normalizedCode) {
                return normalizedCode;
            }

            const compactName = String(name ?? '')
                .toUpperCase()
                .replace(/[^A-Z0-9]+/g, '')
                .slice(0, 8);

            return `FNB-${compactName || 'GEN'}`;
        }

        function calculateBill(items = []) {
            const subTotal = items.reduce((sum, item) => sum + (Number(item.price) * Number(item.qty)), 0);
            const discount = 0;
            const service = Math.round(subTotal * 0.10);
            const tax = Math.round(subTotal * 0.11);

            return {
                subTotal,
                discount,
                service,
                tax,
                total: subTotal - discount + service + tax,
            };
        }

        function classifyPayment(label = '') {
            const normalized = String(label).toUpperCase();

            if (normalized.includes('CASH')) {
                return 'cash';
            }

            if (
                normalized.includes('CARD')
                || normalized.includes('QRIS')
                || normalized.includes('VISA')
                || normalized.includes('MASTER')
                || normalized.includes('DEBIT')
            ) {
                return 'card';
            }

            return 'other';
        }

        function buildPaymentBreakdown(payments = []) {
            return payments.reduce((totals, payment) => {
                const bucket = classifyPayment(payment.label ?? payment.paymentType ?? '');
                const amount = Number(payment.amount ?? 0);

                totals[bucket] += amount;

                return totals;
            }, { cash: 0, card: 0, other: 0 });
        }

        function buildSnapshotItems(zone = activeSplitZone) {
            return getItemsForZone(zone).map((item) => ({
                uid: item.uid,
                seq: item.seq,
                serviceCode: item.serviceCode,
                name: item.name,
                price: Number(item.price),
                qty: Number(item.qty),
                seatNo: Number(item.seatNo),
                splitZone: item.splitZone,
            }));
        }

        function buildOrderSnapshot(zone = activeSplitZone) {
            const items = buildSnapshotItems(zone);

            if (items.length === 0) {
                return null;
            }

            const identity = getDraftIdentity(zone) ?? ensureDraftIdentity(zone);
            const now = new Date();
            const bill = calculateBill(items);

            return {
                outletCode: outletCodeMap[cashierSession.outletName] ?? '10',
                outletName: cashierSession.outletName,
                cashierCode: cashierSession.cashierCode,
                cashierName: cashierSession.cashierName,
                waiter: normalizeText(waiterInput?.value, cashierSession.cashierName),
                guestName: normalizeText(guestNameInput?.value, '-'),
                chargeGuestName: normalizeText(chargeGuestNameInput?.value, guestNameInput?.value || '-'),
                roomReference: normalizeText(roomChargeReferenceInput?.value, '-'),
                discountType: normalizeText(discountTypeInput?.value, 'Full Payment'),
                docNo: normalizeText(docNoInput?.value, '01'),
                timeZone: normalizeText(timeZoneInput?.value, 'Breakfast'),
                groupCharge: normalizeText(groupChargeInput?.value, 'F&B'),
                tableNo: extractTableNo(),
                splitZone: zone,
                tableLabel: `${extractTableNo()} / Split ${zone}`,
                checkNo: identity.checkNo,
                billNo: identity.billNo,
                pax: Math.max(1, Number(paxInput?.value || 1)),
                paymentType: 'Full',
                businessDate: toBusinessDate(now),
                printedDate: formatShortDateTime(now),
                transactionAt: now.toISOString(),
                items,
                ...bill,
            };
        }

        function resolveDisplayPaymentType(snapshot) {
            const baseType = normalizeText(snapshot?.paymentType, 'Full');
            const splitZone = normalizeZone(snapshot?.splitZone ?? '', '');

            if (!splitZone || baseType.includes(`Split ${splitZone}`)) {
                return baseType;
            }

            return `${baseType} / Split ${splitZone}`;
        }

        function buildBillingPayload(source = null) {
            const snapshot = source
                ?? buildOrderSnapshot(activeSplitZone)
                ?? (orderItems.length === 0 ? lastCompletedTransaction : null);

            if (!snapshot) {
                return null;
            }

            return {
                outlet: snapshot.outletName ?? cashierSession.outletName,
                date: snapshot.printedDate ?? formatShortDateTime(new Date(snapshot.transactionAt ?? Date.now())),
                tableNo: snapshot.tableLabel ?? snapshot.tableNo ?? extractTableNo(),
                checkNo: snapshot.checkNo ?? (getDraftIdentity(activeSplitZone)?.checkNo ?? '0000000000'),
                cashier: snapshot.cashierCode ?? cashierSession.cashierCode,
                paymentType: resolveDisplayPaymentType(snapshot),
                pax: snapshot.pax ?? 1,
                items: Array.isArray(snapshot.items) ? snapshot.items.map((item) => ({
                    qty: Number(item.qty ?? 0),
                    name: item.name ?? '-',
                    price: Number(item.price ?? 0),
                })) : [],
                subTotal: Number(snapshot.subTotal ?? 0),
                discount: Number(snapshot.discount ?? 0),
                service: Number(snapshot.service ?? 0),
                tax: Number(snapshot.tax ?? 0),
                grandTotal: Number(snapshot.total ?? snapshot.grandTotal ?? 0),
            };
        }

        function buildSettlementConfig(zone = activeSplitZone) {
            const snapshot = buildOrderSnapshot(zone);

            if (!snapshot) {
                return null;
            }

            return {
                billNo: snapshot.billNo,
                totalAmount: snapshot.total,
                splitZone: zone,
            };
        }

        function persistCompletedTransaction(zone, detail = {}) {
            const snapshot = buildOrderSnapshot(zone);

            if (!snapshot) {
                return null;
            }

            const settledAt = new Date().toISOString();
            const payments = Array.isArray(detail.payments)
                ? detail.payments.map((payment) => ({
                    label: payment.label ?? payment.paymentType ?? '-',
                    amount: Number(payment.amount ?? 0),
                }))
                : [];
            const paymentBreakdown = buildPaymentBreakdown(payments);
            const transactionRecord = {
                ...snapshot,
                storageId: `${cashierSession.sessionStartedAt}:${snapshot.billNo}:${snapshot.transactionAt}:${zone}`,
                paymentType: detail.paymentType ?? snapshot.paymentType,
                tips: Number(detail.tips ?? 0),
                change: Number(detail.change ?? 0),
                payments,
                paymentBreakdown,
                transactionCount: 1,
                status: 'settled',
                source: 'restaurant-transaction',
                settledAt,
            };
            const transactions = [
                ...loadTransactions().filter((transaction) => transaction?.storageId !== transactionRecord.storageId),
                transactionRecord,
            ];
            const settledTransactions = [
                ...loadSettledTransactions().filter((transaction) => transaction?.storageId !== transactionRecord.storageId),
                transactionRecord,
            ];

            saveTransactions(transactions);
            saveSettledTransactions(settledTransactions);

            lastCompletedTransaction = transactionRecord;

            return transactionRecord;
        }

        function resetSharedFields() {
            if (guestNameInput) guestNameInput.value = '';
            if (chargeGuestNameInput) chargeGuestNameInput.value = '';
            if (roomChargeReferenceInput) roomChargeReferenceInput.value = '';
            if (paxInput) paxInput.value = 1;
        }

        function resetOrder() {
            orderItems.splice(0, orderItems.length);
            currentDraftIdentities = {};
            activeSplitZone = 'A';

            resetSharedFields();
            renderOrder();
        }

        function createOrderItem({
            name,
            price,
            serviceCode,
            qty = 1,
            splitZone = activeSplitZone,
            seatNo = 1,
        }) {
            const seq = nextLineSequence();

            return {
                uid: `line-${seq}`,
                seq,
                serviceCode: resolveServiceCode(serviceCode, name),
                name,
                price: Number(price),
                qty: Math.max(1, Number(qty || 1)),
                splitZone: normalizeZone(splitZone, activeSplitZone),
                seatNo: Math.max(1, Number(seatNo || 1)),
            };
        }

        function findMergeTarget(sourceItem, destinationZone, destinationSeatNo, excludedUid = null) {
            return orderItems.find((item) => {
                return item.uid !== excludedUid
                    && item.splitZone === destinationZone
                    && Number(item.seatNo) === Number(destinationSeatNo)
                    && item.serviceCode === sourceItem.serviceCode
                    && Number(item.price) === Number(sourceItem.price)
                    && item.name === sourceItem.name;
            });
        }

        function removeItemsForZone(zone, { clearIdentity = false } = {}) {
            const removableItems = getItemsForZone(zone);

            removableItems.forEach((item) => {
                const index = orderItems.findIndex((candidate) => candidate.uid === item.uid);

                if (index >= 0) {
                    orderItems.splice(index, 1);
                }
            });

            if (clearIdentity) {
                delete currentDraftIdentities[zone];
            }
        }

        function updateTableContext() {
            if (activeTableTab) {
                activeTableTab.textContent = `TABLE ${extractTableNo()}`;
            }

            if (activeSplitBadge) {
                activeSplitBadge.textContent = `Split ${activeSplitZone}`;
            }
        }

        function renderZoneButtons() {
            zoneButtons.forEach((button) => {
                const zone = normalizeZone(button.dataset.zone, 'A');
                const itemCount = getItemsForZone(zone).reduce((sum, item) => sum + Number(item.qty), 0);
                const isActive = zone === activeSplitZone;

                button.classList.toggle('ring-2', isActive);
                button.classList.toggle('ring-[#2c88b4]', isActive);
                button.classList.toggle('ring-offset-1', isActive);
                button.title = itemCount > 0
                    ? `${itemCount} item pada split ${zone}`
                    : `Split ${zone} kosong`;
            });
        }

        function renderOrder() {
            const visibleItems = getVisibleItems();
            const totalQty = visibleItems.reduce((sum, item) => sum + Number(item.qty), 0);
            const total = visibleItems.reduce((sum, item) => sum + (Number(item.price) * Number(item.qty)), 0);

            itemCountBadge.textContent = `${totalQty} item`;
            grandTotal.textContent = formatRupiah(total);

            updateTableContext();
            renderZoneButtons();

            if (visibleItems.length === 0) {
                orderItemsBody.innerHTML = `
                    <tr id="emptyItemRow">
                        <td colspan="4" class="px-3 py-10 text-center text-slate-400">
                            Belum ada item di split ${escapeHtml(activeSplitZone)}. Pilih menu di sebelah kanan.
                        </td>
                    </tr>
                `;
                return;
            }

            orderItemsBody.innerHTML = visibleItems.map((item) => `
                <tr class="border-b border-slate-100 hover:bg-slate-50">
                    <td class="px-3 py-2">
                        <div class="font-semibold text-slate-600">${escapeHtml(item.name)}</div>
                        <div class="mt-0.5 text-[9px] text-slate-400">
                            ${escapeHtml(item.serviceCode)} · Seat ${item.seatNo} · Split ${item.splitZone}
                        </div>
                    </td>
                    <td class="px-2 py-2 text-center">
                        <div class="inline-flex items-center overflow-hidden rounded-sm border border-slate-200 bg-white">
                            <button class="qty-button px-1.5 py-1 text-slate-500 hover:bg-slate-100" data-item-id="${escapeHtml(item.uid)}" data-change="-1">−</button>
                            <span class="min-w-6 px-1 text-center text-slate-700">${item.qty}</span>
                            <button class="qty-button px-1.5 py-1 text-slate-500 hover:bg-slate-100" data-item-id="${escapeHtml(item.uid)}" data-change="1">+</button>
                        </div>
                    </td>
                    <td class="px-2 py-2 text-right font-semibold text-[#2a6d8c]">${formatRupiah(item.price * item.qty)}</td>
                    <td class="px-1 py-2 text-center">
                        <button class="remove-item rounded p-1 text-slate-400 hover:bg-rose-50 hover:text-rose-600" data-item-id="${escapeHtml(item.uid)}" title="Hapus item">
                            <i data-lucide="x" class="h-3.5 w-3.5"></i>
                        </button>
                    </td>
                </tr>
            `).join('');

            window.lucide?.createIcons({ attrs: { 'stroke-width': 1.8 } });

            document.querySelectorAll('.qty-button').forEach((button) => {
                button.addEventListener('click', () => {
                    const item = orderItems.find((candidate) => candidate.uid === button.dataset.itemId);
                    const change = Number(button.dataset.change);

                    if (!item) {
                        return;
                    }

                    item.qty += change;

                    if (item.qty <= 0) {
                        const index = orderItems.findIndex((candidate) => candidate.uid === item.uid);

                        if (index >= 0) {
                            orderItems.splice(index, 1);
                        }
                    }

                    renderOrder();
                });
            });

            document.querySelectorAll('.remove-item').forEach((button) => {
                button.addEventListener('click', () => {
                    const index = orderItems.findIndex((candidate) => candidate.uid === button.dataset.itemId);

                    if (index >= 0) {
                        orderItems.splice(index, 1);
                        renderOrder();
                    }
                });
            });
        }

        function addService(name, price, serviceCode) {
            ensureDraftIdentity(activeSplitZone);

            const existingItem = orderItems.find((item) => {
                return item.splitZone === activeSplitZone
                    && item.serviceCode === resolveServiceCode(serviceCode, name)
                    && Number(item.seatNo) === 1
                    && Number(item.price) === Number(price)
                    && item.name === name;
            });

            if (existingItem) {
                existingItem.qty += 1;
            } else {
                orderItems.push(createOrderItem({
                    name,
                    price,
                    serviceCode,
                    splitZone: activeSplitZone,
                    seatNo: 1,
                }));
            }

            renderOrder();
        }

        function filterServices() {
            const keyword = serviceSearch?.value.trim().toLowerCase() ?? '';
            let visibleCount = 0;

            serviceCards.forEach((card) => {
                const matchesCategory = card.dataset.serviceCategory === activeCategory;
                const matchesKeyword = !keyword || card.dataset.serviceName.toLowerCase().includes(keyword);
                const visible = matchesCategory && matchesKeyword;

                card.classList.toggle('hidden', !visible);

                if (visible) visibleCount += 1;
            });

            noServiceMessage?.classList.toggle('hidden', visibleCount > 0);
        }

        function prepareMoveSplitContext() {
            const preferredZone = getItemsForZone(activeSplitZone).length > 0
                ? activeSplitZone
                : (getFirstOccupiedZone() ?? activeSplitZone);
            const identity = getDraftIdentity(preferredZone) ?? ensureDraftIdentity(preferredZone);

            window.dispatchEvent(new CustomEvent('move-split:prepare', {
                detail: {
                    tableNo: extractTableNo(),
                    orderNo: identity?.checkNo ?? '0000000000',
                    currentZone: activeSplitZone,
                    items: orderItems.map((item) => ({
                        itemId: item.uid,
                        seq: item.seq,
                        serviceCode: item.serviceCode,
                        description: item.name,
                        seatNo: item.seatNo,
                        qty: item.qty,
                        split: item.splitZone,
                        splitQty: 1,
                    })),
                },
            }));
        }

        function setActiveSplitZone(zone) {
            activeSplitZone = normalizeZone(zone, activeSplitZone);
            renderOrder();
        }

        serviceCards.forEach((card) => {
            card.addEventListener('click', () => {
                addService(
                    card.dataset.serviceName,
                    card.dataset.servicePrice,
                    card.dataset.serviceCode,
                );
            });
        });

        categoryButtons.forEach((button) => {
            button.addEventListener('click', () => {
                activeCategory = button.dataset.category;
                selectedCategoryTitle.textContent = activeCategory;
                categoryButtons.forEach((item) => item.classList.remove('ring-2', 'ring-[#3a98c4]', 'ring-offset-1'));
                button.classList.add('ring-2', 'ring-[#3a98c4]', 'ring-offset-1');
                filterServices();
            });
        });

        serviceSearch?.addEventListener('input', filterServices);

        document.getElementById('clearSearchButton')?.addEventListener('click', () => {
            activeCategory = categoryButtons[0]?.dataset.category ?? activeCategory;
            if (serviceSearch) serviceSearch.value = '';
            selectedCategoryTitle.textContent = activeCategory;
            categoryButtons.forEach((button) => {
                const isActive = button.dataset.category === activeCategory;
                button.classList.toggle('ring-2', isActive);
                button.classList.toggle('ring-[#3a98c4]', isActive);
                button.classList.toggle('ring-offset-1', isActive);
            });
            filterServices();
        });

        zoneButtons.forEach((button) => {
            button.addEventListener('click', () => {
                setActiveSplitZone(button.dataset.zone);
            });
        });

        orderTabs.forEach((tab) => {
            tab.addEventListener('click', () => {
                orderTabs.forEach((item) => item.classList.remove('bg-white', 'text-[#256f94]', 'shadow-sm'));
                tab.classList.add('bg-white', 'text-[#256f94]', 'shadow-sm');
            });
        });

        document.getElementById('clearOrderButton')?.addEventListener('click', () => {
            if (orderItems.length === 0 || window.confirm('Hapus semua item pesanan di seluruh split?')) {
                resetOrder();
            }
        });

        document.getElementById('cancelOrderButton')?.addEventListener('click', () => {
            if (window.confirm('Batalkan seluruh transaksi split pada meja ini?')) {
                resetOrder();
            }
        });

        document.getElementById('saveOrderButton')?.addEventListener('click', () => {
            const snapshot = buildOrderSnapshot(activeSplitZone);

            if (!snapshot) {
                window.alert(`Silakan tambahkan service pada split ${activeSplitZone} terlebih dahulu.`);
                return;
            }

            window.alert(
                `Pesanan split ${snapshot.splitZone} disimpan.\nCheck #: ${snapshot.checkNo}\nTotal: ${formatRupiah(snapshot.total)}`
            );
        });

        document.getElementById('addOrderButton')?.addEventListener('click', () => serviceSearch?.focus());
        document.getElementById('openWaiterButton')?.addEventListener('click', () => waiterModal?.showModal());
        document.querySelectorAll('[data-waiter-close]').forEach((button) => button.addEventListener('click', () => waiterModal?.close()));
        document.querySelectorAll('.waiter-choice').forEach((button) => {
            button.addEventListener('click', () => {
                if (waiterInput) waiterInput.value = button.dataset.waiter;
                waiterModal?.close();
            });
        });
        waiterModal?.addEventListener('click', (event) => { if (event.target === waiterModal) waiterModal.close(); });

        window.addEventListener('reservation-folio-selected', (event) => {
            const nameField = document.getElementById('chargeGuestNameInput');
            const roomField = document.getElementById('roomChargeReferenceInput');
            if (nameField) nameField.value = event.detail.name ?? '';
            if (roomField) roomField.value = event.detail.roomNo ?? '';
        });

        window.addEventListener('folio-selected', (event) => {
            const nameField = document.getElementById('chargeGuestNameInput');
            const referenceField = document.getElementById('roomChargeReferenceInput');
            if (nameField) nameField.value = event.detail.description ?? '';
            if (referenceField) referenceField.value = event.detail.folioNo ?? '';
        });

        moveSplitButtons.forEach((button) => {
            button.addEventListener('click', (event) => {
                if (!orderItems.length) {
                    event.preventDefault();
                    event.stopPropagation();
                    window.alert('Belum ada item yang bisa dipindahkan ke split lain.');
                    return;
                }

                prepareMoveSplitContext();
            });
        });

        billingButtons.forEach((button) => {
            button.addEventListener('click', (event) => {
                const payload = buildBillingPayload();

                if (!payload) {
                    event.preventDefault();
                    event.stopPropagation();
                    window.alert(`Belum ada transaksi pada split ${activeSplitZone} yang bisa dicetak.`);
                    return;
                }

                window.dispatchEvent(new CustomEvent('print-billing:preview', {
                    detail: payload,
                }));
            });
        });

        settleButtons.forEach((button) => {
            button.addEventListener('click', (event) => {
                const config = buildSettlementConfig(activeSplitZone);

                if (!config) {
                    event.preventDefault();
                    event.stopPropagation();
                    window.alert(`Silakan tambahkan service pada split ${activeSplitZone} terlebih dahulu.`);
                    return;
                }

                window.dispatchEvent(new CustomEvent('settlement:configure', {
                    detail: config,
                }));
            });
        });

        window.addEventListener('move-split-processed', (event) => {
            const detail = event.detail || {};
            const sourceItem = orderItems.find((item) => item.uid === detail.itemId);

            if (!sourceItem) {
                return;
            }

            const destinationZone = normalizeZone(detail.destination ?? detail.splitZone, sourceItem.splitZone);
            const destinationSeatNo = Math.max(1, Number(detail.destinationSeatNo ?? detail.seatNo ?? sourceItem.seatNo));
            const splitQty = Math.max(1, Number(detail.splitQty ?? 0));
            const acceptedSplitQty = Math.min(splitQty, Number(sourceItem.qty));
            const sameDestination = destinationZone === sourceItem.splitZone
                && Number(destinationSeatNo) === Number(sourceItem.seatNo);

            if (acceptedSplitQty < 1 || sameDestination) {
                window.alert('Tidak ada perubahan split yang diproses.');
                return;
            }

            ensureDraftIdentity(destinationZone);

            if (acceptedSplitQty === Number(sourceItem.qty)) {
                const targetItem = findMergeTarget(sourceItem, destinationZone, destinationSeatNo, sourceItem.uid);

                if (targetItem) {
                    targetItem.qty += Number(sourceItem.qty);

                    const sourceIndex = orderItems.findIndex((item) => item.uid === sourceItem.uid);
                    if (sourceIndex >= 0) {
                        orderItems.splice(sourceIndex, 1);
                    }
                } else {
                    sourceItem.splitZone = destinationZone;
                    sourceItem.seatNo = destinationSeatNo;
                }
            } else {
                sourceItem.qty -= acceptedSplitQty;

                const targetItem = findMergeTarget(sourceItem, destinationZone, destinationSeatNo);

                if (targetItem) {
                    targetItem.qty += acceptedSplitQty;
                } else {
                    orderItems.push(createOrderItem({
                        name: sourceItem.name,
                        price: sourceItem.price,
                        serviceCode: sourceItem.serviceCode,
                        qty: acceptedSplitQty,
                        splitZone: destinationZone,
                        seatNo: destinationSeatNo,
                    }));
                }
            }

            renderOrder();
        });

        window.addEventListener('settlement-completed', (event) => {
            const settledZone = activeSplitZone;
            const transactionRecord = persistCompletedTransaction(settledZone, event.detail || {});

            if (!transactionRecord) {
                return;
            }

            removeItemsForZone(settledZone, { clearIdentity: true });

            const nextOccupiedZone = getFirstOccupiedZone();

            if (nextOccupiedZone) {
                activeSplitZone = nextOccupiedZone;
            } else {
                activeSplitZone = 'A';
                resetSharedFields();
            }

            renderOrder();
        });

        const existingTransactions = loadTransactions();
        lastCompletedTransaction = existingTransactions.length > 0
            ? existingTransactions[existingTransactions.length - 1]
            : null;

        filterServices();
        renderOrder();
    })();
</script>
@endpush
