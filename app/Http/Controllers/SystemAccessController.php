<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SystemAccessController extends Controller
{
    public function entry(Request $request): View|RedirectResponse
    {
        $this->normalizeLegacySession($request);

        if ($this->shouldShowLoading($request)) {
            $request->session()->put('system_boot.loading_seen_at', now()->toIso8601String());

            return view('system.loading', [
                'nextUrl' => $this->resolveNextUrl($request),
                'delayMs' => (int) config('cashier.loading_delay_ms', 2800),
            ]);
        }

        return redirect()->to($this->resolveNextUrl($request));
    }

    public function create(Request $request): View|RedirectResponse
    {
        $this->normalizeLegacySession($request);

        if ($request->session()->has('system_login') && $request->session()->has('cashier_login')) {
            return redirect()->route('cashier.dashboard');
        }

        if ($request->session()->has('system_login')) {
            return redirect()->route('cashier.session.create');
        }

        return view('system.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('systemLogin', [
            'user_id' => ['required', 'string', 'max:100'],
            'password' => ['required', 'string', 'max:255'],
        ]);

        $userId = Str::upper(trim($validated['user_id']));

        $request->session()->regenerate();
        $request->session()->put('system_login', [
            'user_id' => $userId,
            'display_name' => Str::title(Str::of($userId)->replace(['.', '_', '-'], ' ')->value()),
            'logged_in_at' => now()->toIso8601String(),
        ]);

        return redirect()->route('cashier.session.create');
    }

    private function normalizeLegacySession(Request $request): void
    {
        if (! $request->session()->has('system_login') && $request->session()->has('cashier_login')) {
            $request->session()->forget('cashier_login');
        }
    }

    private function shouldShowLoading(Request $request): bool
    {
        $seenAt = $request->session()->get('system_boot.loading_seen_at');

        if (! filled($seenAt)) {
            return true;
        }

        try {
            $ttlMinutes = max(1, (int) config('cashier.loader_ttl_minutes', 120));

            return Carbon::parse($seenAt)->addMinutes($ttlMinutes)->isPast();
        } catch (\Throwable) {
            return true;
        }
    }

    private function resolveNextUrl(Request $request): string
    {
        if (! $request->session()->has('system_login')) {
            return route('system.login');
        }

        if (! $request->session()->has('cashier_login')) {
            return route('cashier.session.create');
        }

        return route('cashier.dashboard');
    }
}
