<?php

namespace Tests\Feature;

use App\Enums\Slot;
use App\Models\Availability;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ScheduleTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_schedule_lists_slots_from_today_until_months_ahead(): void
    {
        Carbon::setTestNow('2026-10-07 10:00:00');

        Availability::factory()->createMany([
            ['date' => '2026-10-06', 'slot' => Slot::Morning],   // 過去
            ['date' => '2026-10-09', 'slot' => Slot::Afternoon],
            ['date' => '2026-10-09', 'slot' => Slot::Morning],
            ['date' => '2027-01-31', 'slot' => Slot::Morning],   // 3ヶ月先の月末まで
            ['date' => '2027-02-01', 'slot' => Slot::Morning],   // 範囲外
        ]);

        $this->getJson(route('schedule.events'))
            ->assertOk()
            ->assertExactJson(['days' => [
                '2026-10-09' => ['am', 'pm'],
                '2027-01-31' => ['am'],
            ]]);
    }
}
