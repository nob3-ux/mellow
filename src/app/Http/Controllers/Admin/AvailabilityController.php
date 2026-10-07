<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Slot;
use App\Http\Controllers\Controller;
use App\Models\Availability;
use Carbon\CarbonPeriod;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class AvailabilityController extends Controller
{
    /**
     * 空き状況の管理画面
     */
    public function index(): View
    {
        return view('admin.availabilities');
    }

    /**
     * 指定月（?month=2026-10）の空き枠
     */
    public function month(Request $request): JsonResponse
    {
        $month = $this->monthFromRequest($request);

        return response()->json([
            'days' => Availability::slotsByDate($month->copy()->startOfMonth(), $month->copy()->endOfMonth()),
        ]);
    }

    /**
     * 1日分の空き枠を更新する（slots に含めた枠だけ「空きあり」になる）
     */
    public function update(Request $request, string $date): JsonResponse
    {
        $validated = $request->validate([
            'slots' => ['present', 'array'],
            'slots.*' => [Rule::enum(Slot::class)],
        ]);

        // 2026-13-01 のような存在しない日付は繰り上がるため、書式が一致するかで判定する
        $day = rescue(fn () => Carbon::createFromFormat('!Y-m-d', $date), report: false);
        abort_if($day?->format('Y-m-d') !== $date, 404);

        Availability::setSlots($day, array_map(Slot::from(...), $validated['slots']));

        return response()->json(['date' => $day->toDateString(), 'slots' => $validated['slots']]);
    }

    /**
     * 月の指定曜日をまとめて更新する（過去の日は対象外）
     */
    public function bulk(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'month' => ['required', 'date_format:Y-m'],
            'weekdays' => ['required', 'array', 'min:1'],
            'weekdays.*' => ['integer', 'between:0,6'],
            'slots' => ['present', 'array'],
            'slots.*' => [Rule::enum(Slot::class)],
        ]);

        $month = Carbon::createFromFormat('!Y-m', $validated['month']);
        $slots = array_map(Slot::from(...), $validated['slots']);

        foreach (CarbonPeriod::create($month->copy()->startOfMonth(), $month->copy()->endOfMonth()) as $day) {
            if ($day->isBefore(today()) || ! in_array($day->dayOfWeek, $validated['weekdays'])) {
                continue;
            }
            Availability::setSlots($day, $slots);
        }

        return response()->json([
            'days' => Availability::slotsByDate($month->copy()->startOfMonth(), $month->copy()->endOfMonth()),
        ]);
    }

    private function monthFromRequest(Request $request): Carbon
    {
        $request->validate(['month' => ['required', 'date_format:Y-m']]);

        return Carbon::createFromFormat('!Y-m', $request->query('month'));
    }
}
