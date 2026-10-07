import { useEffect, useMemo, useState } from 'react';
import { monthDays, SLOTS, WEEKDAYS, ymd } from '../lib/calendar';

/**
 * 空き状況カレンダー（お客様向け）。空きのある日を選ぶと、その日を第一希望日にして予約フォームへ進める。
 * monthsAhead: 何ヶ月先まで表示するか（ご予約はご希望日の3ヶ月前から）
 */
export default function ScheduleCalendar({ eventsUrl, reservationUrl, lineUrl, monthsAhead = 3 }) {
    const [days, setDays] = useState(null);
    const [failed, setFailed] = useState(false);
    const [month, setMonth] = useState(() => {
        const d = new Date();
        return new Date(d.getFullYear(), d.getMonth(), 1);
    });
    const [selected, setSelected] = useState(null);

    useEffect(() => {
        fetch(eventsUrl, { headers: { Accept: 'application/json' } })
            .then((r) => (r.ok ? r.json() : Promise.reject()))
            .then((body) => setDays(body.days))
            .catch(() => setFailed(true));
    }, [eventsUrl]);

    const calendar = useMemo(() => monthDays(month), [month]);

    const today = ymd(new Date());
    const thisMonth = new Date(new Date().getFullYear(), new Date().getMonth(), 1);
    const lastMonth = new Date(thisMonth.getFullYear(), thisMonth.getMonth() + monthsAhead, 1);
    const shift = (n) => {
        setMonth(new Date(month.getFullYear(), month.getMonth() + n, 1));
        setSelected(null);
    };
    const slotLabel = (slots) => slots.map((s) => `${SLOTS[s]}空きあり`).join('／');

    if (failed) {
        return (
            <p className="rounded-sm bg-white p-6 text-center text-sm">
                空き状況を読み込めませんでした。お手数ですが
                <a href={lineUrl} className="text-brand-700 underline underline-offset-4">
                    LINE
                </a>
                でお問い合わせください。
            </p>
        );
    }

    return (
        <div className="rounded-sm bg-white p-4 sm:p-8">
            <div className="flex items-center justify-between">
                <button
                    type="button"
                    onClick={() => shift(-1)}
                    disabled={month <= thisMonth}
                    className="rounded-full p-2 text-brand-700 disabled:opacity-30"
                    aria-label="前の月"
                >
                    <svg className="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.5" aria-hidden="true">
                        <path strokeLinecap="round" strokeLinejoin="round" d="M15 5l-7 7 7 7" />
                    </svg>
                </button>
                <h2 className="text-lg tracking-[0.12em] text-brand-800" aria-live="polite">
                    {month.getFullYear()}年{month.getMonth() + 1}月
                </h2>
                <button
                    type="button"
                    onClick={() => shift(1)}
                    disabled={month >= lastMonth}
                    className="rounded-full p-2 text-brand-700 disabled:opacity-30"
                    aria-label="次の月"
                >
                    <svg className="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.5" aria-hidden="true">
                        <path strokeLinecap="round" strokeLinejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>

            <div className="mt-4 grid grid-cols-7 text-center text-xs tracking-wider">
                {WEEKDAYS.map((w, i) => (
                    <div key={w} className={`py-2 ${i === 0 ? 'text-alert' : i === 6 ? 'text-sky-700' : 'text-brand-600'}`}>
                        {w}
                    </div>
                ))}
            </div>

            <div className="grid grid-cols-7 gap-1" aria-busy={days === null}>
                {calendar.map((day, i) => {
                    if (!day) return <div key={`blank-${i}`} />;
                    const key = ymd(day);
                    const slots = key < today ? [] : (days?.[key] ?? []);
                    const available = slots.length > 0;

                    return (
                        <button
                            key={key}
                            type="button"
                            disabled={!available}
                            onClick={() => setSelected(key)}
                            aria-pressed={selected === key}
                            aria-label={`${day.getMonth() + 1}月${day.getDate()}日 ${available ? slotLabel(slots) : '空きなし'}`}
                            className={`flex min-h-16 flex-col items-center rounded-sm border p-1 text-sm transition sm:min-h-20 ${
                                selected === key
                                    ? 'border-brand-700 bg-brand-700 text-white'
                                    : available
                                      ? 'border-brand-300 bg-brand-50 hover:border-brand-700'
                                      : 'border-transparent text-brand-300'
                            }`}
                        >
                            <span>{day.getDate()}</span>
                            {available && (
                                <span className="mt-1 flex flex-col gap-0.5 text-[10px] leading-tight sm:text-[11px]">
                                    {slots.map((s) => (
                                        <span key={s}>
                                            <span className="hidden sm:inline">○</span>
                                            {SLOTS[s]}
                                        </span>
                                    ))}
                                </span>
                            )}
                        </button>
                    );
                })}
            </div>

            {days === null && <p className="mt-4 text-center text-sm text-brand-600">空き状況を読み込んでいます…</p>}

            <div className="mt-6 min-h-24 rounded-sm bg-brand-50 p-5 text-center text-sm" aria-live="polite">
                {selected ? (
                    <>
                        <p className="text-brand-800">
                            {selected.replaceAll('-', '/')}：{slotLabel(days[selected])}
                        </p>
                        <div className="mt-4 flex flex-col justify-center gap-3 sm:flex-row">
                            <a href={`${reservationUrl}?date_first=${selected}`} className="rounded-full bg-brand-700 px-8 py-3 tracking-widest text-white">
                                この日で予約フォームへ
                            </a>
                            <a href={lineUrl} target="_blank" rel="noopener" className="rounded-full bg-line px-8 py-3 tracking-widest text-white">
                                LINEで予約する
                            </a>
                        </div>
                    </>
                ) : (
                    <p className="text-brand-700">枠の線がある日に空きがあります。日付を選ぶと、その日で予約に進めます。</p>
                )}
            </div>
        </div>
    );
}
