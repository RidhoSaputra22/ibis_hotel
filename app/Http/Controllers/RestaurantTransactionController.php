<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RestaurantTransactionController extends Controller
{
    public function tableList(Request $request): View
    {
        return view('restaurant-transaction.table-list', [
            'tables' => $this->availableTables(),
            'selectedTable' => $this->resolveSelectedTable($request),
        ]);
    }

    public function transaction(Request $request): View|RedirectResponse
    {
        $selectedTable = $this->normalizeTable($request->query('table'));

        if (! $this->isValidTable($selectedTable)) {
            $fallbackSelection = $this->resolveSelectedTable($request);

            return redirect()
                ->route('restaurant.table-list', array_filter([
                    'selected_table' => $fallbackSelection,
                ]));
        }

        $request->session()->put('restaurant_transaction.selected_table', $selectedTable);

        return view('restaurant-transaction.transaction', [
            'selectedTable' => $selectedTable,
        ]);
    }

    private function availableTables(): array
    {
        return collect(range(1, 20))
            ->map(fn (int $number) => str_pad((string) $number, 2, '0', STR_PAD_LEFT))
            ->all();
    }

    private function resolveSelectedTable(Request $request): ?string
    {
        $selectedTable = $this->normalizeTable(
            $request->query('selected_table', $request->session()->get('restaurant_transaction.selected_table'))
        );

        return $this->isValidTable($selectedTable) ? $selectedTable : null;
    }

    private function isValidTable(?string $table): bool
    {
        return $table !== null && in_array($table, $this->availableTables(), true);
    }

    private function normalizeTable(mixed $value): ?string
    {
        $normalized = trim((string) $value);

        if ($normalized === '' || ! ctype_digit($normalized)) {
            return null;
        }

        return str_pad($normalized, 2, '0', STR_PAD_LEFT);
    }
}
