<x-layouts.app
    title="Ibis Hotel Management System"
    body-class="min-h-screen overflow-hidden bg-[#030811] font-sans text-slate-100 antialiased"
>
    @push('style')
        <style>
            @keyframes ibis-loader-progress {
                from { width: 0%; }
                to { width: 100%; }
            }

            @keyframes ibis-loader-pulse {
                0%, 100% { opacity: .45; transform: scale(1); }
                50% { opacity: 1; transform: scale(1.08); }
            }

            .ibis-sky {
                background:
                    radial-gradient(circle at 20% 16%, rgba(255, 221, 168, .18), transparent 8%),
                    radial-gradient(circle at 76% 22%, rgba(255, 255, 255, .16), transparent 18%),
                    radial-gradient(circle at 68% 18%, rgba(255, 255, 255, .14), transparent 2%),
                    radial-gradient(circle at 72% 20%, rgba(255, 255, 255, .12), transparent 1.2%),
                    radial-gradient(circle at 76% 17%, rgba(255, 255, 255, .12), transparent 1.3%),
                    radial-gradient(circle at 16% 23%, rgba(255, 207, 119, .95), transparent 1.2%),
                    radial-gradient(circle at 17% 25%, rgba(233, 156, 255, .55), transparent 1.2%),
                    linear-gradient(180deg, #06090f 0%, #0b121d 38%, #090f19 100%);
            }

            .ibis-stars::before,
            .ibis-stars::after {
                content: '';
                position: absolute;
                inset: 0;
                background-image:
                    radial-gradient(circle, rgba(255,255,255,.75) 0 1px, transparent 1px),
                    radial-gradient(circle, rgba(255,255,255,.45) 0 1px, transparent 1px);
                background-position: 0 0, 22px 28px;
                background-size: 46px 46px, 58px 58px;
                opacity: .28;
            }

            .ibis-stars::after {
                opacity: .12;
                filter: blur(.35px);
            }
        </style>
    @endpush

    <main class="relative min-h-screen overflow-hidden ibis-sky">
        <div class="ibis-stars absolute inset-0"></div>
        <div class="absolute inset-x-0 bottom-0 h-56 bg-[linear-gradient(180deg,transparent_0%,rgba(1,4,8,.1)_30%,rgba(1,4,8,.95)_100%)]"></div>
        <div class="absolute bottom-0 left-0 h-40 w-72 rounded-tr-[120px] bg-[#05070c] blur-[1px]"></div>
        <div class="absolute bottom-0 right-0 h-52 w-80 rounded-tl-[180px] bg-[#05070c] blur-[1px]"></div>

        <div class="relative z-10 flex min-h-screen items-center justify-center px-6">
            <section class="w-full max-w-3xl rounded-[28px] border border-white/10 bg-black/20 p-5 shadow-[0_30px_120px_rgba(0,0,0,.45)] backdrop-blur-sm">
                <div class="mx-auto w-full max-w-2xl border border-[#cfd9d7] bg-[#f9fdf8] px-8 py-10 shadow-[0_28px_50px_rgba(3,11,19,.35)]">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="h-3 w-3 rounded-full bg-[#9e5d55]/80"></span>
                            <span class="h-3 w-3 rounded-full bg-[#94836b]/75"></span>
                            <span class="h-3 w-3 rounded-full bg-[#b3a85f]/75"></span>
                        </div>
                        <span class="text-[10px] font-semibold uppercase tracking-[0.28em] text-[#70818a]">System Boot</span>
                    </div>

                    <div class="mt-16 text-center">
                        <p class="font-serif text-[72px] italic leading-none text-[#8f3877] sm:text-[92px]">Rhapsody</p>
                        <p class="mt-2 text-lg font-semibold text-[#313e45]">ICT ONE Solution for Hospitality Management</p>
                        <p class="mt-5 text-xs uppercase tracking-[0.35em] text-[#7f8e95]">Ibis Hotel Management System</p>
                    </div>

                    <div class="mt-14">
                        <div class="flex items-center justify-between gap-4 text-[11px] text-[#5c6f77]">
                            <span id="loadingStatus">Preparing cashier workspace...</span>
                            <span id="loadingPercent" class="font-bold text-[#8f3877]">0%</span>
                        </div>
                        <div class="mt-2 h-3 overflow-hidden border border-[#b9d0d9] bg-white/90 shadow-[inset_0_1px_2px_rgba(15,23,42,.08)]">
                            <div
                                class="h-full bg-[linear-gradient(90deg,#4f87c0_0%,#7db7f1_45%,#3a70a3_100%)] shadow-[0_0_15px_rgba(59,130,246,.35)]"
                                style="animation: ibis-loader-progress {{ max(600, $delayMs) }}ms linear forwards;"
                                id="loadingBar"
                            ></div>
                        </div>
                        <p class="mt-3 text-[11px] text-[#8798a0]">
                            Downloading cashier modules, session profile, and report workspace.
                        </p>
                    </div>
                </div>
            </section>
        </div>

        <div class="absolute left-6 top-6 flex items-center gap-3 text-white/75">
            <div class="grid h-10 w-10 place-items-center rounded-xl border border-white/15 bg-white/10 text-sm font-black shadow-[0_10px_30px_rgba(0,0,0,.25)]">R</div>
            <div>
                <p class="text-sm font-semibold">Ibis Makassar City Center</p>
                <p class="text-[11px] uppercase tracking-[0.22em] text-white/45">Front Office &amp; Cashier System</p>
            </div>
        </div>

        <div class="absolute bottom-6 right-6 flex items-center gap-2 text-[11px] text-white/50">
            <span class="h-2 w-2 rounded-full bg-emerald-400" style="animation: ibis-loader-pulse 1.6s ease-in-out infinite;"></span>
            <span>Session protected. Redirecting automatically.</span>
        </div>
    </main>

    @push('scripts')
        <script>
            (() => {
                const nextUrl = @js($nextUrl);
                const delayMs = @js($delayMs);
                const status = document.getElementById('loadingStatus');
                const percent = document.getElementById('loadingPercent');
                const checkpoints = [
                    { threshold: 18, label: 'Loading operator profile...' },
                    { threshold: 42, label: 'Opening cashier modules...' },
                    { threshold: 69, label: 'Syncing local transaction storage...' },
                    { threshold: 88, label: 'Preparing report workspace...' },
                    { threshold: 100, label: 'Ready to continue...' },
                ];

                const startedAt = performance.now();
                let lastLabel = '';

                function tick(now) {
                    const progress = Math.min(100, Math.round(((now - startedAt) / delayMs) * 100));
                    percent.textContent = `${progress}%`;

                    let current = checkpoints[0];
                    checkpoints.forEach((item) => {
                        if (progress >= item.threshold) {
                            current = item;
                        }
                    });

                    if (current.label !== lastLabel) {
                        status.textContent = current.label;
                        lastLabel = current.label;
                    }

                    if (progress < 100) {
                        window.requestAnimationFrame(tick);
                    }
                }

                window.requestAnimationFrame(tick);
                window.setTimeout(() => window.location.assign(nextUrl), delayMs + 120);
            })();
        </script>
    @endpush
</x-layouts.app>
