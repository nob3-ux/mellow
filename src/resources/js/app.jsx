/*
 * Reactの埋め込み（アイランド）
 *
 * Blade の <x-island name="Foo" :props="[...]"> が出力する
 *   <div data-island="Foo" data-props="{...}">（JS読み込み前の内容）</div>
 * を探し、resources/js/islands/Foo.jsx を必要なときだけ読み込んで描画する。
 *
 * islands/*.jsx の書き方:
 *   export default function Foo(props) {...}
 *   export const enhance = true; // 中身（Bladeの出力）を残したまま機能を足す場合
 *                                // このとき props.root に要素が渡り、描画先は別に用意される
 */
import { createRoot } from 'react-dom/client';

const islands = import.meta.glob('./islands/*.jsx');

async function mount(element) {
    const name = element.dataset.island;
    const loader = islands[`./islands/${name}.jsx`];

    if (!loader) {
        console.error(`アイランド ${name} が見つかりません`);
        return;
    }

    const module = await loader();
    const props = JSON.parse(element.dataset.props || '{}');
    const Component = module.default;

    if (module.enhance) {
        const container = document.createElement('div');
        document.body.appendChild(container);
        createRoot(container).render(<Component {...props} root={element} />);
    } else {
        createRoot(element).render(<Component {...props} />);
    }
}

document.querySelectorAll('[data-island]').forEach(mount);
