<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CashierLoginController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('system_login')) {
            return redirect()->route('system.login');
        }

        return view('cashier.session', [
            'outlets' => config('cashier.outlets', []),
            'cashiers' => config('cashier.cashiers', []),
            'shifts' => config('cashier.shifts', ['1', '2', '3']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('cashierLogin', [
            'outlet' => ['required', 'string', 'max:100'],
            'cashier' => ['required', 'string', 'max:100'],
            'password' => ['required', 'string', 'max:255'],
            'redirect_to' => ['nullable', 'string', 'max:2048'],
            'for_waiter' => ['nullable', 'boolean'],
            'business_date' => ['nullable', 'date'],
            'shift' => ['nullable', 'string', 'max:10'],
            'opening_balance' => ['nullable', 'numeric', 'min:0'],
            'secondary_cashier' => ['nullable', 'boolean'],
            'secondary_cashier_name' => ['nullable', 'string', 'max:100'],
            'primary_cashier_name' => ['nullable', 'string', 'max:100'],
        ]);

        $cashier = Str::upper(trim($validated['cashier']));
        $outlet = trim($validated['outlet']);
        $cashierCode = $this->resolveCashierCode($cashier, $validated['primary_cashier_name'] ?? null);

        $request->session()->regenerate();
        $request->session()->put('cashier_login', [
            'cashier' => $cashierCode,
            'display_name' => $cashier,
            'outlet' => $outlet,
            'outlet_code' => $this->resolveOutletCode($outlet),
            'for_waiter' => (bool) ($validated['for_waiter'] ?? false),
            'business_date' => $validated['business_date'] ?? now()->toDateString(),
            'shift' => (string) ($validated['shift'] ?? '1'),
            'opening_balance' => (float) ($validated['opening_balance'] ?? 0),
            'secondary_cashier' => (bool) ($validated['secondary_cashier'] ?? false),
            'secondary_cashier_name' => trim((string) ($validated['secondary_cashier_name'] ?? '')),
            'primary_cashier_name' => trim((string) ($validated['primary_cashier_name'] ?? $cashier)),
            'status' => 'OPEN',
            'logged_in_at' => now()->toIso8601String(),
            'actual_opened_at' => now()->toIso8601String(),
            'actual_closed_at' => null,
            'system_user' => $request->session()->get('system_login.user_id', $cashier),
        ]);

        return redirect()->to($this->resolveRedirectTarget($request, $validated['redirect_to'] ?? null));
    }

    private function resolveOutletCode(string $outlet): string
    {
        foreach (config('cashier.outlets', []) as $item) {
            if (($item['name'] ?? null) === $outlet) {
                return (string) ($item['code'] ?? '');
            }
        }

        return '';
    }

    private function resolveCashierCode(string $cashier, ?string $primaryCashierName = null): string
    {
        $matchAgainst = Str::upper(trim((string) ($primaryCashierName ?: $cashier)));

        foreach (config('cashier.cashiers', []) as $item) {
            $code = Str::upper((string) ($item['code'] ?? ''));
            $name = Str::upper((string) ($item['name'] ?? ''));

            if ($code === $cashier || $name === $matchAgainst) {
                return $code;
            }
        }

        return $cashier;
    }

    private function resolveRedirectTarget(Request $request, ?string $target): string
    {
        $fallback = route('cashier.dashboard');

        if (! filled($target)) {
            return $fallback;
        }

        $target = trim($target);

        if (Str::startsWith($target, '/')) {
            return $target;
        }

        $parts = parse_url($target);

        if (! is_array($parts)) {
            return $fallback;
        }

        if (($parts['host'] ?? null) !== $request->getHost()) {
            return $fallback;
        }

        $path = $parts['path'] ?? '/';
        $query = isset($parts['query']) ? '?' . $parts['query'] : '';
        $fragment = isset($parts['fragment']) ? '#' . $parts['fragment'] : '';

        return $path . $query . $fragment;
    }
}
