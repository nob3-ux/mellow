<?php

namespace Database\Seeders;

use App\Enums\Slot;
use App\Models\Availability;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * 初期データを投入する。管理者アカウントは `php artisan admin:create` で作成する。
     */
    public function run(): void
    {
        // 旧サイト（Googleカレンダー）に登録されていた空き状況
        $initial = json_decode(file_get_contents(database_path('data/availabilities.json')), true);

        foreach ($initial as $date => $slots) {
            Availability::setSlots(Carbon::parse($date), array_map(Slot::from(...), $slots));
        }
    }
}
