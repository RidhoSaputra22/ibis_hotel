<section class="border-b border-slate-200 bg-[#fbfdfe] p-3 xl:border-b-0 xl:border-r">
    <div class="grid gap-2 sm:grid-cols-[64px_1fr]">
        <label class="field-label">Order Info</label>

        <div class="grid gap-2 sm:grid-cols-2">
            <x-forms.legacy-field label="Discount Type">
                <x-forms.legacy-select :options="['Full Payment', 'Percentage', 'Nominal']" selected="Full Payment" />
            </x-forms.legacy-field>
            <x-forms.legacy-field label="Doc. No">
                <x-forms.legacy-input value="01" readonly />
            </x-forms.legacy-field>
            <x-forms.legacy-field label="PAX" for="paxInput">
                <x-forms.legacy-input id="paxInput" type="number" min="1" value="1" />
            </x-forms.legacy-field>
            <x-forms.legacy-field label="Waiter" for="waiterInput">
                <x-forms.legacy-input id="waiterInput" value="ADHA" />
            </x-forms.legacy-field>
            <x-forms.legacy-field label="Guest Name" for="guestNameInput" class="sm:col-span-2">
                <x-forms.legacy-input id="guestNameInput" placeholder="Masukkan nama tamu" />
            </x-forms.legacy-field>
        </div>
    </div>

    <div class="mt-3 grid grid-cols-2 gap-1 rounded-sm border border-slate-200 bg-[#f1f6f7] p-1 text-[9px] font-bold text-slate-500 sm:grid-cols-5">
        @foreach (['ROOM', 'GRP MASTER', 'PERMANENT FOLIO', 'GOLF PERSONAL', 'BANQUET'] as $tab)
            <button type="button" class="order-tab rounded-sm px-1 py-2 transition hover:bg-white hover:text-[#256f94] {{ $loop->first ? 'bg-white text-[#256f94] shadow-sm' : '' }}">{{ $tab }}</button>
        @endforeach
    </div>

    <div class="mt-3 grid gap-2 sm:grid-cols-2">
        <x-forms.legacy-field label="Room">
            <div class="flex gap-1">
                <x-forms.legacy-input id="roomChargeReferenceInput" placeholder="No. Room / Folio" />
                <button type="button" class="mini-button" data-open-dialog="reservationFolioLookupModal" aria-label="Cari reservation"><i data-lucide="search" class="h-3 w-3"></i></button>
                <button type="button" class="mini-button" data-open-dialog="folioLookupModal" aria-label="Cari folio"><i data-lucide="receipt-text" class="h-3 w-3"></i></button>
            </div>
        </x-forms.legacy-field>
        <x-forms.legacy-field label="Name"><x-forms.legacy-input id="chargeGuestNameInput" placeholder="Nama tamu" /></x-forms.legacy-field>
        <x-forms.legacy-field label="Time Zone"><x-forms.legacy-select :options="['Breakfast', 'Lunch', 'Dinner']" selected="Breakfast" /></x-forms.legacy-field>
        <x-forms.legacy-field label="Group of Chg"><x-forms.legacy-select :options="['F&B', 'Room Charge', 'Cash']" selected="F&B" /></x-forms.legacy-field>
    </div>

    <x-restaurant.order-items-panel />
</section>
