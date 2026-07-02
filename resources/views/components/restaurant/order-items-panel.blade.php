<div class="mt-3 overflow-hidden rounded-sm border border-slate-300 bg-white">
    <div class="flex items-center justify-between border-b border-slate-200 bg-[#edf4f6] px-3 py-2">
        <h2 class="text-[11px] font-bold text-slate-600">Item List</h2>
        <span id="itemCountBadge" class="rounded-full bg-sky-100 px-2 py-0.5 text-[9px] font-bold text-sky-700">0 item</span>
    </div>

    <x-tables.legacy-table-shell wrapper-class="max-h-[250px] overflow-y-auto" table-class="w-full text-left text-[10px]">
            <thead class="sticky top-0 bg-[#f7fafb] text-slate-500">
                <tr class="border-b border-slate-200">
                    <th class="px-3 py-2 font-bold">Service</th>
                    <th class="w-14 px-2 py-2 text-center font-bold">Qty</th>
                    <th class="w-24 px-2 py-2 text-right font-bold">Net</th>
                    <th class="w-8 px-1 py-2"></th>
                </tr>
            </thead>
            <tbody id="orderItemsBody">
                <tr id="emptyItemRow">
                    <td colspan="4" class="px-3 py-10 text-center text-slate-400">Belum ada item. Pilih menu di sebelah kanan.</td>
                </tr>
            </tbody>
    </x-tables.legacy-table-shell>
</div>

<div class="mt-3 rounded-sm border border-slate-300 bg-[#edf4f6] p-3">
    <div class="flex items-center justify-between border-b border-slate-300 pb-2">
        <span class="text-sm font-black tracking-wide text-slate-600">TOTAL</span>
        <span id="grandTotal" class="text-lg font-black text-[#1d6687]">Rp0</span>
    </div>

    <div class="mt-3 grid grid-cols-2 gap-1.5 sm:grid-cols-5">
        <button class="mini-action" type="button">Delete Item</button>
        <button class="mini-action" type="button">Chg Qty</button>
        <button class="mini-action" type="button">+</button>
        <button class="mini-action" type="button">-</button>
        <button class="mini-action" type="button">Update Set</button>
        <button class="mini-action" type="button">View Detail</button>
        <button class="mini-action" type="button">Spec. Request</button>
        <button class="mini-action" type="button" data-open-dialog="moveSplitModal">Move to Table</button>
        <button class="mini-action" type="button" data-open-dialog="printBillingModal">Print Bill</button>
        <button class="mini-action" type="button" data-open-dialog="settleModal">Pay Card</button>
    </div>

    <div class="mt-2 grid grid-cols-2 gap-1.5 sm:grid-cols-6">
        <button class="mini-action" type="button">Print Kitchen</button>
        <button class="mini-action" type="button">Reprint Kitchen</button>
        <button class="mini-action" type="button">Print Draft</button>
        <button class="mini-action" type="button">Event Print</button>
        <button class="mini-action" type="button">C/B Bill</button>
        <button class="mini-action" type="button" data-open-dialog="settleModal">Settle</button>
    </div>
</div>
