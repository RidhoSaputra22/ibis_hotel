<?php

namespace Tests\Feature;

use Tests\TestCase;

class CashierLoginTest extends TestCase
{
    public function test_cashier_login_stores_session_and_redirects_to_requested_panel(): void
    {
        $response = $this
            ->withSession([
                'system_login' => [
                    'user_id' => 'FARHAN',
                    'display_name' => 'Farhan',
                    'logged_in_at' => now()->toIso8601String(),
                ],
            ])
            ->post(route('cashier.login.store'), [
                'outlet' => 'Banquet',
                'cashier' => 'farhan',
                'password' => 'secret',
                'business_date' => now()->toDateString(),
                'shift' => '2',
                'opening_balance' => '150000.00',
                'redirect_to' => '/restaurant-transaction',
            ]);

        $response->assertRedirect('/restaurant-transaction');
        $response->assertSessionHas('cashier_login.cashier', 'FARHAN');
        $response->assertSessionHas('cashier_login.display_name', 'FARHAN');
        $response->assertSessionHas('cashier_login.outlet', 'Banquet');
        $response->assertSessionHas('cashier_login.shift', '2');
        $response->assertSessionHas('cashier_login.status', 'OPEN');
        $response->assertSessionHas('cashier_login.for_waiter', false);
    }

    public function test_cashier_login_falls_back_when_redirect_target_is_external(): void
    {
        $response = $this
            ->withSession([
                'system_login' => [
                    'user_id' => 'ADHA',
                    'display_name' => 'Adha',
                    'logged_in_at' => now()->toIso8601String(),
                ],
            ])
            ->post(route('cashier.login.store'), [
                'outlet' => 'Ibis Kitchen',
                'cashier' => 'adha',
                'password' => 'secret',
                'redirect_to' => 'https://example.com/phishing',
            ]);

        $response->assertRedirect(route('cashier.dashboard'));
    }
}
