@props(['id' => 'settleModal'])

{{-- =========================
     CHILD MODAL: PAYMENT LIST
========================= --}}
<dialog
    id="{{ $id }}PaymentList"
    class="m-auto w-[calc(100%-1.5rem)] max-w-3xl overflow-hidden rounded-[2px] border border-[#8fa6ae] bg-[#edf4f6] p-0 shadow-2xl"
>
    <div>
        <div class="flex h-9 items-center justify-between border-b border-[#afc2c8] bg-[linear-gradient(180deg,#eaf5f8_0%,#d5e8ee_100%)] px-3">
            <div class="flex items-center gap-1.5">
                <span class="grid h-4 w-4 place-items-center rounded-[2px] border border-[#78a3b4] bg-[#d9f0f5] text-[9px] font-black text-[#326a7e]">
                    P
                </span>

                <h2 class="text-[11px] font-bold text-[#536b74]">
                    Payment List
                </h2>
            </div>

            <button
                type="button"
                data-payment-list-close
                class="grid h-5 w-5 place-items-center rounded-[2px] text-[#6b7f87] hover:bg-[#c9e0e7]"
            >
                ✕
            </button>
        </div>

        <div class="p-3">
            <div class="overflow-auto border border-[#a9bdc4] bg-white">
                <table class="w-full border-collapse text-left text-[10px] text-[#526b74]">
                    <thead class="bg-[#dcecf0]">
                        <tr class="border-b border-[#b7cbd1]">
                            <th class="w-14 border-r border-[#c5d5d9] px-3 py-2">No.</th>
                            <th class="border-r border-[#c5d5d9] px-3 py-2">Payment Type</th>
                            <th class="w-[170px] px-3 py-2 text-right">Amount</th>
                        </tr>
                    </thead>

                    <tbody id="{{ $id }}PaymentListBody">
                        <tr>
                            <td colspan="3" class="px-3 py-10 text-center text-slate-400">
                                Belum ada pembayaran.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex justify-end border-t border-[#c5d4d8] bg-[#edf4f6] px-4 py-3">
            <button
                type="button"
                data-payment-list-close
                class="inline-flex h-8 min-w-[80px] items-center justify-center rounded-[2px] border border-[#b3c3c7] bg-white px-4 text-[10px] font-bold text-[#60767e] hover:bg-slate-100"
            >
                CLOSE
            </button>
        </div>
    </div>
</dialog>
