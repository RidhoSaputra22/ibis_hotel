@push('scripts')
<script>
    (() => {
        const orderItems = [];
        const orderItemsBody = document.getElementById('orderItemsBody');
        const grandTotal = document.getElementById('grandTotal');
        const itemCountBadge = document.getElementById('itemCountBadge');
        const serviceSearch = document.getElementById('serviceSearch');
        const serviceCards = [...document.querySelectorAll('.service-card')];
        const categoryButtons = [...document.querySelectorAll('.category-button')];
        const selectedCategoryTitle = document.getElementById('selectedCategoryTitle');
        const noServiceMessage = document.getElementById('noServiceMessage');
        const zoneButtons = [...document.querySelectorAll('.zone-button')];
        const orderTabs = [...document.querySelectorAll('.order-tab')];
        const waiterModal = document.getElementById('waiterModal');
        const waiterInput = document.getElementById('waiterInput');
        const activeTableTab = document.getElementById('transactionTableTab');

        if (!orderItemsBody || !grandTotal || !itemCountBadge) return;

        let activeCategory = categoryButtons[0]?.dataset.category ?? 'Appetizer';

        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                maximumFractionDigits: 0,
            }).format(number);
        }

        function renderOrder() {
            const totalQty = orderItems.reduce((sum, item) => sum + item.qty, 0);
            const total = orderItems.reduce((sum, item) => sum + (item.price * item.qty), 0);

            itemCountBadge.textContent = `${totalQty} item`;
            grandTotal.textContent = formatRupiah(total);

            if (orderItems.length === 0) {
                orderItemsBody.innerHTML = `
                    <tr id="emptyItemRow">
                        <td colspan="4" class="px-3 py-10 text-center text-slate-400">Belum ada item. Pilih menu di sebelah kanan.</td>
                    </tr>
                `;
                return;
            }

            orderItemsBody.innerHTML = orderItems.map((item, index) => `
                <tr class="border-b border-slate-100 hover:bg-slate-50">
                    <td class="px-3 py-2 font-semibold text-slate-600">${item.name}</td>
                    <td class="px-2 py-2 text-center">
                        <div class="inline-flex items-center overflow-hidden rounded-sm border border-slate-200 bg-white">
                            <button class="qty-button px-1.5 py-1 text-slate-500 hover:bg-slate-100" data-index="${index}" data-change="-1">−</button>
                            <span class="min-w-6 px-1 text-center text-slate-700">${item.qty}</span>
                            <button class="qty-button px-1.5 py-1 text-slate-500 hover:bg-slate-100" data-index="${index}" data-change="1">+</button>
                        </div>
                    </td>
                    <td class="px-2 py-2 text-right font-semibold text-[#2a6d8c]">${formatRupiah(item.price * item.qty)}</td>
                    <td class="px-1 py-2 text-center">
                        <button class="remove-item rounded p-1 text-slate-400 hover:bg-rose-50 hover:text-rose-600" data-index="${index}" title="Hapus item">
                            <i data-lucide="x" class="h-3.5 w-3.5"></i>
                        </button>
                    </td>
                </tr>
            `).join('');

            window.lucide?.createIcons({ attrs: { 'stroke-width': 1.8 } });

            document.querySelectorAll('.qty-button').forEach((button) => {
                button.addEventListener('click', () => {
                    const index = Number(button.dataset.index);
                    const change = Number(button.dataset.change);
                    orderItems[index].qty += change;
                    if (orderItems[index].qty <= 0) orderItems.splice(index, 1);
                    renderOrder();
                });
            });

            document.querySelectorAll('.remove-item').forEach((button) => {
                button.addEventListener('click', () => {
                    orderItems.splice(Number(button.dataset.index), 1);
                    renderOrder();
                });
            });
        }

        function addService(name, price) {
            const existingItem = orderItems.find((item) => item.name === name);
            if (existingItem) {
                existingItem.qty += 1;
            } else {
                orderItems.push({ name, price: Number(price), qty: 1 });
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

        serviceCards.forEach((card) => card.addEventListener('click', () => addService(card.dataset.serviceName, card.dataset.servicePrice)));

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
                zoneButtons.forEach((item) => item.classList.remove('ring-2', 'ring-[#2c88b4]', 'ring-offset-1'));
                button.classList.add('ring-2', 'ring-[#2c88b4]', 'ring-offset-1');
                if (activeTableTab) activeTableTab.textContent = `TABLE ${button.dataset.zone}01`;
            });
        });

        orderTabs.forEach((tab) => {
            tab.addEventListener('click', () => {
                orderTabs.forEach((item) => item.classList.remove('bg-white', 'text-[#256f94]', 'shadow-sm'));
                tab.classList.add('bg-white', 'text-[#256f94]', 'shadow-sm');
            });
        });

        document.getElementById('clearOrderButton')?.addEventListener('click', () => {
            if (orderItems.length === 0 || window.confirm('Hapus semua item pesanan?')) {
                orderItems.splice(0, orderItems.length);
                renderOrder();
            }
        });

        document.getElementById('cancelOrderButton')?.addEventListener('click', () => {
            if (window.confirm('Batalkan transaksi ini?')) {
                orderItems.splice(0, orderItems.length);
                document.getElementById('guestNameInput').value = '';
                document.getElementById('paxInput').value = 1;
                renderOrder();
            }
        });

        document.getElementById('saveOrderButton')?.addEventListener('click', () => {
            const total = orderItems.reduce((sum, item) => sum + (item.price * item.qty), 0);
            if (!orderItems.length) {
                window.alert('Silakan tambahkan service terlebih dahulu.');
                return;
            }
            window.alert(`Pesanan disimpan.\nTotal: ${formatRupiah(total)}`);
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

        filterServices();
        renderOrder();
    })();
</script>
@endpush
