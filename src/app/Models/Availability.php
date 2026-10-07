<?php

namespace App\Models;

use App\Enums\Slot;
use Database\Factories\AvailabilityFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * 撮影の空き枠（1日の午前・午後）
 *
 * @property Carbon $date
 * @property Slot $slot
 */
class Availability extends Model
{
    /** @use HasFactory<AvailabilityFactory> */
    use HasFactory;

    protected $fillable = ['date', 'slot'];

    protected function casts(): array
    {
        return [
            'slot' => Slot::class,
        ];
    }

    /**
     * 日付は時刻なし（Y-m-d）で保存する。date キャストだと SQLite などで時刻付きの文字列になり、
     * 一意制約や範囲検索が日付単位で効かなくなるため。
     */
    protected function date(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->startOfDay(),
            set: fn (Carbon|string $value) => Carbon::parse($value)->toDateString(),
        );
    }

    /**
     * @param  Builder<self>  $query
     */
    public function scopeBetween(Builder $query, Carbon $from, Carbon $to): void
    {
        $query->whereBetween('date', [$from->toDateString(), $to->toDateString()]);
    }

    /**
     * 期間内の空き枠を「日付 => [am, pm]」の形で返す。
     *
     * @return Collection<string, array<int, string>>
     */
    public static function slotsByDate(Carbon $from, Carbon $to): Collection
    {
        return self::query()
            ->between($from, $to)
            ->orderBy('date')
            ->orderBy('slot')
            ->get()
            ->groupBy(fn (self $availability) => $availability->date->toDateString())
            ->map(fn (Collection $rows) => $rows->map(fn (self $row) => $row->slot->value)->values()->all());
    }

    /**
     * 指定日の空き枠を、渡された枠だけに置き換える。
     *
     * @param  array<int, Slot>  $slots
     */
    public static function setSlots(Carbon $date, array $slots): void
    {
        self::query()->where('date', $date->toDateString())->whereNotIn('slot', $slots)->delete();

        foreach ($slots as $slot) {
            self::query()->firstOrCreate(['date' => $date->toDateString(), 'slot' => $slot]);
        }
    }
}
