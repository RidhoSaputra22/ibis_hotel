@php
    $systemLoginErrors = $errors->getBag('systemLogin');
    $buildingRows = [8, 8, 8, 8, 7, 7, 6, 6];
@endphp

<x-layouts.app title="System Login"
    body-class="min-h-screen bg-[#111214] font-sans">

      <main class="flex min-h-screen items-center justify-center px-4 py-8">
        <section
            class="relative grid w-full max-w-5xl overflow-hidden rounded-sm bg-white shadow-[0_24px_70px_rgba(0,0,0,.48)] md:grid-cols-[1.08fr_.92fr]"
        >
            {{-- Tombol Tutup --}}
            <button
                type="button"
                onclick="history.back()"
                class="absolute right-4 top-3 z-20 text-lg font-bold text-slate-500 transition hover:text-slate-900"
                aria-label="Tutup"
            >
                ×
            </button>

            {{-- Bagian gambar hotel --}}
            <div class="relative min-h-[330px] overflow-hidden md:min-h-[540px]">
                <img
                    src="{{ asset('images/ibis-hotel.jpg') }}"
                    alt="Ibis Hotel"
                    class="absolute inset-0 h-full w-full object-cover object-center"
                >

                <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-black/10"></div>

                <div class="absolute bottom-7 left-7">
                    <p class="text-lg font-semibold tracking-wide text-white drop-shadow">
                        Ibis Hotel
                    </p>
                    <p class="mt-1 text-xs text-white/80">
                        Hotel Management System
                    </p>
                </div>
            </div>

            {{-- Bagian form login --}}
            <div class="flex min-h-[420px] items-center bg-[#fbfcfb] px-8 py-12 sm:px-14 md:min-h-[540px]">
                <div class="w-full">
                    <div class="mb-10">
                        <h1 class="text-2xl font-light tracking-wide text-slate-700">
                            Welcome Back
                        </h1>

                        <p class="mt-2 text-sm text-slate-400">
                            Login untuk mengakses sistem hotel.
                        </p>
                    </div>

                    @if ($errors->any())
                        <div class="mb-5 rounded-sm border border-rose-200 bg-rose-50 px-4 py-3 text-xs text-rose-700">
                            <ul class="list-disc space-y-1 pl-4">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="#" class="space-y-5">
                        @csrf

                        {{-- User ID --}}
                        <div>
                            <label
                                for="user_id"
                                class="mb-2 block text-xs font-medium text-slate-500"
                            >
                                User ID
                            </label>

                            <input
                                id="user_id"
                                name="user_id"
                                type="text"
                                value="{{ old('user_id') }}"
                                required
                                autofocus
                                autocomplete="username"
                                class="h-10 w-full border border-slate-400 bg-white px-3 text-sm text-slate-700 outline-none transition focus:border-[#8b2e73] focus:ring-2 focus:ring-[#8b2e73]/15"
                            >
                        </div>

                        {{-- Password --}}
                        <div>
                            <label
                                for="password"
                                class="mb-2 block text-xs font-medium text-slate-500"
                            >
                                Password
                            </label>

                            <input
                                id="password"
                                name="password"
                                type="password"
                                required
                                autocomplete="current-password"
                                class="h-10 w-full border border-slate-400 bg-white px-3 text-sm text-slate-700 outline-none transition focus:border-[#8b2e73] focus:ring-2 focus:ring-[#8b2e73]/15"
                            >
                        </div>

                        <div class="flex items-center justify-between pt-1">
                            <label class="flex cursor-pointer items-center gap-2 text-xs text-slate-500">
                                <input
                                    type="checkbox"
                                    name="remember"
                                    class="h-3.5 w-3.5 rounded border-slate-300 text-[#8b2e73] focus:ring-[#8b2e73]"
                                >
                                Remember me
                            </label>

                            <button
                                type="submit"
                                class="min-w-[92px] bg-[#8f2d74] px-6 py-2.5 text-xs font-semibold text-white shadow-sm transition hover:bg-[#74245e] focus:outline-none focus:ring-4 focus:ring-[#8f2d74]/20"
                            >
                                Login
                            </button>
                        </div>
                    </form>

                    <p class="mt-14 text-center text-xs text-slate-400">
                        www.realta.co.id
                    </p>
                </div>
            </div>
        </section>
    </main>

    @push('scripts')
        <script>
            (() => {
                const passwordInput = document.getElementById('systemPassword');
                const toggle = document.getElementById('toggleSystemPassword');

                toggle?.addEventListener('click', () => {
                    const showPassword = passwordInput.type === 'password';
                    passwordInput.type = showPassword ? 'text' : 'password';
                    toggle.setAttribute('aria-label', showPassword ? 'Sembunyikan password' : 'Tampilkan password');
                    toggle.innerHTML = showPassword ?
                        '<i data-lucide="eye-off" class="h-4 w-4"></i>' :
                        '<i data-lucide="eye" class="h-4 w-4"></i>';
                    window.lucide?.createIcons({
                        attrs: {
                            'stroke-width': 1.8
                        }
                    });
                });
            })();
        </script>
    @endpush
</x-layouts.app>
