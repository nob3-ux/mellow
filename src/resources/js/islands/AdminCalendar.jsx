import { useCallback, useEffect, useMemo, useRef, useState } from 'react';
import { monthDays, SLOTS, WEEKDAYS, ymd } from '../lib/calendar';
import { requestJson } from '../lib/http';

/**
 * 管理画面：空き状況の登録カレンダー
 * 日付ごとに午前・午後の「空きあり」を切り替える。押した時点で保存する。
 */
export default function AdminCalendar({ monthUrl, updateUrl, bulkUrl }) {
    const [month, setMonth] = useState(() => {
        const d = new Date();
        return new Date(d.getFullYear(), d.getMonth(), 1);
    });
    const [days, setDaysState] = useState(null);
    // 連続して押されたときも最新の状態から保存するため、描画を待たずに参照できるようにしておく
    const latest = useRef(null);
    const setDays = useCallback((next) => {
        latest.current = typeof next === 'function' ? next(latest.current) : next;
        setDaysState(latest.current);
    }, []);
    const [saving, setSaving] = useState({});
    const [message, setMessage] = useState(null);

    const monthKey = `${month.getFullYear()}-${String(month.getMonth() + 1).padStart(2, '0')}`;
    const today = ymd(new Date());

    useEffect(() => {
        setDays(null);
        requestJson(`${monthUrl}?month=${monthKey}`)
            .then((body) => setDays(body.days))
            .catch((e) => setMessage({ type: 'error', text: e.message }));
    }, [monthUrl, monthKey, setDays]);

    const notify = (type, text) => {
        setMessage({ type, text });
        if (type === 'ok') setTimeout(() => setMessage(null), 2500);
    };

    const toggle = useCallback(
        async (date, slot) => {
            const before = latest.current[date] ?? [];
            const after = before.includes(slot) ? before.filter((s) => s !== slot) : [...before, slot].sort();

            setDays((d) => ({ ...d, [date]: after }));
            setSaving((s) => ({ ...s, [`${date}-${slot}`]: true }));
            try {
                await requestJson(updateUrl.replace('__DATE__', date), { method: 'PUT', body: { slots: after } });
                notify('ok', `${date.replaceAll('-', '/')} ${SLOTS[slot]}を「${after.includes(slot) ? '空きあり' : '空きなし'}」にしました`);
            } catch (e) {
                setDays((d) => ({ ...d, [date]: before }));
                notify('error', e.message);
            } finally {
                setSaving((s) => ({ ...s, [`${date}-${slot}`]: false }));
            }
        },
        [setDays, updateUrl],
    );

    const calendar = useMemo(() => monthDays(month), [month]);

    return (
        <div className="space-y-6">
            <div className="rounded-sm bg-white p-4 shadow-sm sm:p-6">
                <div className="flex items-center justify-between">
                    <button type="button" onClick={() => setMonth(new Date(month.getFullYear(), month.getMonth() - 1, 1))} className="rounded-full px-3 py-1.5 text-sm text-brand-700 hover:bg-brand-100">
                        ← 前の月
                    </button>
                    <h2 className="text-lg tracking-widest text-brand-800">
                        {month.getFullYear()}年{month.getMonth() + 1}月
                    </h2>
                    <button type="button" onClick={() => setMonth(new Date(month.getFullYear(), month.getMonth() + 1, 1))} className="rounded-full px-3 py-1.5 text-sm text-brand-700 hover:bg-brand-100">
                        次の月 →
                    </button>
                </div>

                <div className="mt-4 grid grid-cols-7 gap-1 text-center text-xs">
                    {WEEKDAYS.map((w, i) => (
                        <div key={w} className={`py-1 ${i === 0 ? 'text-alert' : i === 6 ? 'text-sky-700' : 'text-brand-600'}`}>
                            {w}
                        </div>
                    ))}
                    {calendar.map((day, i) => {
                        if (!day) return <div key={`blank-${i}`} />;
                        const date = ymd(day);
                        const past = date < today;
                        const slots = days?.[date] ?? [];

                        return (
                            <div key={date} className={`rounded-sm border p-1 ${past ? 'border-transparent bg-brand-50 opacity-50' : 'border-brand-200'} ${date === today ? 'ring-2 ring-brand-400' : ''}`}>
                                <p className="text-left text-xs text-brand-800">{day.getDate()}</p>
                                <div className="mt-1 grid gap-1">
                                    {Object.entries(SLOTS).map(([slot, label]) => {
                                        const on = slots.includes(slot);
                                        return (
                                            <button
                                                key={slot}
                                                type="button"
                                                disabled={past || days === null || saving[`${date}-${slot}`]}
                                                onClick={() => toggle(date, slot)}
                                                aria-pressed={on}
                                                aria-label={`${day.getMonth() + 1}月${day.getDate()}日 ${label} ${on ? '空きあり' : '空きなし'}`}
                                                className={`rounded-sm px-1 py-1 text-[11px] transition sm:text-xs ${
                                                    on ? 'bg-brand-700 text-white' : 'bg-brand-100 text-brand-400 hover:bg-brand-200'
                                                } disabled:cursor-not-allowed`}
                                            >
                                                {label}
                                                <span className="hidden sm:inline">{on ? ' ○' : ' ー'}</span>
                                            </button>
                                        );
                                    })}
                                </div>
                            </div>
                        );
                    })}
                </div>

                <p className="mt-4 text-xs text-brand-600">
                    <span className="mr-1 inline-block size-3 rounded-sm bg-brand-700 align-middle" /> 空きあり（お客様のカレンダーに表示）
                    <span className="mr-1 ml-4 inline-block size-3 rounded-sm bg-brand-100 align-middle" /> 空きなし
                </p>
            </div>

            <BulkEditor month={month} monthKey={monthKey} bulkUrl={bulkUrl} onDone={(d) => setDays(d)} notify={notify} />

            <div aria-live="polite" className="fixed inset-x-0 bottom-4 flex justify-center px-4">
                {message && (
                    <p className={`rounded-full px-5 py-2.5 text-sm text-white shadow-lg ${message.type === 'error' ? 'bg-alert' : 'bg-brand-800'}`}>
                        {message.text}
                        {message.type === 'error' && (
                            <button type="button" onClick={() => setMessage(null)} className="ml-3 underline">
                                閉じる
                            </button>
                        )}
                    </p>
                )}
            </div>
        </div>
    );
}

/**
 * 表示中の月をまとめて更新する（例: 平日の午前・午後をすべて空きにする）
 */
function BulkEditor({ month, monthKey, bulkUrl, onDone, notify }) {
    const [weekdays, setWeekdays] = useState([1, 2, 3, 4, 5]);
    const [slots, setSlots] = useState(['am', 'pm']);
    const [pending, setPending] = useState(null);
    const [busy, setBusy] = useState(false);

    const flip = (list, setList, value) => setList(list.includes(value) ? list.filter((v) => v !== value) : [...list, value]);
    const label = `${month.getMonth() + 1}月の${weekdays.length === 7 ? '全日' : weekdays.map((w) => WEEKDAYS[w]).join('・')}`;

    const run = async (target) => {
        setBusy(true);
        try {
            const body = await requestJson(bulkUrl, { method: 'POST', body: { month: monthKey, weekdays, slots: target } });
            onDone(body.days);
            notify('ok', target.length ? `${label}を空きありにしました` : `${label}の空きを消しました`);
        } catch (e) {
            notify('error', e.message);
        } finally {
            setBusy(false);
            setPending(null);
        }
    };

    return (
        <section className="rounded-sm bg-white p-4 shadow-sm sm:p-6" aria-labelledby="bulk-title">
            <h2 id="bulk-title" className="tracking-widest text-brand-800">
                {month.getMonth() + 1}月をまとめて設定
            </h2>
            <p className="mt-1 text-xs text-brand-600">本日より前の日は変更されません。</p>

            <fieldset className="mt-4">
                <legend className="text-sm text-brand-800">曜日</legend>
                <div className="mt-2 flex flex-wrap gap-2">
                    {WEEKDAYS.map((w, i) => (
                        <label key={w} className="flex items-center gap-1.5 rounded-full border border-brand-200 px-3 py-1.5 text-sm has-checked:border-brand-700 has-checked:bg-brand-100">
                            <input type="checkbox" checked={weekdays.includes(i)} onChange={() => flip(weekdays, setWeekdays, i)} className="accent-brand-700" />
                            {w}
                        </label>
                    ))}
                </div>
            </fieldset>

            <fieldset className="mt-4">
                <legend className="text-sm text-brand-800">枠（「空きにする」で使用）</legend>
                <div className="mt-2 flex gap-2">
                    {Object.entries(SLOTS).map(([slot, name]) => (
                        <label key={slot} className="flex items-center gap-1.5 rounded-full border border-brand-200 px-3 py-1.5 text-sm has-checked:border-brand-700 has-checked:bg-brand-100">
                            <input type="checkbox" checked={slots.includes(slot)} onChange={() => flip(slots, setSlots, slot)} className="accent-brand-700" />
                            {name}
                        </label>
                    ))}
                </div>
            </fieldset>

            {pending === null ? (
                <div className="mt-6 flex flex-wrap gap-3">
                    <button
                        type="button"
                        disabled={!weekdays.length || !slots.length}
                        onClick={() => setPending('fill')}
                        className="rounded-full bg-brand-700 px-5 py-2.5 text-sm text-white disabled:opacity-40"
                    >
                        選んだ曜日を空きにする
                    </button>
                    <button
                        type="button"
                        disabled={!weekdays.length}
                        onClick={() => setPending('clear')}
                        className="rounded-full border border-alert px-5 py-2.5 text-sm text-alert disabled:opacity-40"
                    >
                        選んだ曜日の空きを消す
                    </button>
                </div>
            ) : (
                <div className="mt-6 rounded-sm bg-brand-50 p-4 text-sm" role="alertdialog" aria-labelledby="bulk-confirm">
                    <p id="bulk-confirm">
                        {pending === 'fill'
                            ? `${label}を「${slots.map((s) => SLOTS[s]).join('・')}空きあり」にします。個別に設定した内容は上書きされます。よろしいですか？`
                            : `${label}の空きをすべて消します。よろしいですか？`}
                    </p>
                    <div className="mt-3 flex gap-3">
                        <button
                            type="button"
                            disabled={busy}
                            onClick={() => run(pending === 'fill' ? slots : [])}
                            className={`rounded-full px-5 py-2 text-white disabled:opacity-60 ${pending === 'fill' ? 'bg-brand-700' : 'bg-alert'}`}
                        >
                            {busy ? '更新しています…' : '実行する'}
                        </button>
                        <button type="button" onClick={() => setPending(null)} className="rounded-full border border-brand-300 px-5 py-2 text-brand-700">
                            やめる
                        </button>
                    </div>
                </div>
            )}
        </section>
    );
}
