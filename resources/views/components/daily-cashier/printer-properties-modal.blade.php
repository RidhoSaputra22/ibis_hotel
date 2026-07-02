<dialog id="printerPropertiesModal" class="m-auto w-[calc(100%-2rem)] max-w-md overflow-hidden rounded-sm border border-[#8fa6ae] bg-[#edf4f6] p-0 shadow-2xl backdrop:bg-slate-900/40">
    <x-ui.window-titlebar title="Printer Properties" symbol="P">
        <x-slot:actions><button type="button" id="closePrinterPropertiesTop" class="grid h-5 w-5 place-items-center rounded-[2px] text-[#6b7f87] hover:bg-[#c9e0e7]">✕</button></x-slot:actions>
    </x-ui.window-titlebar>
    <div class="space-y-4 p-5">
        <x-forms.legacy-field label="Printer" label-class="form-label"><x-forms.legacy-select class="form-control" :options="['EPSON TM-U220 Slip', 'EPSON TM-T82 Receipt', 'Microsoft Print to PDF']" selected="EPSON TM-U220 Slip" /></x-forms.legacy-field>
        <div class="grid grid-cols-2 gap-3">
            <x-forms.legacy-field label="Paper Size" label-class="form-label"><x-forms.legacy-select class="form-control" :options="['Slip', 'A4']" selected="Slip" /></x-forms.legacy-field>
            <x-forms.legacy-field label="Copies" label-class="form-label"><x-forms.legacy-input class="form-control" type="number" min="1" value="1" /></x-forms.legacy-field>
        </div>
    </div>
    <div class="flex justify-end gap-2 border-t border-[#c5d4d8] bg-[#edf4f6] px-4 py-3">
        <button type="button" id="closePrinterProperties" class="small-button">Cancel</button>
        <button type="button" id="savePrinterProperties" class="small-button !border-[#79aebf] !bg-[#d8edf3] !text-[#3e6c7c]">Save</button>
    </div>
</dialog>
