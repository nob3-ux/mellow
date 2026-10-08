# プロジェクト固有のルール

## 実行環境（Docker）

- PHP・Composer・Node.js はホスト（Mac）にはインストールされていない。必ず Docker コンテナ内で実行する。
- `compose.yaml` はこのLaravelディレクトリ（`src/`）の1つ上にある。リポジトリのルートで実行するか `-f ../compose.yaml` を付ける。
  - Artisan: `docker compose exec -T app php artisan <command> --no-interaction`
  - Composer: `docker compose exec -T app composer <command>`
  - テスト: `docker compose exec -T app php artisan test --compact`
  - 整形: `docker compose exec -T app vendor/bin/pint --dirty`
  - フロントのビルド: `docker compose run --rm --no-deps node npm run build`（開発中は `docker compose up -d` で node コンテナが `npm run dev` を起動する）
- サービス: web（Nginx, http://localhost:8000）/ app（PHP-FPM）/ node（Vite, :5173）/ db（MySQL 8.4）/ phpmyadmin（http://localhost:8080）/ mailpit（http://localhost:8025）。
- 本番はロリポップ（共用レンタルサーバー・Node.js なし）の `https://ps-mellow.jp/`。SSR や Node 常駐を前提にした仕組みは使わない。デプロイは GitHub 経由：手元で `../scripts/publish.sh`（テスト・本番用ビルド・`src/public/build` を記録・push）、サーバーで `~/web/mellow/scripts/deploy.sh`（git pull・composer・migrate・optimize）。手順は `../docs/deploy.md`。ビルド結果 `public/build` は Git に記録する。本番はリポジトリ全体を `~/web/mellow` に置き、公開フォルダは `mellow/src/public`。リポジトリ直下の `.htaccess` で src/public 以外を拒否し、`RedirectToCanonicalHost` で APP_URL 以外のホストを転送する。
- 旧WordPress（`/bb/` 以下）のURLは `config/pages.php` の `redirects` で新しいページへ301転送する。

## 構成

- ページは Blade でサーバー側描画（SEOのため）。動きのある部分だけ React（アイランド方式）。
  - Blade から `<x-island name="Foo" :props="[...]">読み込み前の表示</x-island>` で `resources/js/islands/Foo.jsx` を埋め込む。
  - 既存のHTMLに機能を足す場合は `export const enhance = true`（例: `Lightbox.jsx`）。
- スタイルは Tailwind CSS v4（`resources/css/app.css` の `@theme` にブランドカラー `brand-*`・フォント）。独自CSSは最小限にする。
- ページ定義（URL・タイトル・説明文・パンくずの親・旧URLからの301転送）は `config/pages.php`。ページを追加したら必ずここに登録する（ルート・sitemap.xml・パンくずに自動反映）。
  - ルート名に「.」を含む（`plans.shichigosan` など）ため、`config('pages.pages')[$name]` のように配列から引く。`config("pages.pages.{$name}")` は使わない。
- 店舗情報・ナビ・プラン一覧は `config/site.php`、フォームの項目は `config/forms.php`（サーバー検証と React フォームの両方が参照）。
- 画像は `public/images/<key>-{幅}.webp`。必ず `<x-picture src="<key>">` で出力する（srcset・幅高さ・遅延読み込みを付与）。サイズ情報は `resources/data/images.json`。新しい写真は `resources/images/<key>.jpg` に置き `docker compose run --rm --no-deps node npm run images` で変換する（public/images と images.json は手で編集しない）。
- 文章・料金・写真などの修正手順は `../docs/editing.md`。料金は複数箇所（プランページ・plans/index・config/site.php・config/pages.php の description・config/forms.php）にあるので、変更時はすべて揃える。
- 共通部品は `resources/views/components/`（各ファイル先頭のコメントに使い方）。ページは `<x-layouts.app :seo="$seo">` で包み、h1 は `<x-page-hero>` に1つだけ。
- WordPress 由来のクラス名・ディレクトリ（wp-content、wp-block-* など）は使わない。

## 空き状況（独自カレンダー）

- 空き枠は `availabilities` テーブル（1行 = 1日の午前 `am` または午後 `pm` が「空きあり」）。モデル `App\Models\Availability`、枠は `App\Enums\Slot`。
- スタッフは `/admin`（ログイン必須）で切り替える。管理者アカウントは `php artisan admin:create <ユーザー名>`（ログインはユーザー名＋パスワード） で作成・パスワード変更する。
- 公開側は `/api/schedule` から、本日〜 `config('site.schedule_months_ahead')` ヶ月先の月末までを取得して表示する。
- 予約フォームの申し込みと空き枠は連動しない（日程確定後にスタッフが管理画面で枠を外す運用）。

## コミュニケーション

- ユーザーへの応答・説明・コミットメッセージはすべて日本語で書く。
