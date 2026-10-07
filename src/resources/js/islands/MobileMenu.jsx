import { useEffect, useRef, useState } from 'react';
import { createPortal } from 'react-dom';

/**
 * スマートフォン用のメニュー（右からスライドするドロワー）
 */
export default function MobileMenu({ navigation, reservationUrl, lineUrl, instagramUrl }) {
    const [open, setOpen] = useState(false);
    const closeButton = useRef(null);

    useEffect(() => {
        if (!open) return;

        document.body.style.overflow = 'hidden';
        closeButton.current?.focus();
        const onKey = (e) => e.key === 'Escape' && setOpen(false);
        window.addEventListener('keydown', onKey);

        return () => {
            document.body.style.overflow = '';
            window.removeEventListener('keydown', onKey);
        };
    }, [open]);

    return (
        <>
            <button
                type="button"
                className="p-2 text-brand-800"
                aria-label="メニューを開く"
                aria-expanded={open}
                aria-controls="mobile-menu"
                onClick={() => setOpen(true)}
            >
                <svg className="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.5" aria-hidden="true">
                    <path strokeLinecap="round" d="M4 7h16M4 12h16M4 17h16" />
                </svg>
            </button>

            {createPortal(
                <div
                    id="mobile-menu"
                    role="dialog"
                    aria-modal="true"
                    aria-label="メニュー"
                    className={`fixed inset-0 z-50 lg:hidden ${open ? '' : 'pointer-events-none'}`}
                >
                    <div
                        className={`absolute inset-0 bg-brand-900/40 transition-opacity duration-300 ${open ? 'opacity-100' : 'opacity-0'}`}
                        onClick={() => setOpen(false)}
                        aria-hidden="true"
                    />
                    <div
                        className={`absolute inset-y-0 right-0 flex w-[85%] max-w-sm flex-col overflow-y-auto bg-brand-50 shadow-xl transition-transform duration-300 ${
                            open ? 'translate-x-0' : 'translate-x-full'
                        }`}
                    >
                        <div className="flex justify-end p-3">
                            <button
                                ref={closeButton}
                                type="button"
                                className="p-2 text-brand-800"
                                aria-label="メニューを閉じる"
                                onClick={() => setOpen(false)}
                            >
                                <svg className="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.5" aria-hidden="true">
                                    <path strokeLinecap="round" d="M6 6l12 12M18 6 6 18" />
                                </svg>
                            </button>
                        </div>

                        <nav aria-label="メインメニュー" className="flex-1 px-6">
                            <ul className="divide-y divide-brand-200 border-y border-brand-200">
                                {navigation.map((item) => (
                                    <li key={item.label} className="py-3">
                                        {item.href ? (
                                            <a href={item.href} className="block py-1 tracking-wider text-brand-800">
                                                {item.label}
                                            </a>
                                        ) : (
                                            <p className="py-1 tracking-wider text-brand-800">{item.label}</p>
                                        )}
                                        {item.children.length > 0 && (
                                            <ul className="mt-1 grid grid-cols-1 gap-1 pl-3 text-sm">
                                                {item.children.map((child) => (
                                                    <li key={child.href}>
                                                        <a href={child.href} className="block py-1 text-ink">
                                                            {child.label}
                                                        </a>
                                                    </li>
                                                ))}
                                            </ul>
                                        )}
                                    </li>
                                ))}
                            </ul>
                        </nav>

                        <div className="space-y-3 p-6">
                            <a href={reservationUrl} className="block rounded-full bg-brand-700 py-3 text-center text-sm tracking-widest text-white">
                                予約フォーム
                            </a>
                            <a href={lineUrl} target="_blank" rel="noopener" className="block rounded-full bg-line py-3 text-center text-sm tracking-widest text-white">
                                LINEで予約・相談
                            </a>
                            <a href={instagramUrl} target="_blank" rel="noopener" className="block py-2 text-center text-sm text-brand-700 underline underline-offset-4">
                                Instagram
                            </a>
                        </div>
                    </div>
                </div>,
                document.body,
            )}
        </>
    );
}
