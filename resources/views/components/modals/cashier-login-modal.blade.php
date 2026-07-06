{{-- resources/views/components/cashier-login-modal.blade.php --}}
@props([
    'id' => 'cashierLoginModal',
    'cashier' => null,
    'outlets' => collect(config('cashier.outlets', []))->pluck('name')->all(),
    'action' => route('cashier.login.store'),
])

@php
    $cashierLoginErrors = $errors->getBag('cashierLogin');
    $resolvedCashier = old('cashier', $cashier ?? session('cashier_login.display_name', session('system_login.user_id', 'ADHA')));
    $selectedOutlet = old('outlet', session('cashier_login.outlet', $outlets[0] ?? null));
    $redirectTarget = old('redirect_to', url()->current());
@endphp

<style>
    #{{ $id }}::backdrop {
        background: rgba(2, 12, 27, 0.72);
        backdrop-filter: blur(3px);
    }
</style>

<dialog
    id="{{ $id }}"
    class="m-auto w-[calc(100%_-_2rem)] max-w-4xl overflow-hidden rounded-lg border-0 bg-transparent p-0 shadow-none"
    aria-labelledby="{{ $id }}Title"
>
    <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-2xl">
        {{-- Header modal --}}
        <div class="flex items-center justify-between border-b border-slate-200 bg-slate-50 px-5 py-3">
            <div class="flex items-center gap-2">
                <div class="grid h-7 w-7 place-items-center rounded bg-sky-600 text-xs font-black text-white">R</div>
                <h2 id="{{ $id }}Title" class="text-sm font-bold text-slate-700">Cashier Login</h2>
            </div>

            <button
                type="button"
                data-close-modal
                class="grid h-8 w-8 place-items-center rounded text-slate-500 transition hover:bg-slate-200 hover:text-slate-800"
                aria-label="Tutup modal"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 6 6 18M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form method="POST" action="{{ $action }}" class="border-l-8 border-sky-500">
            @csrf
            <input type="hidden" name="redirect_to" value="{{ $redirectTarget }}" data-login-redirect-input>

            <div class="grid grid-cols-1 lg:grid-cols-[1.12fr_.88fr]">
                {{-- Kolom pemilihan outlet --}}
                <section class="border-b border-slate-200 p-6 lg:border-b-0 lg:border-r">
                    <label for="{{ $id }}Outlet" class="mb-3 block text-sm font-semibold text-slate-700">Outlet</label>

                    <select
                        id="{{ $id }}Outlet"
                        name="outlet"
                        size="7"
                        required
                        class="h-60 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-100"
                    >
                        @foreach ($outlets as $index => $outlet)
                            <option value="{{ $outlet }}" @selected($selectedOutlet === $outlet || ($selectedOutlet === null && $index === 0))>{{ $outlet }}</option>
                        @endforeach
                    </select>

                    <p class="mt-3 text-xs leading-relaxed text-slate-500">
                        Pilih outlet tempat kasir akan melakukan transaksi.
                    </p>

                    @if ($cashierLoginErrors->has('outlet'))
                        <p class="mt-2 text-xs font-medium text-rose-600">{{ $cashierLoginErrors->first('outlet') }}</p>
                    @endif
                </section>

                {{-- Kolom autentikasi kasir --}}
                <section class="p-6">
                    <div class="space-y-4">
                        @if ($cashierLoginErrors->any())
                            <div class="rounded-md border border-rose-200 bg-rose-50 px-3 py-2 text-xs text-rose-700">
                                {{ $cashierLoginErrors->first() }}
                            </div>
                        @endif

                        <div class="grid gap-2 sm:grid-cols-[100px_1fr] sm:items-center">
                            <label for="{{ $id }}Cashier" class="text-sm font-semibold text-slate-700">Cashier</label>
                            <input
                                id="{{ $id }}Cashier"
                                name="cashier"
                                type="text"
                                value="{{ $resolvedCashier }}"
                                required
                                autocomplete="username"
                                placeholder="Masukkan nama atau ID kasir"
                                class="h-10 rounded-md border border-slate-300 bg-white px-3 text-sm font-medium text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:ring-4 focus:ring-sky-100"
                            >
                        </div>

                        @if ($cashierLoginErrors->has('cashier'))
                            <p class="-mt-2 text-xs font-medium text-rose-600 sm:ml-[100px]">{{ $cashierLoginErrors->first('cashier') }}</p>
                        @endif

                        <div class="grid gap-2 sm:grid-cols-[100px_1fr] sm:items-center">
                            <label for="{{ $id }}Password" class="text-sm font-semibold text-slate-700">Password</label>

                            <div class="relative">
                                <input
                                    id="{{ $id }}Password"
                                    data-password-input
                                    name="password"
                                    type="password"
                                    required
                                    autocomplete="current-password"
                                    placeholder="Masukkan password"
                                    class="h-10 w-full rounded-md border border-slate-300 bg-white py-2 pl-3 pr-11 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:ring-4 focus:ring-sky-100"
                                >
                                <button
                                    type="button"
                                    data-toggle-password
                                    class="absolute inset-y-0 right-0 grid w-10 place-items-center text-slate-400 transition hover:text-sky-600"
                                    aria-label="Tampilkan password"
                                >
                                    <svg data-eye-open xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M2.062 12.348a1 1 0 0 1 0-.696C3.422 7.496 7.36 5 12 5c4.639 0 8.578 2.496 9.938 6.652a1 1 0 0 1 0 .696C20.578 16.504 16.64 19 12 19c-4.639 0-8.578-2.496-9.938-6.652Z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    <svg data-eye-closed xmlns="http://www.w3.org/2000/svg" class="hidden h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="m3 3 18 18"/>
                                        <path d="M10.584 10.587a2 2 0 0 0 2.828 2.829"/>
                                        <path d="M9.363 5.365A9.466 9.466 0 0 1 12 5c4.639 0 8.578 2.496 9.938 6.652a1 1 0 0 1 0 .696 10.059 10.059 0 0 1-4.304 5.055"/>
                                        <path d="M6.228 6.228A10.058 10.058 0 0 0 2.062 11.65a1 1 0 0 0 0 .696C3.422 16.504 7.36 19 12 19c1.734 0 3.37-.348 4.856-.978"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        @if ($cashierLoginErrors->has('password'))
                            <p class="-mt-2 text-xs font-medium text-rose-600 sm:ml-[100px]">{{ $cashierLoginErrors->first('password') }}</p>
                        @endif

                        <label class="flex cursor-pointer items-center gap-2 text-sm text-slate-600 sm:ml-[100px]">
                            <input
                                type="checkbox"
                                name="for_waiter"
                                value="1"
                                @checked(old('for_waiter', session('cashier_login.for_waiter', false)))
                                class="h-4 w-4 rounded border-slate-300 text-sky-600 focus:ring-sky-500"
                            >
                            <span>For Waiter</span>
                        </label>

                        <div class="flex justify-end gap-2 border-t border-slate-100 pt-5">
                            <button
                                type="button"
                                data-close-modal
                                class="rounded-md border border-slate-300 bg-white px-5 py-2 text-xs font-bold text-slate-600 shadow-sm transition hover:bg-slate-100"
                            >
                                CANCEL
                            </button>

                            <button
                                type="submit"
                                class="rounded-md bg-sky-600 px-5 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-sky-700 focus:outline-none focus:ring-4 focus:ring-sky-200"
                            >
                                LOGIN
                            </button>
                        </div>
                    </div>
                </section>
            </div>
        </form>
    </div>
</dialog>

<script>
    (() => {
        const modal = document.getElementById(@js($id));
        if (!modal) return;

        const passwordInput = modal.querySelector('[data-password-input]');
        const togglePassword = modal.querySelector('[data-toggle-password]');
        const eyeOpen = modal.querySelector('[data-eye-open]');
        const eyeClosed = modal.querySelector('[data-eye-closed]');

        modal.querySelectorAll('[data-close-modal]').forEach((button) => {
            button.addEventListener('click', () => modal.close());
        });

        togglePassword?.addEventListener('click', () => {
            const isHidden = passwordInput.type === 'password';
            passwordInput.type = isHidden ? 'text' : 'password';
            eyeOpen.classList.toggle('hidden', isHidden);
            eyeClosed.classList.toggle('hidden', !isHidden);
            togglePassword.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Tampilkan password');
        });

        modal.addEventListener('click', (event) => {
            if (event.target === modal) modal.close();
        });

        if (@js($cashierLoginErrors->any()) && !modal.open && typeof modal.showModal === 'function') {
            modal.showModal();
        }
    })();
</script>
