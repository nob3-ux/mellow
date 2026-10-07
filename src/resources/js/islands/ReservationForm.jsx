import { useMemo, useRef, useState } from 'react';
import { displayValue, Field, Honeypot, isVisible, useSubmit, validate } from '../lib/forms';

/**
 * ご予約フォーム（ステップ形式）
 * URLのクエリ（?plan=shichigosan&shichigosan_plan=studio_50 など）を初期値として読み込む。
 */
export default function ReservationForm({ fields, steps, submitUrl, lineUrl, cancelPolicyUrl, flowUrl }) {
    const [values, setValues] = useState(() => {
        const query = Object.fromEntries(new URLSearchParams(window.location.search));
        return Object.fromEntries(Object.keys(fields).filter((k) => query[k]).map((k) => [k, query[k]]));
    });
    const [errors, setErrors] = useState({});
    const [step, setStep] = useState(1);
    const [failed, setFailed] = useState(null);
    const [done, setDone] = useState(false);
    const { submit, sending } = useSubmit(submitUrl);
    const top = useRef(null);

    const visible = useMemo(
        () => Object.fromEntries(Object.entries(fields).filter(([, f]) => isVisible(f, values))),
        [fields, values],
    );
    const fieldsOf = (n) => Object.fromEntries(Object.entries(visible).filter(([, f]) => f.step === n));

    const onChange = (name, value) => {
        setValues((v) => ({ ...v, [name]: value }));
        setErrors((e) => ({ ...e, [name]: undefined }));
    };

    const scrollTop = () => top.current?.scrollIntoView({ behavior: 'smooth', block: 'start' });

    const next = (e) => {
        e.preventDefault();
        const stepErrors = validate(fieldsOf(step), values);
        setErrors(stepErrors);
        if (Object.keys(stepErrors).length) {
            document.getElementById(`field-${Object.keys(stepErrors)[0]}`)?.focus();
            return;
        }
        setStep(step + 1);
        scrollTop();
    };

    const back = () => {
        setStep(step - 1);
        scrollTop();
    };

    const send = async () => {
        setFailed(null);
        const payload = Object.fromEntries(Object.keys(visible).map((k) => [k, values[k] ?? '']));
        const result = await submit({ ...payload, website: values.website ?? '' });

        if (result.ok) {
            setDone(true);
            scrollTop();
        } else if (result.errors) {
            setErrors(result.errors);
            const first = Object.keys(result.errors)[0];
            setStep(fields[first]?.step ?? 1);
            setFailed('入力内容をご確認ください。');
            scrollTop();
        } else {
            setFailed(result.failed);
        }
    };

    if (done) {
        return (
            <div ref={top} className="rounded-sm bg-white p-8 text-center sm:p-12" role="status">
                <p className="font-display text-lg tracking-[0.3em] text-brand-500 uppercase italic">Thank you</p>
                <h2 className="mt-3 text-xl tracking-[0.12em] text-brand-800">ご予約のお申し込みを受け付けました</h2>
                <div className="mx-auto mt-6 max-w-lg space-y-3 text-left text-sm leading-loose">
                    <p>ご記入ありがとうございました。スタジオの空き状況を確認のうえ、ご来店日の決定についてご連絡いたします。こちらからの返信をお待ちくださいませ。</p>
                    <p>ご予約決定後、撮影の詳細メールをお送りします。その後、1週間以内の事前入金をもって予約確定となります。</p>
                </div>
                <div className="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
                    <a href={flowUrl} className="rounded-full border border-brand-700 px-6 py-3 text-sm tracking-widest text-brand-700">
                        ご予約の流れを見る
                    </a>
                    <a href={lineUrl} target="_blank" rel="noopener" className="rounded-full bg-line px-6 py-3 text-sm tracking-widest text-white">
                        LINEでも相談する
                    </a>
                </div>
            </div>
        );
    }

    return (
        <div ref={top} className="scroll-mt-24">
            <ol className="mb-8 grid grid-cols-4 gap-2 text-center text-[11px] tracking-wider sm:text-xs" aria-label="入力の進み具合">
                {steps.map((label, i) => {
                    const n = i + 1;
                    return (
                        <li key={label} aria-current={n === step ? 'step' : undefined}>
                            <span
                                className={`mx-auto flex size-8 items-center justify-center rounded-full font-display text-base ${
                                    n <= step ? 'bg-brand-700 text-white' : 'bg-brand-200 text-brand-700'
                                }`}
                            >
                                {n}
                            </span>
                            <span className={`mt-1.5 block ${n === step ? 'text-brand-800' : 'text-brand-500'}`}>{label}</span>
                        </li>
                    );
                })}
            </ol>

            {failed && (
                <p className="mb-6 rounded-sm border-l-4 border-alert bg-[#fbf3f1] p-4 text-sm text-alert" role="alert">
                    {failed}
                </p>
            )}

            {step < steps.length ? (
                <form noValidate onSubmit={next} className="relative space-y-7 rounded-sm bg-white p-6 sm:p-10">
                    <h2 className="text-lg tracking-[0.12em] text-brand-800">{steps[step - 1]}</h2>
                    {step === 1 && values.plan === 'shichigosan' && (
                        <p className="rounded-sm bg-brand-50 p-4 text-xs leading-relaxed">
                            七五三撮影をご希望のお客様は、下記の項目をすべてお選びください。料金の詳細は七五三プランのページをご覧ください。
                        </p>
                    )}
                    {Object.entries(fieldsOf(step)).map(([name, field]) => (
                        <Field key={name} name={name} field={field} value={values[name]} error={errors[name]} onChange={onChange} values={values} />
                    ))}
                    <Honeypot value={values.website} onChange={onChange} />

                    <div className="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:justify-between">
                        {step > 1 ? (
                            <button type="button" onClick={back} className="rounded-full border border-brand-300 px-6 py-3 text-sm tracking-widest text-brand-700">
                                戻る
                            </button>
                        ) : (
                            <span />
                        )}
                        <button type="submit" className="rounded-full bg-brand-700 px-10 py-3.5 text-sm tracking-widest text-white transition hover:bg-brand-800">
                            {step === steps.length - 1 ? '入力内容を確認する' : '次へ進む'}
                        </button>
                    </div>
                </form>
            ) : (
                <div className="rounded-sm bg-white p-6 sm:p-10">
                    <h2 className="text-lg tracking-[0.12em] text-brand-800">内容の確認</h2>
                    <p className="mt-2 text-sm">以下の内容でお申し込みします。よろしければ「この内容で申し込む」を押してください。</p>

                    <dl className="mt-6 divide-y divide-brand-100 border-y border-brand-100 text-sm">
                        {Object.entries(visible).map(([name, field]) => (
                            <div key={name} className="grid gap-1 py-3 sm:grid-cols-[14rem_1fr] sm:gap-4">
                                <dt className="text-brand-600">{field.label}</dt>
                                <dd className="break-words whitespace-pre-wrap">{displayValue(field, values[name]) || '（未入力）'}</dd>
                            </div>
                        ))}
                    </dl>

                    <p className="mt-6 text-xs leading-relaxed text-brand-700">
                        日程変更およびキャンセルについては、
                        <a href={cancelPolicyUrl} target="_blank" className="underline underline-offset-4">
                            キャンセルポリシー
                        </a>
                        をご確認ください。スタジオ運営上、日程変更をお願いする場合もございます。
                    </p>

                    <div className="mt-8 flex flex-col-reverse gap-3 sm:flex-row sm:justify-between">
                        <button type="button" onClick={back} className="rounded-full border border-brand-300 px-6 py-3 text-sm tracking-widest text-brand-700">
                            修正する
                        </button>
                        <button
                            type="button"
                            onClick={send}
                            disabled={sending}
                            className="rounded-full bg-brand-700 px-10 py-3.5 text-sm tracking-widest text-white transition hover:bg-brand-800 disabled:opacity-60"
                        >
                            {sending ? '送信しています…' : 'この内容で申し込む'}
                        </button>
                    </div>
                </div>
            )}
        </div>
    );
}
