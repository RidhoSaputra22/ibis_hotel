@props([
    'outlets' => [],
    'cashiers' => [],
])

<aside class="border-b border-slate-200 bg-[#f3f8f9] p-5 lg:border-b-0 lg:border-r">
    <div class="space-y-4">
        <div class="grid grid-cols-[70px_1fr] items-center gap-3">
            <label for="summaryDate" class="form-label">Date</label>
            <input id="summaryDate" type="date" value="2026-06-15" class="form-control max-w-[170px]">
        </div>
        <div class="grid grid-cols-[70px_1fr] items-start gap-3">
            <span class="form-label pt-1">Group by</span>
            <div class="flex flex-wrap items-center gap-4">
                <label class="radio-label"><input type="radio" name="groupBy" value="outlet" checked> Outlet</label>
                <label class="radio-label"><input type="radio" name="groupBy" value="cashier"> Cashier</label>
                <label class="radio-label"><input id="salesOnly" type="checkbox"> Sales Only</label>
            </div>
        </div>
    </div>

    <fieldset id="outletFieldset" class="fieldset-panel mt-5">
        <legend>Outlet</legend>
        <div class="grid grid-cols-[50px_1fr] items-center gap-x-3 gap-y-3">
            <label class="form-label">From</label>
            <div class="grid grid-cols-[70px_1fr] gap-1.5">
                <x-forms.legacy-select id="outletFrom" class="form-control" :options="collect($outlets)->pluck('code', 'code')->all()" selected="10" />
                <x-forms.legacy-input id="outletFromName" value="Ibis Kitchen" readonly class="form-control bg-[#dfe8eb]" />
            </div>
            <label class="form-label">To</label>
            <div class="grid grid-cols-[70px_1fr] gap-1.5">
                <x-forms.legacy-select id="outletTo" class="form-control" :options="collect($outlets)->pluck('code', 'code')->all()" selected="50" />
                <x-forms.legacy-input id="outletToName" value="Pool Bar" readonly class="form-control bg-[#dfe8eb]" />
            </div>
        </div>
    </fieldset>

    <fieldset id="cashierFieldset" class="fieldset-panel mt-4 opacity-60">
        <legend>Cashier</legend>
        <div class="grid grid-cols-[50px_1fr] items-center gap-x-3 gap-y-3">
            <label class="form-label">From</label>
            <div class="grid grid-cols-[110px_32px_1fr] gap-1.5">
                <x-forms.legacy-select id="cashierFrom" disabled class="form-control" :options="collect($cashiers)->pluck('code', 'code')->all()" selected="ADHA" />
                <button type="button" data-open-dialog="cashierLookupModal" data-cashier-target="from" class="mini-button" aria-label="Cari cashier dari"><i data-lucide="search" class="h-3 w-3"></i></button>
                <x-forms.legacy-input id="cashierFromName" value="Adha" readonly class="form-control bg-[#dfe8eb]" />
            </div>
            <label class="form-label">To</label>
            <div class="grid grid-cols-[110px_32px_1fr] gap-1.5">
                <x-forms.legacy-select id="cashierTo" disabled class="form-control" :options="collect($cashiers)->pluck('code', 'code')->all()" selected="RIZKY" />
                <button type="button" data-open-dialog="cashierLookupModal" data-cashier-target="to" class="mini-button" aria-label="Cari cashier tujuan"><i data-lucide="search" class="h-3 w-3"></i></button>
                <x-forms.legacy-input id="cashierToName" value="Rizky" readonly class="form-control bg-[#dfe8eb]" />
            </div>
        </div>
    </fieldset>

    <fieldset class="fieldset-panel mt-4">
        <legend>Output To</legend>
        <div class="flex flex-wrap items-center gap-3">
            <label class="radio-label"><input type="radio" name="outputTo" value="screen" checked> Screen</label>
            <label class="radio-label"><input type="radio" name="outputTo" value="printer"> Printer</label>
            <input id="printerName" type="text" value="EPSON TM-U220 Slip" disabled class="form-control h-8 max-w-[190px] bg-[#dfe8eb] text-[10px]">
            <button id="printerPropertiesButton" type="button" data-open-dialog="printerPropertiesModal" disabled class="small-button disabled:cursor-not-allowed disabled:opacity-50">Properties</button>
        </div>
    </fieldset>

    <div class="mt-5 flex justify-end">
        <button id="printSummaryButton" type="button" class="flex h-[68px] w-[105px] flex-col items-center justify-center gap-1 rounded-sm border border-[#79aebf] bg-[#d8edf3] text-[11px] font-bold text-[#3e6c7c] shadow-[inset_0_1px_0_rgba(255,255,255,.9)] transition hover:border-[#4e99b5] hover:bg-[#c7e6ef]">
            <i data-lucide="printer" class="h-5 w-5"></i>
            Print
        </button>
    </div>
</aside>
