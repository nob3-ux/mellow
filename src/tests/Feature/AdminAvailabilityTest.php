<?php

namespace Tests\Feature;

use App\Enums\Slot;
use App\Models\Availability;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class AdminAvailabilityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow('2026-10-07 10:00:00');
    }

    public function test_guests_are_sent_to_login(): void
    {
        $this->get(route('admin.availabilities'))->assertRedirect(route('admin.login'));
        $this->putJson(route('admin.availabilities.update', '2026-10-10'), ['slots' => ['am']])->assertUnauthorized();

        $this->assertDatabaseCount('availabilities', 0);
    }

    public function test_admin_can_log_in_and_out(): void
    {
        $user = User::factory()->create(['password' => 'correct-horse-battery']);

        $this->post(route('admin.login'), ['email' => $user->email, 'password' => 'wrong'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();

        $this->post(route('admin.login'), ['email' => $user->email, 'password' => 'correct-horse-battery'])
            ->assertRedirect(route('admin.availabilities'));
        $this->assertAuthenticatedAs($user);

        $this->get(route('admin.availabilities'))->assertOk()->assertSee('noindex', false);

        $this->post(route('admin.logout'))->assertRedirect(route('admin.login'));
        $this->assertGuest();
    }

    public function test_login_is_locked_after_repeated_failures(): void
    {
        $user = User::factory()->create(['password' => 'correct-horse-battery']);

        foreach (range(1, 5) as $_) {
            $this->post(route('admin.login'), ['email' => $user->email, 'password' => 'wrong']);
        }

        $this->post(route('admin.login'), ['email' => $user->email, 'password' => 'correct-horse-battery'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_admin_can_set_slots_of_a_day(): void
    {
        $this->actingAs(User::factory()->create());

        $this->putJson(route('admin.availabilities.update', '2026-10-10'), ['slots' => ['am', 'pm']])->assertOk();
        $this->putJson(route('admin.availabilities.update', '2026-10-10'), ['slots' => ['pm']])->assertOk();

        $this->getJson(route('admin.availabilities.month', ['month' => '2026-10']))
            ->assertExactJson(['days' => ['2026-10-10' => ['pm']]]);

        $this->putJson(route('admin.availabilities.update', '2026-10-10'), ['slots' => []])->assertOk();
        $this->assertDatabaseCount('availabilities', 0);
    }

    public function test_invalid_date_and_slot_are_rejected(): void
    {
        $this->actingAs(User::factory()->create());

        $this->putJson(route('admin.availabilities.update', '2026-13-01'), ['slots' => ['am']])->assertNotFound();
        $this->putJson(route('admin.availabilities.update', '2026-10-10'), ['slots' => ['evening']])->assertUnprocessable();
    }

    public function test_bulk_update_fills_chosen_weekdays_but_not_past_days(): void
    {
        $this->actingAs(User::factory()->create());
        Availability::factory()->create(['date' => '2026-10-05', 'slot' => Slot::Afternoon]); // 過去の月曜

        $this->postJson(route('admin.availabilities.bulk'), [
            'month' => '2026-10',
            'weekdays' => [1], // 月曜
            'slots' => ['am'],
        ])->assertOk()->assertExactJson(['days' => [
            '2026-10-05' => ['pm'], // 過去は変更しない
            '2026-10-12' => ['am'],
            '2026-10-19' => ['am'],
            '2026-10-26' => ['am'],
        ]]);

        $this->postJson(route('admin.availabilities.bulk'), [
            'month' => '2026-10',
            'weekdays' => [1],
            'slots' => [],
        ])->assertOk()->assertExactJson(['days' => ['2026-10-05' => ['pm']]]);
    }
}
