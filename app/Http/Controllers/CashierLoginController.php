<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CashierLoginController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('system_login')) {
            return redirect()->route('system.login');
        }

        $currentSession = (array) $request->session()->get('cashier_login', []);
        $history = $this->normalizeHistory($request->session()->get('cashier_history', []));

        if ($currentSession !== []) {
            $history = $this->upsertHistoryEntry($history, $currentSession);
            $request->session()->put('cashier_history', $history);
        }

        return view('cashier.session', [
            'shifts' => config('cashier.shifts', ['1', '2', '3']),
            'cashierHistory' => $this->formatHistoryForView($history),
            'cashierSession' => $currentSession,
            'systemLogin' => (array) $request->session()->get('system_login', []),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('cashierLogin', [
            'action' => ['nullable', Rule::in(['open', 'save', 'close'])],
            'outlet' => ['nullable', 'string', 'max:100'],
            'cashier' => ['required', 'string', 'max:100'],
            'password' => ['required', 'string', 'max:255'],
            'business_date' => ['nullable', 'date'],
            'shift' => ['nullable', 'string', Rule::in(config('cashier.shifts', ['1', '2', '3']))],
            'currency' => ['nullable', 'string', 'max:10'],
            'opening_balance' => ['nullable', 'numeric', 'min:0'],
            'secondary_cashier' => ['nullable', 'boolean'],
            'primary_cashier_name' => [
                'nullable',
                'string',
                'max:100',
                Rule::requiredIf(fn () => $request->boolean('secondary_cashier')),
            ],
            'primary_password' => [
                'nullable',
                'string',
                'max:255',
                Rule::requiredIf(fn () => $request->boolean('secondary_cashier')),
            ],
            'status' => ['nullable', Rule::in(['OPEN', 'CLOSED'])],
            'redirect_to' => ['nullable', 'string', 'max:2048'],
        ]);

        $action = Str::lower((string) ($validated['action'] ?? 'open'));
        $currentSession = (array) $request->session()->get('cashier_login', []);

        if ($action === 'close' && $currentSession === []) {
            return back()
                ->withErrors(['cashier' => 'Tidak ada cashier yang sedang terbuka untuk ditutup.'], 'cashierLogin')
                ->withInput();
        }

        $systemLogin = (array) $request->session()->get('system_login', []);
        $systemUser = Str::upper(trim((string) Arr::get($systemLogin, 'user_id', $validated['cashier'])));
        $cashierInput = Str::upper(trim((string) $validated['cashier']));
        $cashierProfile = $this->resolveCashierProfile(
            $cashierInput,
            (string) Arr::get($systemLogin, 'display_name', '')
        );
        $businessDate = (string) ($validated['business_date'] ?? now()->toDateString());
        $shift = (string) ($validated['shift'] ?? '1');
        $isSecondaryCashier = (bool) ($validated['secondary_cashier'] ?? false);

        if ($action === 'close' && $currentSession !== []) {
            $cashierProfile = [
                'code' => (string) Arr::get($currentSession, 'cashier', $cashierProfile['code']),
                'name' => (string) Arr::get($currentSession, 'cashier_name', Arr::get($currentSession, 'display_name', $cashierProfile['name'])),
            ];
            $businessDate = (string) Arr::get($currentSession, 'business_date', $businessDate);
            $shift = (string) Arr::get($currentSession, 'shift', $shift);
        }

        $outlet = $action === 'close'
            ? (string) Arr::get($currentSession, 'outlet', $this->resolveCurrentOutletName($request))
            : $this->resolveSubmittedOutletName($request, $validated);
        $status = $action === 'close' ? 'CLOSED' : 'OPEN';
        $isSameSession = $this->isSameSession($currentSession, $cashierProfile['code'], $businessDate, $shift);
        $resolvedSecondaryCashier = $action === 'close'
            ? (bool) Arr::get($currentSession, 'secondary_cashier', $isSecondaryCashier)
            : $isSecondaryCashier;
        $resolvedCurrency = $action === 'close'
            ? (string) Arr::get($currentSession, 'currency', Str::upper(trim((string) ($validated['currency'] ?? 'IDR'))))
            : Str::upper(trim((string) ($validated['currency'] ?? 'IDR')));
        $resolvedOpeningBalance = $action === 'close'
            ? (float) Arr::get($currentSession, 'opening_balance', $validated['opening_balance'] ?? 0)
            : (float) ($validated['opening_balance'] ?? 0);
        $resolvedSecondaryCashierName = $action === 'close'
            ? trim((string) Arr::get($currentSession, 'secondary_cashier_name', ''))
            : ($resolvedSecondaryCashier
                ? trim((string) Arr::get($systemLogin, 'display_name', $cashierProfile['name']))
                : '');
        $resolvedPrimaryCashierName = $action === 'close'
            ? trim((string) Arr::get($currentSession, 'primary_cashier_name', $cashierProfile['name']))
            : ($resolvedSecondaryCashier
                ? trim((string) $validated['primary_cashier_name'])
                : $cashierProfile['name']);
        $resolvedSystemUser = $action === 'close'
            ? (string) Arr::get($currentSession, 'system_user', $systemUser)
            : $systemUser;
        $actualOpenedAt = $isSameSession
            ? (string) Arr::get($currentSession, 'actual_opened_at', now()->toIso8601String())
            : now()->toIso8601String();
        $loggedInAt = $isSameSession
            ? (string) Arr::get($currentSession, 'logged_in_at', $actualOpenedAt)
            : now()->toIso8601String();

        $payload = [
            'cashier' => $cashierProfile['code'],
            'display_name' => $cashierProfile['code'],
            'cashier_name' => $cashierProfile['name'],
            'outlet' => $outlet,
            'outlet_code' => $this->resolveOutletCode($outlet),
            'for_waiter' => (bool) Arr::get($currentSession, 'for_waiter', false),
            'business_date' => $businessDate,
            'shift' => $shift,
            'currency' => $resolvedCurrency,
            'opening_balance' => $resolvedOpeningBalance,
            'secondary_cashier' => $resolvedSecondaryCashier,
            'secondary_cashier_name' => $resolvedSecondaryCashierName,
            'primary_cashier_name' => $resolvedPrimaryCashierName,
            'status' => $status,
            'logged_in_at' => $loggedInAt,
            'actual_opened_at' => $actualOpenedAt,
            'actual_closed_at' => $action === 'close' ? now()->toIso8601String() : null,
            'system_user' => $resolvedSystemUser,
        ];

        $history = $this->normalizeHistory($request->session()->get('cashier_history', []));
        $history = $this->upsertHistoryEntry($history, $payload);
        $request->session()->put('cashier_history', $history);

        if ($action === 'close') {
            $request->session()->forget('cashier_login');

            return redirect()
                ->route('cashier.session.create')
                ->with('cashierSessionMessage', 'Cashier session closed.');
        }

        $request->session()->put('cashier_login', $payload);

        if ($action === 'save') {
            return redirect()
                ->route('cashier.session.create')
                ->with('cashierSessionMessage', 'Cashier session updated.');
        }

        return redirect()
            ->to($this->resolveRedirectTarget($request, $validated['redirect_to'] ?? null))
            ->with('cashierSessionMessage', 'Cashier session opened.');
    }

    private function resolveCurrentOutletName(Request $request): string
    {
        $currentOutlet = trim((string) $request->session()->get('cashier_login.outlet', ''));

        if ($currentOutlet !== '') {
            return $currentOutlet;
        }

        foreach (config('cashier.outlets', []) as $item) {
            $name = trim((string) ($item['name'] ?? ''));

            if ($name !== '') {
                return $name;
            }
        }

        return 'Ibis Kitchen';
    }

    private function resolveSubmittedOutletName(Request $request, array $validated): string
    {
        $submittedOutlet = trim((string) ($validated['outlet'] ?? ''));

        if ($submittedOutlet !== '') {
            return $submittedOutlet;
        }

        return $this->resolveCurrentOutletName($request);
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

    private function resolveCashierProfile(string $cashier, string $fallbackName = ''): array
    {
        $normalizedCashier = Str::upper(trim($cashier));

        foreach (config('cashier.cashiers', []) as $item) {
            $code = Str::upper(trim((string) ($item['code'] ?? '')));
            $name = trim((string) ($item['name'] ?? ''));
            $normalizedName = Str::upper($name);

            if ($code === '') {
                continue;
            }

            if ($code === $normalizedCashier || $normalizedName === $normalizedCashier || Str::endsWith($normalizedCashier, $code)) {
                return [
                    'code' => $code,
                    'name' => $name !== '' ? $name : $this->humanizeCashierName($normalizedCashier),
                ];
            }
        }

        return [
            'code' => $normalizedCashier,
            'name' => trim($fallbackName) !== '' ? trim($fallbackName) : $this->humanizeCashierName($normalizedCashier),
        ];
    }

    private function humanizeCashierName(string $value): string
    {
        return Str::title(Str::of($value)->replace(['.', '_', '-'], ' ')->value());
    }

    private function isSameSession(array $currentSession, string $cashierCode, string $businessDate, string $shift): bool
    {
        return (string) Arr::get($currentSession, 'cashier') === $cashierCode
            && (string) Arr::get($currentSession, 'business_date') === $businessDate
            && (string) Arr::get($currentSession, 'shift') === $shift;
    }

    private function normalizeHistory(mixed $history): array
    {
        if (! is_array($history)) {
            return [];
        }

        return array_values(array_filter($history, fn ($item) => is_array($item) && filled($item['logged_in_at'] ?? null)));
    }

    private function upsertHistoryEntry(array $history, array $entry): array
    {
        $updated = false;

        foreach ($history as $index => $item) {
            if (($item['logged_in_at'] ?? null) === ($entry['logged_in_at'] ?? null)) {
                $history[$index] = $entry;
                $updated = true;
                break;
            }
        }

        if (! $updated) {
            $history[] = $entry;
        }

        usort($history, function (array $left, array $right): int {
            return strcmp(
                (string) ($right['actual_opened_at'] ?? $right['logged_in_at'] ?? ''),
                (string) ($left['actual_opened_at'] ?? $left['logged_in_at'] ?? '')
            );
        });

        return array_values($history);
    }

    private function formatHistoryForView(array $history): array
    {
        return array_map(function (array $entry): array {
            $secondaryLabel = $this->resolveSecondaryLabel($entry);

            return [
                'logged_in_at' => (string) ($entry['logged_in_at'] ?? ''),
                'date' => $this->formatDate($entry['business_date'] ?? null),
                'date_value' => (string) ($entry['business_date'] ?? ''),
                'shift' => (string) ($entry['shift'] ?? '1'),
                'status' => Str::upper((string) ($entry['status'] ?? 'OPEN')),
                'secondary' => $secondaryLabel,
                'secondary_active' => (bool) ($entry['secondary_cashier'] ?? false),
                'user' => (string) ($entry['system_user'] ?? $entry['cashier'] ?? ''),
                'user_name' => (string) ($entry['cashier_name'] ?? $entry['display_name'] ?? ''),
                'cashier_code' => (string) ($entry['cashier'] ?? ''),
                'primary_cashier_name' => (string) ($entry['primary_cashier_name'] ?? ''),
                'currency' => (string) ($entry['currency'] ?? 'IDR'),
                'opening_balance' => $this->formatAmount($entry['opening_balance'] ?? 0),
                'open_time' => $this->formatTime($entry['actual_opened_at'] ?? null),
                'actual_open' => $this->formatDateTime($entry['actual_opened_at'] ?? null),
                'actual_close' => $this->formatDateTime($entry['actual_closed_at'] ?? null),
            ];
        }, $history);
    }

    private function resolveSecondaryLabel(array $entry): string
    {
        if (! (bool) ($entry['secondary_cashier'] ?? false)) {
            return '-';
        }

        $secondary = trim((string) ($entry['secondary_cashier_name'] ?? ''));
        $primary = trim((string) ($entry['primary_cashier_name'] ?? ''));

        return $secondary !== '' ? $secondary : ($primary !== '' ? $primary : 'Yes');
    }

    private function formatDate(string|null $value): string
    {
        try {
            return Carbon::parse($value)->format('d-M-Y');
        } catch (\Throwable) {
            return '';
        }
    }

    private function formatTime(string|null $value): string
    {
        try {
            return Carbon::parse($value)->format('H:i');
        } catch (\Throwable) {
            return '';
        }
    }

    private function formatDateTime(string|null $value): string
    {
        if (! filled($value)) {
            return '';
        }

        try {
            return Carbon::parse($value)->format('d-M-Y H:i');
        } catch (\Throwable) {
            return '';
        }
    }

    private function formatAmount(float|int|string|null $value): string
    {
        $formatted = number_format((float) $value, 2, '.', '');

        return rtrim(rtrim($formatted, '0'), '.');
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
