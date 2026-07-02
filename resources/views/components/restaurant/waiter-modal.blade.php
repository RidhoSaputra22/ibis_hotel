@props([
    'id' => 'waiterModal',
    'waiters' => ['ADHA', 'FARHAN', 'NURUL', 'RIZKY'],
])

<dialog id="{{ $id }}" class="m-auto w-[calc(100%-2rem)] max-w-sm rounded-md border border-slate-300 bg-white p-0 shadow-2xl backdrop:bg-slate-950/40">
    <x-ui.window-titlebar title="Pilih Waiter" symbol="W">
        <x-slot:actions>
            <button type="button" data-waiter-close class="grid h-5 w-5 place-items-center rounded-[2px] text-[#6b7f87] transition hover:bg-[#c9e0e7] hover:text-[#244e5f]">✕</button>
        </x-slot:actions>
    </x-ui.window-titlebar>
    <div class="space-y-2 p-4">
        @foreach ($waiters as $waiter)
            <button type="button" data-waiter="{{ $waiter }}" class="waiter-choice w-full rounded-sm border border-slate-200 px-3 py-2 text-left text-sm font-medium text-slate-600 transition hover:border-sky-300 hover:bg-sky-50 hover:text-sky-700">{{ $waiter }}</button>
        @endforeach
    </div>
    <div class="flex justify-end border-t border-slate-200 bg-slate-50 px-4 py-3">
        <button type="button" data-waiter-close class="rounded-sm border border-slate-300 bg-white px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-100">Cancel</button>
    </div>
</dialog>
