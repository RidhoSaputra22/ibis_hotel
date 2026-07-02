@props(['id' => 'settleModal'])

{{-- =========================
     CHILD MODAL: KONFIRMASI PRINT
========================= --}}
<dialog
    id="{{ $id }}ConfirmPrint"
    class="m-auto w-[calc(100%-2rem)] max-w-sm overflow-hidden rounded-[2px] border border-[#8fa6ae] bg-[#edf4f6] p-0 shadow-2xl"
>
    <div>
        <div class="flex h-8 items-center justify-between border-b border-[#afc2c8] bg-[linear-gradient(180deg,#eaf5f8_0%,#d5e8ee_100%)] px-3">
            <span class="text-[10px] font-bold text-[#536b74]">
                Confirmation
            </span>

            <button
                type="button"
                data-confirm-print-close
                class="grid h-5 w-5 place-items-center rounded-[2px] text-[#6b7f87] hover:bg-[#c9e0e7]"
            >
                ✕
            </button>
        </div>

        <div class="px-5 py-7 text-center">
            <p class="text-sm font-medium text-[#536b74]">
                Do You want to print settlement?
            </p>
        </div>

        <div class="flex justify-center gap-2 border-t border-[#c5d4d8] bg-[#edf4f6] px-4 py-3">
            <button
                type="button"
                id="{{ $id }}ConfirmPrintYes"
                class="inline-flex h-8 min-w-[80px] items-center justify-center rounded-[2px] border border-[#89acb8] bg-[#e2f3f7] px-4 text-[10px] font-bold text-[#3d6e7e] hover:bg-[#d0eaf1]"
            >
                Yes
            </button>

            <button
                type="button"
                id="{{ $id }}ConfirmPrintNo"
                class="inline-flex h-8 min-w-[80px] items-center justify-center rounded-[2px] border border-[#b3c3c7] bg-white px-4 text-[10px] font-bold text-[#60767e] hover:bg-slate-100"
            >
                No
            </button>
        </div>
    </div>
</dialog>
