import { useCallback, useEffect, useRef, useState } from 'react';

/**
 * 写真の拡大表示。<x-gallery> の a[data-lightbox] をクリックすると全画面で表示する。
 * 矢印キー・スワイプで前後に移動、Escで閉じる。
 */
export const enhance = true;

export default function Lightbox({ root }) {
    const [items, setItems] = useState([]);
    const [index, setIndex] = useState(null);
    const touchX = useRef(null);

    useEffect(() => {
        const links = [...root.querySelectorAll('a[data-lightbox]')];
        setItems(links.map((a) => ({ src: a.href, alt: a.querySelector('img')?.alt ?? '' })));

        const handlers = links.map((a, i) => {
            const handler = (e) => {
                e.preventDefault();
                setIndex(i);
            };
            a.addEventListener('click', handler);
            return () => a.removeEventListener('click', handler);
        });

        return () => handlers.forEach((off) => off());
    }, [root]);

    const close = useCallback(() => setIndex(null), []);
    const move = useCallback((step) => setIndex((i) => (i + step + items.length) % items.length), [items.length]);

    useEffect(() => {
        if (index === null) return;

        document.body.style.overflow = 'hidden';
        const onKey = (e) => {
            if (e.key === 'Escape') close();
            if (e.key === 'ArrowRight') move(1);
            if (e.key === 'ArrowLeft') move(-1);
        };
        window.addEventListener('keydown', onKey);

        return () => {
            document.body.style.overflow = '';
            window.removeEventListener('keydown', onKey);
        };
    }, [index, close, move]);

    if (index === null) return null;

    const item = items[index];

    return (
        <div
            role="dialog"
            aria-modal="true"
            aria-label="写真の拡大表示"
            className="fixed inset-0 z-50 flex items-center justify-center bg-brand-900/95"
            onClick={close}
            onTouchStart={(e) => (touchX.current = e.touches[0].clientX)}
            onTouchEnd={(e) => {
                const dx = e.changedTouches[0].clientX - touchX.current;
                if (Math.abs(dx) > 50) move(dx < 0 ? 1 : -1);
            }}
        >
            <img
                src={item.src}
                alt={item.alt}
                className="max-h-[88vh] max-w-[94vw] object-contain shadow-2xl"
                onClick={(e) => e.stopPropagation()}
            />

            <p className="absolute bottom-4 left-1/2 -translate-x-1/2 text-xs tracking-widest text-brand-100">
                {index + 1} / {items.length}
            </p>

            <button type="button" aria-label="閉じる" onClick={close} className="absolute top-3 right-3 p-3 text-white">
                <svg className="size-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.5" aria-hidden="true">
                    <path strokeLinecap="round" d="M6 6l12 12M18 6 6 18" />
                </svg>
            </button>

            {items.length > 1 && (
                <>
                    <button
                        type="button"
                        aria-label="前の写真"
                        onClick={(e) => {
                            e.stopPropagation();
                            move(-1);
                        }}
                        className="absolute left-1 p-3 text-white sm:left-4"
                    >
                        <svg className="size-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.2" aria-hidden="true">
                            <path strokeLinecap="round" strokeLinejoin="round" d="M15 5l-7 7 7 7" />
                        </svg>
                    </button>
                    <button
                        type="button"
                        aria-label="次の写真"
                        onClick={(e) => {
                            e.stopPropagation();
                            move(1);
                        }}
                        className="absolute right-1 p-3 text-white sm:right-4"
                    >
                        <svg className="size-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.2" aria-hidden="true">
                            <path strokeLinecap="round" strokeLinejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </>
            )}
        </div>
    );
}
