@props(['id' => 'settleModal', 'creditCards' => []])

{{-- =========================
     CHILD MODAL: CARD LOOKUP
========================= --}}
<dialog
    id="{{ $id }}CardLookup"
    class="m-auto w-[calc(100%-1.5rem)] max-w-4xl overflow-hidden rounded-[2px] border border-[#8fa6ae] bg-[#edf4f6] p-0 shadow-2xl"
>
    <div>
        <div class="flex h-9 items-center justify-between border-b border-[#afc2c8] bg-[linear-gradient(180deg,#eaf5f8_0%,#d5e8ee_100%)] px-3">
            <div class="flex items-center gap-1.5">
                <span class="grid h-4 w-4 place-items-center rounded-[2px] border border-[#78a3b4] bg-[#d9f0f5] text-[9px] font-black text-[#326a7e]">
                    C
                </span>

                <h2 class="text-[11px] font-bold text-[#536b74]">
                    Card Lookup
                </h2>
            </div>

            <button
                type="button"
                data-card-lookup-close
                class="grid h-5 w-5 place-items-center rounded-[2px] text-[#6b7f87] hover:bg-[#c9e0e7]"
            >
                ✕
            </button>
        </div>

        <div class="p-3">
            <div class="max-h-[420px] overflow-auto border border-[#a9bdc4] bg-white">
                <table class="w-full border-collapse text-left text-[10px] text-[#526b74]">
                    <thead class="sticky top-0 bg-[#dcecf0]">
                        <tr class="border-b border-[#b7cbd1]">
                            <th class="w-8 border-r border-[#c5d5d9] px-1 py-2"></th>
                            <th class="w-[180px] border-r border-[#c5d5d9] px-3 py-2 font-bold">Card Code</th>
                            <th class="px-3 py-2 font-bold">Card Name</th>
                        </tr>
                    </thead>

                    <tbody id="{{ $id }}CardLookupBody">
                        @foreach ($creditCards as $index => $card)
                            <tr
                                data-card-lookup-row
                                data-card-code="{{ $card['code'] }}"
                                data-card-name="{{ $card['name'] }}"
                                class="cursor-pointer border-b border-[#d7e3e6] transition hover:bg-[#e7f3f6] {{ $index === 0 ? 'bg-[#f6d69a]' : 'bg-white' }}"
                            >
                                <td class="border-r border-[#e0e9eb] px-1 py-2 text-center">
                                    <span data-card-arrow class="{{ $index === 0 ? '' : 'invisible' }}">◆</span>
                                </td>

                                <td class="border-r border-[#e0e9eb] px-3 py-2 font-medium">
                                    {{ $card['code'] }}
                                </td>

                                <td class="px-3 py-2">
                                    {{ $card['name'] }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex justify-end gap-2 border-t border-[#c5d4d8] bg-[#edf4f6] px-4 py-3">
            <button
                type="button"
                id="{{ $id }}CardLookupOk"
                class="inline-flex h-8 min-w-[80px] items-center justify-center rounded-[2px] border border-[#89acb8] bg-[#e2f3f7] px-4 text-[10px] font-bold text-[#3d6e7e] hover:bg-[#d0eaf1]"
            >
                OK
            </button>

            <button
                type="button"
                data-card-lookup-close
                class="inline-flex h-8 min-w-[80px] items-center justify-center rounded-[2px] border border-[#b3c3c7] bg-white px-4 text-[10px] font-bold text-[#60767e] hover:bg-slate-100"
            >
                CANCEL
            </button>
        </div>
    </div>
</dialog>
