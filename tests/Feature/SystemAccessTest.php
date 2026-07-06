<?php

namespace Tests\Feature;

use Tests\TestCase;

class SystemAccessTest extends TestCase
{
    public function test_first_visit_shows_loading_screen_and_marks_session(): void
    {
        $response = $this->get(route('system.entry'));

        $response->assertOk();
        $response->assertSee('System Boot');
        $response->assertSessionHas('system_boot.loading_seen_at');
    }

    public function test_return_visit_redirects_to_login_when_loading_was_already_seen(): void
    {
        $response = $this
            ->withSession(['system_boot.loading_seen_at' => now()->toIso8601String()])
            ->get(route('system.entry'));

        $response->assertRedirect(route('system.login'));
    }

    public function test_system_login_stores_session_and_redirects_to_cashier_setup(): void
    {
        $response = $this->post(route('system.login.store'), [
            'user_id' => 'operator_01',
            'password' => 'secret',
        ]);

        $response->assertRedirect(route('cashier.session.create'));
        $response->assertSessionHas('system_login.user_id', 'OPERATOR_01');
        $response->assertSessionHas('system_login.display_name', 'Operator 01');
    }

    public function test_legacy_cashier_only_session_is_cleared_and_sent_to_system_login(): void
    {
        $response = $this
            ->withSession([
                'system_boot.loading_seen_at' => now()->toIso8601String(),
                'cashier_login' => [
                    'cashier' => 'ADHA',
                    'display_name' => 'ADHA',
                ],
            ])
            ->get(route('system.entry'));

        $response->assertRedirect(route('system.login'));
        $response->assertSessionMissing('cashier_login');
    }

    public function test_cashier_setup_page_renders_after_system_login(): void
    {
        $response = $this
            ->withSession([
                'system_login' => [
                    'user_id' => 'OPERATOR_01',
                    'display_name' => 'Operator 01',
                    'logged_in_at' => now()->toIso8601String(),
                ],
            ])
            ->get(route('cashier.session.create'));

        $response->assertOk();
        $response->assertSee('Open Cashier');
        $response->assertSee('Cashier Session Setup');
    }

    public function test_dashboard_and_summary_render_with_active_cashier_session(): void
    {
        $session = [
            'system_login' => [
                'user_id' => 'ADHA',
                'display_name' => 'Adha',
                'logged_in_at' => now()->subMinute()->toIso8601String(),
            ],
            'cashier_login' => [
                'cashier' => 'ADHA',
                'display_name' => 'ADHA',
                'outlet' => 'Ibis Kitchen',
                'outlet_code' => '10',
                'business_date' => now()->toDateString(),
                'shift' => '1',
                'opening_balance' => 0,
                'status' => 'OPEN',
                'logged_in_at' => now()->toIso8601String(),
                'actual_opened_at' => now()->toIso8601String(),
            ],
        ];

        $this->withSession($session)
            ->get(route('cashier.dashboard'))
            ->assertOk()
            ->assertSee('FB &amp; Shop Cashier', false);

        $this->withSession($session)
            ->get(route('daily-cashier.summary'))
            ->assertOk()
            ->assertSee('Daily Cashier Summary');
    }
}
