<?php

namespace App\Support;

use Illuminate\Foundation\Vite;

/**
 * 開発中（npm run dev）のCSS/JSを、ページと同じURL（/@vite/client など）から読み込む。
 *
 * 通常は http://localhost:5173 を直接参照するため、ngrok 経由やスマホからの確認では
 * 読み込めない（ブラウザが localhost へのアクセスを遮断する）。Nginx がこれらのパスを
 * node コンテナの Vite へ中継する（docker/nginx/default.conf）。
 * ビルド済み（本番）のときは影響しない。
 */
class SameOriginVite extends Vite
{
    public function devServerUrl()
    {
        return $this->isRunningHot() ? '' : null;
    }
}
