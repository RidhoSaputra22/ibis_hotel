<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CashierLoginController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('cashierLogin', [
            'outlet' => ['required', 'string', 'max:100'],
            'cashier' => ['required', 'string', 'max:100'],
            'password' => ['required', 'string', 'max:255'],
            'redirect_to' => ['nullable', 'string', 'max:2048'],
            'for_waiter' => ['nullable', 'boolean'],
        ]);

        $cashier = Str::upper(trim($validated['cashier']));
        $outlet = trim($validated['outlet']);

        $request->session()->regenerate();
        $request->session()->put('cashier_login', [
            'cashier' => $cashier,
            'display_name' => $cashier,
            'outlet' => $outlet,
            'for_waiter' => (bool) ($validated['for_waiter'] ?? false),
            'logged_in_at' => now()->toIso8601String(),
        ]);

        return redirect()->to($this->resolveRedirectTarget($request, $validated['redirect_to'] ?? null));
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
