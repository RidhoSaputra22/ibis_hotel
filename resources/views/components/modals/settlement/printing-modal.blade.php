@props(['id' => 'settleModal'])

{{-- =========================
     CHILD MODAL: PRINTING
========================= --}}
<dialog
    id="{{ $id }}Printing"
    class="m-auto w-[calc(100%-2rem)] max-w-md overflow-hidden rounded-[2px] border border-[#8fa6ae] bg-[#edf4f6] p-0 shadow-2xl"
>
    <div>
        <div class="flex h-8 items-center justify-between border-b border-[#afc2c8] bg-[linear-gradient(180deg,#eaf5f8_0%,#d5e8ee_100%)] px-3">
            <span class="text-[10px] font-bold text-[#536b74]">
                Printing
            </span>

            <button
                type="button"
                id="{{ $id }}PrintingCancelTop"
                class="grid h-5 w-5 place-items-center rounded-[2px] text-[#6b7f87] hover:bg-[#c9e0e7]"
            >
                ✕
            </button>
        </div>

        <div class="min-h-[155px] px-6 py-7">
            <div class="flex items-center gap-3">
                <div class="h-6 w-6 animate-spin rounded-full border-2 border-[#84b5c5] border-t-[#2b7896]"></div>

                <div>
                    <p id="{{ $id }}PrintingStatus" class="text-sm font-semibold text-[#536b74]">
                        Sending settlement to printer...
                    </p>

                    <p class="mt-1 text-[10px] text-[#788b91]">
                        Page 1 of document
                    </p>
                </div>
            </div>
        </div>

        <div class="flex justify-center border-t border-[#c5d4d8] bg-[#edf4f6] px-4 py-3">
            <button
                type="button"
                id="{{ $id }}PrintingCancel"
                class="inline-flex h-8 min-w-[90px] items-center justify-center rounded-[2px] border border-[#b3c3c7] bg-white px-4 text-[10px] font-bold text-[#60767e] hover:bg-slate-100"
            >
                Cancel
            </button>
        </div>
    </div>
</dialog>
