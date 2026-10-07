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
        $this->artisan('admin:create', ['username' => 'staff'])
            ->expectsQuestion('パスワード（8文字以上）', 'first-pass')
            ->assertSuccessful();

        $this->artisan('admin:create', ['username' => 'staff'])
            ->expectsQuestion('パスワード（8文字以上）', 'second-pass')
            ->expectsOutputToContain('パスワードを変更しました')
            ->assertSuccessful();

        $this->assertDatabaseCount('users', 1);
        $this->assertTrue(Hash::check('second-pass', User::firstWhere('username', 'staff')->password));
    }

    public function test_created_account_can_log_in_with_username(): void
    {
        $this->artisan('admin:create', ['username' => 'staff'])
            ->expectsQuestion('パスワード（8文字以上）', 'first-pass')
            ->assertSuccessful();

        $this->post(route('admin.login'), ['username' => 'staff', 'password' => 'first-pass'])
            ->assertRedirect(route('admin.availabilities'));
        $this->assertAuthenticated();
    }

    public function test_short_password_and_invalid_username_are_rejected(): void
    {
        $this->artisan('admin:create', ['username' => 'staff'])
            ->expectsQuestion('パスワード（8文字以上）', 'short')
            ->assertFailed();

        $this->artisan('admin:create', ['username' => 'まりこ'])
            ->expectsQuestion('パスワード（8文字以上）', 'long-enough-password')
            ->assertFailed();

        $this->assertDatabaseCount('users', 0);
    }
}
