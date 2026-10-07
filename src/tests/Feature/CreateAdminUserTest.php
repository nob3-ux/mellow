<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CreateAdminUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_account_is_created_and_password_can_be_changed(): void
    {
        $this->artisan('admin:create', ['email' => 'staff@example.com'])
            ->expectsQuestion('パスワード（12文字以上）', 'first-password-123')
            ->assertSuccessful();

        $this->artisan('admin:create', ['email' => 'staff@example.com'])
            ->expectsQuestion('パスワード（12文字以上）', 'second-password-456')
            ->expectsOutputToContain('パスワードを変更しました')
            ->assertSuccessful();

        $this->assertDatabaseCount('users', 1);
        $this->assertTrue(Hash::check('second-password-456', User::firstWhere('email', 'staff@example.com')->password));
    }

    public function test_short_password_is_rejected(): void
    {
        $this->artisan('admin:create', ['email' => 'staff@example.com'])
            ->expectsQuestion('パスワード（12文字以上）', 'short')
            ->assertFailed();

        $this->assertDatabaseCount('users', 0);
    }
}
