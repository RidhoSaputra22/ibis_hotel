@props([
    'tableListUrl' => '/table-list',
])

<div class="flex flex-wrap items-center gap-1.5 border-b border-slate-200 bg-[#eff5f6] px-3 py-2">
    <button type="button" id="addOrderButton" class="toolbar-button"><i data-lucide="plus" class="h-3.5 w-3.5"></i> Add</button>
    <button type="button" class="toolbar-button"><i data-lucide="pencil" class="h-3.5 w-3.5"></i> Edit</button>
    <button type="button" id="clearOrderButton" class="toolbar-button"><i data-lucide="trash-2" class="h-3.5 w-3.5"></i> Delete</button>
    <button type="button" class="toolbar-button"><i data-lucide="rotate-cw" class="h-3.5 w-3.5"></i> Refresh</button>
    <a href="{{ $tableListUrl }}" class="toolbar-button"><i data-lucide="table-properties" class="h-3.5 w-3.5"></i> Table List</a>
    <button type="button" class="toolbar-button"><i data-lucide="log-in" class="h-3.5 w-3.5"></i> Check In</button>
    <button type="button" class="toolbar-button"><i data-lucide="log-out" class="h-3.5 w-3.5"></i> Check Out</button>
    <button type="button" class="toolbar-button" data-open-dialog="printBillingModal"><i data-lucide="receipt-text" class="h-3.5 w-3.5"></i> Bill</button>
    <button type="button" id="openWaiterButton" class="toolbar-button"><i data-lucide="user-round" class="h-3.5 w-3.5"></i> Waiter</button>

    <span class="mx-1 hidden h-6 border-l border-slate-300 sm:block"></span>

    <button type="button" id="saveOrderButton" class="toolbar-button !border-emerald-300 !bg-emerald-50 !text-emerald-700 hover:!bg-emerald-100"><i data-lucide="save" class="h-3.5 w-3.5"></i> Save</button>
    <button type="button" id="cancelOrderButton" class="toolbar-button !border-rose-300 !bg-rose-50 !text-rose-700 hover:!bg-rose-100"><i data-lucide="x" class="h-3.5 w-3.5"></i> Cancel</button>

    <div class="ml-auto hidden items-center gap-2 text-[10px] text-slate-500 xl:flex">
        <span class="font-semibold text-slate-700">Ibis Kitchen</span>
        <span class="rounded border border-slate-300 bg-white px-2 py-1">ADHA · 15 Juni 2026</span>
    </div>
</div>
