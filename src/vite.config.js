import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.jsx'],
            refresh: true,
        }),
        react(),
        tailwindcss(),
    ],
    experimental: {
        // JSから読み込むファイルは相対パスで参照する（/bb/ のようなサブフォルダに置いても動くように）
        renderBuiltUrl(filename, { hostType }) {
            return hostType === 'js' ? { relative: true } : undefined;
        },
    },
    server: {
        // Dockerのnodeコンテナで起動し、ブラウザからは Nginx（ページと同じURL）経由で接続する。
        // （app/Support/SameOriginVite.php・docker/nginx/default.conf を参照）
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        allowedHosts: ['localhost', '.ngrok-free.app', '.ngrok.app'],
        // 自動更新の接続先はページと同じホスト・ポート（http なら ws、https なら wss）
        hmr: { path: 'vite-hmr' },
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
