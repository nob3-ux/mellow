import { useState } from 'react';
import { Field, Honeypot, useSubmit, validate } from '../lib/forms';

/**
 * お問い合わせフォーム
 */
export default function InquiryForm({ fields, submitUrl }) {
    const [values, setValues] = useState({});
    const [errors, setErrors] = useState({});
    const [failed, setFailed] = useState(null);
    const [done, setDone] = useState(false);
    const { submit, sending } = useSubmit(submitUrl);

    const onChange = (name, value) => {
        setValues((v) => ({ ...v, [name]: value }));
        setErrors((e) => ({ ...e, [name]: undefined }));
    };

    const onSubmit = async (e) => {
        e.preventDefault();
        setFailed(null);

        const clientErrors = validate(fields, values);
        setErrors(clientErrors);
        if (Object.keys(clientErrors).length) {
            document.getElementById(`field-${Object.keys(clientErrors)[0]}`)?.focus();
            return;
        }

        const result = await submit(values);
        if (result.ok) {
            setDone(true);
        } else if (result.errors) {
            setErrors(result.errors);
        } else {
            setFailed(result.failed);
        }
    };

    if (done) {
        return (
            <div className="rounded-sm bg-white p-8 text-center sm:p-12" role="status">
                <p className="font-display text-lg tracking-[0.3em] text-brand-500 uppercase italic">Thank you</p>
                <h2 className="mt-3 text-xl tracking-[0.12em] text-brand-800">お問い合わせを受け付けました</h2>
                <p className="mt-6 text-sm leading-loose">内容を確認のうえ、ご連絡いたします。今しばらくお待ちくださいませ。</p>
            </div>
        );
    }

    return (
        <form noValidate onSubmit={onSubmit} className="relative space-y-7 rounded-sm bg-white p-6 sm:p-10">
            {failed && (
                <p className="rounded-sm border-l-4 border-alert bg-[#fbf3f1] p-4 text-sm text-alert" role="alert">
                    {failed}
                </p>
            )}
            {Object.entries(fields).map(([name, field]) => (
                <Field key={name} name={name} field={field} value={values[name]} error={errors[name]} onChange={onChange} values={values} />
            ))}
            <Honeypot value={values.website} onChange={onChange} />
            <div className="text-center">
                <button
                    type="submit"
                    disabled={sending}
                    className="rounded-full bg-brand-700 px-12 py-3.5 text-sm tracking-widest text-white transition hover:bg-brand-800 disabled:opacity-60"
                >
                    {sending ? '送信しています…' : '送信する'}
                </button>
            </div>
        </form>
    );
}
