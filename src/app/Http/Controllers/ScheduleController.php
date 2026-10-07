<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use Illuminate\Http\JsonResponse;

class ScheduleController extends Controller
{
    /**
     * 公開する空き状況（本日から予約受付期間の末日まで）。
     */
    public function __invoke(): JsonResponse
    {
        $from = today();
        $to = today()->addMonthsNoOverflow(config('site.schedule_months_ahead'))->endOfMonth();

        return response()->json([
            'days' => Availability::slotsByDate($from, $to),
        ]);
    }
}
