import { useState } from 'react';

/**
 * config/forms.php の項目定義を画面側で扱うための共通処理
 */

/** 条件 ['項目名', [値...]] を満たすか */
export function matches(condition, values) {
    return !condition || condition[1].includes(values[condition[0]]);
}

export function isVisible(field, values) {
    return matches(field.when, values);
}

export function isRequired(field, values) {
    return Array.isArray(field.required) ? matches(field.required, values) : field.required;
}

/** 入力値を表示用の文字列にする（選択肢は表示名に） */
export function displayValue(field, value) {
    if (!value) return '';
    return field.type === 'select' ? field.options[value] : value;
}

/** ブラウザ側での簡易チェック（最終的な検証はサーバー側） */
export function validate(fields, values) {
    const errors = {};

    Object.entries(fields).forEach(([name, field]) => {
        if (!isVisible(field, values)) return;
        const value = (values[name] ?? '').trim();

        if (!value) {
            if (isRequired(field, values)) {
                errors[name] = field.type === 'select' || field.type === 'date' ? '選択してください。' : '入力してください。';
            }
            return;
        }
        if (field.type === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
            errors[name] = 'メールアドレスの形式が正しくありません。';
        }
        if (field.type === 'tel' && !/^[0-9０-９+\-ー－() ]{10,20}$/.test(value)) {
            errors[name] = '電話番号は数字（ハイフン可）で入力してください。';
        }
        if (field.future && value < new Date().toLocaleDateString('sv-SE')) {
            errors[name] = '本日以降の日付を選択してください。';
        }
    });

    return errors;
}

/** フォームを送信する。検証エラー時は { errors } を返す */
export function useSubmit(url) {
    const [sending, setSending] = useState(false);

    async function submit(values) {
        setSending(true);
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify(values),
            });

            if (response.status === 422) {
                const body = await response.json();
                return { errors: Object.fromEntries(Object.entries(body.errors).map(([k, v]) => [k, v[0]])) };
            }
            if (response.status === 429) {
                return { failed: '送信回数が多すぎます。少し時間をおいてから、もう一度お試しください。' };
            }
            if (!response.ok) {
                return { failed: '送信できませんでした。時間をおいて再度お試しいただくか、LINEからご連絡ください。' };
            }
            return { ok: true };
        } catch {
            return { failed: '通信エラーが発生しました。電波の良い場所で、もう一度お試しください。' };
        } finally {
            setSending(false);
        }
    }

    return { submit, sending };
}

const inputClass =
    'mt-2 block w-full rounded-sm border border-brand-300 bg-white px-4 py-3 text-base text-ink placeholder:text-brand-300 focus:border-brand-700 focus:ring-2 focus:ring-brand-700/20 focus:outline-none aria-invalid:border-alert';

/** 1項目分の入力欄 */
export function Field({ name, field, value, error, onChange, values }) {
    const id = `field-${name}`;
    const required = isRequired(field, values);
    const describedBy = [field.help && `${id}-help`, error && `${id}-error`].filter(Boolean).join(' ') || undefined;
    const common = {
        id,
        name,
        value: value ?? '',
        onChange: (e) => onChange(name, e.target.value),
        'aria-invalid': error ? true : undefined,
        'aria-describedby': describedBy,
        'aria-required': required || undefined,
        className: inputClass,
    };

    let input;
    if (field.type === 'select') {
        input = (
            <select {...common}>
                <option value="">選択してください</option>
                {Object.entries(field.options).map(([key, label]) => (
                    <option key={key} value={key} disabled={field.disabled?.includes(key)}>
                        {label}
                    </option>
                ))}
            </select>
        );
    } else if (field.type === 'textarea') {
        input = <textarea rows={6} maxLength={2000} placeholder={field.placeholder} {...common} />;
    } else {
        input = (
            <input
                type={field.type}
                placeholder={field.placeholder}
                maxLength={255}
                autoComplete={{ email: 'email', tel: 'tel' }[field.type]}
                min={field.future ? new Date().toLocaleDateString('sv-SE') : undefined}
                {...common}
            />
        );
    }

    return (
        <div>
            <label htmlFor={id} className="flex items-center gap-2 text-sm tracking-wider text-brand-800">
                {field.label}
                {required && <span className="rounded-sm bg-brand-700 px-1.5 py-0.5 text-[10px] leading-none text-white">必須</span>}
            </label>
            {field.help && (
                <p id={`${id}-help`} className="mt-1 text-xs leading-relaxed text-brand-600">
                    {field.help}
                </p>
            )}
            {input}
            {error && (
                <p id={`${id}-error`} className="mt-1.5 text-sm text-alert" role="alert">
                    {error}
                </p>
            )}
        </div>
    );
}

/** ボット対策の隠し項目（人には見えない） */
export function Honeypot({ value, onChange }) {
    return (
        <div className="absolute -left-[9999px]" aria-hidden="true">
            <label>
                ウェブサイト
                <input type="text" name="website" tabIndex={-1} autoComplete="off" value={value ?? ''} onChange={(e) => onChange('website', e.target.value)} />
            </label>
        </div>
    );
}
