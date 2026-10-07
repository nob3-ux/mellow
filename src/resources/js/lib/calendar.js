/**
 * カレンダー表示の共通処理
 */

/** Date を 2026-10-07 形式に */
export const ymd = (date) => date.toLocaleDateString('sv-SE');

/** 指定月のカレンダー用の日付配列（月初の曜日までは null で埋める） */
export function monthDays(month) {
    const count = new Date(month.getFullYear(), month.getMonth() + 1, 0).getDate();
    return [
        ...Array(month.getDay()).fill(null),
        ...Array.from({ length: count }, (_, i) => new Date(month.getFullYear(), month.getMonth(), i + 1)),
    ];
}

export const WEEKDAYS = ['日', '月', '火', '水', '木', '金', '土'];

export const SLOTS = { am: '午前', pm: '午後' };
