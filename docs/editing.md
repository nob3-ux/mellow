# サイトの修正方法

PHOTO STUDIO MELLOW のサイトで、文章・料金・写真・メニューなどを修正するときの手順です。
修正後に本番（ロリポップ）へ反映する手順は [8章](#8-公開用ファイルを作って本番に反映する) にあります。初めて公開するとき（サーバーの準備・GitHub の設定・WordPressからの切り替え）は [deploy.md](deploy.md) を参照してください。

---

## 目次

1. [修正の流れ](#1-修正の流れ)
2. [開発環境の起動と停止](#2-開発環境の起動と停止)
3. [フォルダ構成と触ってよい場所](#3-フォルダ構成と触ってよい場所)
4. [どこを直せばいいか（早見表）](#4-どこを直せばいいか早見表)
5. [よくある修正](#5-よくある修正)
6. [見た目（色・余白・レイアウト）を変える](#6-見た目色余白レイアウトを変える)
7. [修正したら確認すること](#7-修正したら確認すること)
8. [公開用ファイルを作って本番に反映する](#8-公開用ファイルを作って本番に反映する)
9. [困ったときは](#9-困ったときは)

---

## 1. 修正の流れ

```
① 開発環境を起動する      docker compose up -d
② ファイルを修正する       触ってよい場所は 3章、どこを直すかは 4章
③ ブラウザで確認する       http://localhost:8000 （保存すると自動で再読み込み）
④ 自動テストを実行する     7章
⑤ Gitに記録する            git commit（9章「変更の履歴」）
⑥ GitHub に送る            テスト → 本番用ビルド → ビルド結果を記録 → git push（8章）
⑦ 本番に反映する           サーバーで scripts/deploy.sh（git pull して反映。8章）
```

修正中は**ビルド不要**です。保存するとブラウザに自動で反映されます。本番用のビルドは、⑥で公開する前に1回だけ行います。

---

## 2. 開発環境の起動と停止

ターミナルで `~/work/mellow` に移動してから実行します。

```bash
cd ~/work/mellow
docker compose up -d      # 起動（Docker Desktop を先に起動しておく）
docker compose down       # 停止
```

| 画面 | URL | 用途 |
|---|---|---|
| サイト | http://localhost:8000 | 修正内容の確認 |
| 管理画面 | http://localhost:8000/admin | 空き状況の登録 |
| Mailpit | http://localhost:8025 | フォームから送られたメールの確認（実際には送信されない） |
| phpMyAdmin | http://localhost:8080 | データベースの中身を見る |

### 管理画面のログイン情報

| 項目 | 値 |
|---|---|
| ユーザー名 | `mariko` |
| パスワード | 別途保管しているもの（この手順書には記載しない） |
| ログイン画面 | 手元: http://localhost:8000/admin ／ 本番: https://ps-mellow.jp/admin |

> このログイン情報は手元の開発環境に設定済みです。本番では、初回の公開時に同じ内容でアカウントを作成します（[deploy.md の 4-7](deploy.md#4-8-管理者アカウントを作る)）。
> パスワードは、この手順書（GitHub に保存される）には書かず、手帳やパスワード管理アプリなどで別に保管してください。

**パスワードを変更する・アカウントを追加する**

```bash
docker compose exec app php artisan admin:create mariko        # 手元（mariko のパスワードを変更）
```

- 実行するとパスワード（8文字以上）の入力を求められます。入力した文字は「•」で表示されます。
- 既にあるユーザー名を指定するとパスワードの変更、新しいユーザー名を指定するとアカウントの追加になります。
- ユーザー名に使えるのは半角英数字・`-`・`_`（3〜50文字）です。
- 本番では、サーバーにSSHで入って `cd ~/web/mellow/src && /usr/local/bin/php8.5 artisan admin:create mariko` を実行します。

---

## 3. フォルダ構成と触ってよい場所

`~/work/mellow` 以下の構成です。**✏️ の場所だけで、普段の修正はすべてできます。**

| 印 | 意味 |
|:-:|---|
| ✏️ | 普段の修正で触る場所 |
| ⚠️ | 仕組みを変えるときだけ触る（分からなければ Claude Code に頼む） |
| 🚫 | 触らない（自動で作られる・上書きされる・壊れると動かなくなる） |

```
mellow/
├── docs/                       ✏️ 手順書（この文書など）
├── scripts/                    ⚠️ deploy.sh（サーバーで反映）
├── docker/   compose.yaml      ⚠️ 開発環境（Docker）の設定
└── src/                        Laravel 本体
    ├── resources/
    │   ├── views/pages/        ✏️ 各ページの内容（文章・写真・レイアウト）
    │   ├── views/components/   ✏️ 共通部品（ヘッダー・フッター・ボタン等。全ページに影響）
    │   ├── views/admin/        ⚠️ 管理画面
    │   ├── views/mail/         ✏️ 通知メールの文面
    │   ├── images/             ✏️ 写真の元データを置く場所（npm run images で変換）
    │   ├── data/instagram.json ✏️ トップの Instagram 欄
    │   ├── data/images.json    🚫 写真の一覧（npm run images が自動で更新）
    │   ├── css/app.css         ✏️ 色・フォント（ブランドカラー）
    │   └── js/                 ⚠️ React（予約フォーム・カレンダー・拡大表示・スマホメニュー）
    ├── config/
    │   ├── site.php            ✏️ 店舗情報・メニュー・プラン一覧
    │   ├── pages.php           ✏️ ページの URL・タイトル・説明文・旧URLの転送
    │   ├── forms.php           ✏️ 予約フォーム・お問い合わせの項目
    │   └── その他              ⚠️ Laravel の設定
    ├── .env                    ✏️ 手元の設定（通知先メールなど。Gitには記録されない）
    ├── app/                    ⚠️ PHP のプログラム（フォーム送信・空き状況・管理画面）
    ├── routes/                 ⚠️ URL の割り当て（ページは config/pages.php で追加する）
    ├── database/               ⚠️ データベースの構造・初期データ
    ├── tests/                  ⚠️ 自動テスト
    ├── public/images/          🚫 変換済みの写真（npm run images が作る）
    ├── public/build/           🚫 本番用のビルド結果（npm run build が作る。記録して送る）
    ├── public/hot              🚫 開発サーバーの目印（記録されない）
    ├── vendor/  node_modules/  🚫 ライブラリ（自動でインストールされる）
    ├── storage/  bootstrap/cache/  🚫 ログ・キャッシュ
    ├── CLAUDE.md  AGENTS.md  .claude/  .agents/  🚫 Claude Code 向けの説明（自動生成。
    │                                    変えたい場合は .ai/guidelines/project.md を直す）
    └── composer.json/lock  package.json/lock  vite.config.js  ⚠️ ライブラリ・ビルドの設定
```

**特に注意すること**

- `src/public/images/` の写真を直接置き換えたり、`images.json` を手で編集したりしないでください。写真は必ず `src/resources/images/` に置いて `npm run images` で変換します（[5-4](#5-4-写真を追加差し替える)）。
- `src/public/build/` は `npm run build` が作り直します。手で編集しないでください。
- 本番サーバー上のファイルは直接編集しないでください。修正は必ず手元で行い、GitHub 経由で反映します（直接編集すると次の `git pull` が失敗します）。
- `views/components/` の部品を直すと、その部品を使っている**全ページ**が変わります。

---

## 4. どこを直せばいいか（早見表）

`src/` 以下のファイルです。

| やりたいこと | 直すファイル |
|---|---|
| ページの文章・写真・レイアウト | `resources/views/pages/〇〇.blade.php`（[5-1](#5-1-文章を直す)） |
| トップページのお知らせ | `resources/views/pages/home.blade.php`（[5-2](#5-2-お知らせを変える)） |
| 料金 | 複数ファイル（[5-3](#5-3-料金を変える)） |
| 写真の追加・差し替え | `resources/images/` に置いて変換（[5-4](#5-4-写真を追加差し替える)） |
| ヘッダーのメニュー | `config/site.php` の `navigation`（[5-5](#5-5-メニューフッターのリンクを変える)） |
| フッターのリンク | `resources/views/components/site-footer.blade.php` |
| 住所・メール・LINE・Instagram のURL | `config/site.php`（[5-6](#5-6-店舗情報住所連絡先を変える)） |
| ページのタイトル・検索結果の説明文 | `config/pages.php`（[5-7](#5-7-ページのタイトル検索結果の説明文を変える)） |
| ページの追加 | `config/pages.php` ＋ ビューを作成（[5-8](#5-8-ページを追加する)） |
| 予約フォーム・お問い合わせの項目 | `config/forms.php`（[5-10](#5-10-予約フォームお問い合わせの項目を変える)） |
| 通知メールの宛先 | `.env` の `SITE_MAIL_TO`（[5-11](#5-11-通知メールの宛先を変える)） |
| 空き状況 | 管理画面（ファイルの修正は不要） |
| トップの Instagram 欄の写真 | `resources/data/instagram.json`（[5-12](#5-12-トップの-instagram-欄を更新する)） |
| 色・フォント | `resources/css/app.css`（[6章](#6-見た目色余白レイアウトを変える)） |

### URL とファイルの対応

| URL | ファイル（`resources/views/pages/` 以下） |
|---|---|
| `/` | `home.blade.php` |
| `/about` | `about.blade.php` |
| `/studio` | `studio.blade.php` |
| `/gallery` | `gallery.blade.php` |
| `/gallery/wedding` | `gallery-wedding.blade.php` |
| `/gallery/kimono` | `gallery-kimono.blade.php` |
| `/plans` | `plans/index.blade.php` |
| `/plans/flower-styling` | `plans/flower-styling.blade.php` |
| `/plans/anniversary` | `plans/anniversary.blade.php` |
| `/plans/shichigosan` | `plans/shichigosan.blade.php` |
| `/plans/maternity` | `plans/maternity.blade.php` |
| `/plans/half-seijinshiki` | `plans/half-seijinshiki.blade.php` |
| `/plans/wedding` | `plans/wedding.blade.php` |
| `/options/dress-up-day` | `options/dress-up-day.blade.php` |
| `/options/smash-cake` | `options/smash-cake.blade.php` |
| `/options/mama-kimono` | `options/mama-kimono.blade.php` |
| `/items` | `items.blade.php` |
| `/schedule` | `schedule.blade.php` |
| `/flow` | `flow.blade.php` |
| `/access` | `access.blade.php` |
| `/contact` | `contact.blade.php` |
| `/reservation` | `reservation.blade.php` |
| `/inquiry` | `inquiry.blade.php` |
| `/line` | `line.blade.php` |
| `/cancel-policy` | `cancel-policy.blade.php` |
| `/recruit` | `recruit.blade.php` |

> 迷ったら、ブラウザに表示されている文章の一部で `src/resources/views` を検索すると見つかります（VS Code の「フォルダーを指定して検索」）。

---

## 5. よくある修正

### 5-1. 文章を直す

ページのファイルはHTMLとほぼ同じ書き方です。**タグ（`<p>` など）の間の文字だけ**を書き換えれば、見た目はそのまま文章が変わります。

```blade
<p>撮影データのお渡しは最大で2週間以内。</p>
              ↓
<p>撮影データのお渡しは最大で10日以内。</p>
```

| やりたいこと | 書き方 |
|---|---|
| 改行 | `<br>` |
| 段落を分ける | `<p>…</p>` をもう1つ作る |
| 太字 | `<strong>…</strong>` |
| サイト内リンク | `<a href="{{ route('access') }}">アクセス</a>`（`route('…')` の中は `config/pages.php` のページ名） |
| 外部リンク | `<a href="https://…" target="_blank" rel="noopener">…</a>` |

**注意**
- `class="…"` の中は見た目の指定です。文章だけ直すときは触らないでください。
- `{{ }}` と `@` で始まる単語（`@if` など）は、Blade の命令として扱われます。文章中で使う場合は `@{{ }}` / `@@` のように `@` を1つ追加してください（メールアドレスの `@` はそのままで問題ありません）。
- `<x-…>` で始まるタグは共通部品です（[6章](#共通部品の一覧)）。

### 5-2. お知らせを変える

トップのお知らせは `resources/views/pages/home.blade.php` の `{{-- お知らせ --}}` の部分です。

```blade
<x-notice title="七五三の受付を再開しました">
    <p>本文1段落目</p>
    <p>本文2段落目</p>
</x-notice>
```

- `title="…"` が見出しです。
- 目立たせたい注意は `tone="alert"` を付けると赤系の枠になります: `<x-notice title="…" tone="alert">`
- お知らせが無いときは、`{{-- お知らせ --}}` から次の `</x-section>` までを削除します（復活させるときのために、`{{-- --}}` で囲んで残しておくこともできます）。

予約フォームのページ（`reservation.blade.php`）や七五三プラン（`plans/shichigosan.blade.php`）にも、同じ形のお知らせがあります。

### 5-3. 料金を変える

料金は**次の場所すべて**を直してください（例: 生花Styling 20カット 平日 22,000円）。

| 場所 | ファイル | 書き方の例 |
|---|---|---|
| ① プランのページ（料金カード） | `pages/plans/flower-styling.blade.php` | `:prices="[['平日', 22000], ['土日祝', 25000]]"`（カンマなしの数字） |
| ② プラン・料金一覧の早見表 | `pages/plans/index.blade.php` | `['生花Styling スタンダード20カット', 22000, 25000, …]` |
| ③ プラン一覧カードの「〜円」 | `config/site.php` の `plans` → `price` | `'price' => 22000,`（そのプランの**最低料金**） |
| ④ 検索結果の説明文 | `config/pages.php` の `description` | `20カット平日22,000円〜` |
| ⑤ 予約フォームの選択肢（追加料金のみ） | `config/forms.php` | `'yes' => '生花あり（＋3,300円）'` |

オプション料金（例: ＋3,300円）は、プランのページの本文や `<x-slot:options>` の中にも書かれています。料金を変えたら、次のコマンドで**古い金額が残っていないか**を確認すると安心です。

```bash
grep -rn "3,300\|3300" src/resources/views src/config
```

### 5-4. 写真を追加・差し替える

サイトの写真は、表示速度のために **WebP 形式・3サイズ** に変換して使っています。元の写真を置いてコマンドを実行すると、自動で変換されます。

**1. 元の写真を置く**

```
src/resources/images/<フォルダ名>/<名前>.jpg     （.jpeg / .png / .webp も可）
```

- フォルダ名・ファイル名は**半角英小文字・数字・`-`・`_`** だけにしてください（日本語・空白・大文字は不可）。
- 例: `src/resources/images/plan-shichigosan/new-kimono-01.jpg`
- 写真は長辺 2000px 程度以上あれば十分です（大きくても自動で縮小されます）。

**2. 変換する**

```bash
docker compose run --rm --no-deps node npm run images
```

`✓ plan-shichigosan/new-kimono-01（640 / 1280 / 1920px）` のように表示されれば完了です。`plan-shichigosan/new-kimono-01` が**画像キー**になります。

**3. ページに載せる**

```blade
{{-- 1枚の写真 --}}
<x-picture src="plan-shichigosan/new-kimono-01" alt="アンティーク着物を着た3歳の女の子" class="w-full rounded-sm" />

{{-- ギャラリー（押すと拡大表示）に追加する場合は、images の一覧にキーを足す --}}
<x-gallery :images="[
    'plan-shichigosan/04',
    'plan-shichigosan/new-kimono-01',
]" />
```

- `alt` には**写真の内容を日本語で**書いてください（検索エンジンと、目の不自由な方の読み上げに使われます）。

**既存の写真を差し替える場合**

同じ画像キーになるように置いて変換します。例えば、トップの大きな写真（画像キー `home/hero`）を差し替えるなら `src/resources/images/home/hero.jpg` を置いて `npm run images` を実行します。ページのファイルは直す必要がありません。

> 画像キーは、ページのファイルの `src="…"` や `images` の一覧で確認できます。変換済みの画像は `src/public/images/` に、一覧は `src/resources/data/images.json` にあります（どちらも手で編集しないでください）。

### 5-5. メニュー・フッターのリンクを変える

**ヘッダーのメニュー**（PC・スマホ共通）は `config/site.php` の `navigation` です。

```php
['label' => 'アクセス', 'route' => 'access'],
['label' => 'プラン', 'route' => 'plans.index', 'children' => [
    ['label' => '七五三プラン', 'route' => 'plans.shichigosan'],
]],
```

- `label` が表示名、`route` がリンク先のページ名（`config/pages.php` の名前）です。
- `children` はマウスを乗せたときに出るサブメニューです。

**フッターのリンク**は `resources/views/components/site-footer.blade.php` を直接編集します。

### 5-6. 店舗情報（住所・連絡先）を変える

`config/site.php` の次の項目です。フッター・アクセス・検索エンジン向けの店舗情報（構造化データ）にまとめて反映されます。

| 項目 | 内容 |
|---|---|
| `contact.email` | メールアドレス |
| `contact.line_url` / `line_id` | LINE公式アカウントのURL・ID |
| `contact.instagram_url` / `instagram_id` | Instagram |
| `address` | 郵便番号・住所・最寄り駅・地図の埋め込みURL |

### 5-7. ページのタイトル・検索結果の説明文を変える

`config/pages.php` の各ページの次の項目です。

| 項目 | 内容 |
|---|---|
| `title` | ブラウザのタブと検索結果のタイトル（後ろに「｜PHOTO STUDIO MELLOW」が自動で付く） |
| `description` | 検索結果に表示される説明文（全角80〜120文字が目安） |
| `parent` | パンくずリストの親ページ |

> ページ内の大きな見出し（h1）は、各ページのファイルの `<x-page-hero title="…">` です。

### 5-8. ページを追加する

例: 「よくある質問」ページ（`/faq`）を追加する場合

**1. `config/pages.php` の `pages` に追加**

```php
'faq' => [
    'path' => '/faq',
    'view' => 'faq',
    'title' => 'よくある質問',
    'description' => '撮影・ご予約・お支払いについてよくいただくご質問をまとめました。',
    'parent' => 'home',
],
```

**2. `resources/views/pages/faq.blade.php` を作成**（既存のページをコピーして書き換えると簡単です）

```blade
<x-layouts.app :seo="$seo">

    <x-page-hero title="よくある質問" en="FAQ" />

    <x-section width="narrow">
        <x-heading en="Reservation">ご予約について</x-heading>
        <div class="prose-mellow">
            <p>本文</p>
        </div>
    </x-section>

</x-layouts.app>
```

**3. 必要ならメニューに追加**（[5-5](#5-5-メニューフッターのリンクを変える)）

ページの表示・サイトマップ（sitemap.xml）・パンくずリストへの追加は自動で行われます。

### 5-9. 旧サイトのURLの転送を追加する

旧WordPressのURL（`https://ps-mellow.jp/bb/〇〇/`）を新しいページへ転送するには、`config/pages.php` の `redirects` に追加します。

```php
'redirects' => [
    '753plan' => 'plans.shichigosan',   // /bb/753plan → 七五三プラン
    '旧スラッグ' => '新しいページ名',
],
```

### 5-10. 予約フォーム・お問い合わせの項目を変える

項目は `config/forms.php` にまとまっています。ここを直すと、**画面のフォーム・入力チェック・通知メールのすべてに反映**されます。

```php
'time_slot' => [
    'label' => 'ご希望の時間帯',           // 項目名（通知メールにも使われる）
    'type' => 'select',                     // text / email / tel / date / select / textarea
    'required' => true,                     // 必須かどうか
    'step' => 1,                            // 予約フォームの何ステップ目に出すか（1〜3）
    'options' => ['am' => '午前', 'pm' => '午後', 'any' => 'どちらでも'],
],
```

| やりたいこと | 方法 |
|---|---|
| 選択肢の文言・料金を変える | `options` の右側（`'午前'` など）を書き換える |
| 選択肢を追加する | `'新しいキー' => '表示名'` を追加（キーは半角英数字） |
| 選択肢を一時的に選べなくする | `'disabled' => ['キー']` に追加（表示はされるが選べない） |
| 特定のプランのときだけ出す | `'when' => ['plan', ['shichigosan']]`（七五三のときだけ表示） |
| 特定のプランのときだけ必須 | `'required' => ['plan', ['other']]`（その他のときだけ必須） |
| 説明文を付ける | `'help' => '…'` |

> 選択肢のキー（左側）を変えると、プランページの「このプランで予約する」ボタンから選択済みで開く機能が効かなくなることがあります。キーはなるべく変えず、表示名だけを変えてください。

### 5-11. 通知メールの宛先を変える

フォームの通知先は `.env` の `SITE_MAIL_TO` です。

- 手元: `src/.env`
- 本番: サーバーの `~/web/mellow/src/.env`（変更後に `bash ~/web/mellow/scripts/deploy.sh` を実行すると反映される）

### 5-12. トップの Instagram 欄を更新する

トップの Instagram 欄は、自動では更新されません（Instagram の仕様変更で止まらないよう、写真を保存して表示しています）。更新する場合は次のとおりです。

1. 写真を `src/resources/images/instagram/<名前>.jpg` に置いて変換する（[5-4](#5-4-写真を追加差し替える)）
2. `src/resources/data/instagram.json` の各投稿を書き換える

```json
{
  "url": "https://www.instagram.com/p/投稿のID/",
  "caption": "投稿の1行目（写真の説明として使われる）",
  "image": "instagram/<名前>"
}
```

9件（3列×3段）にするときれいに並びます。

### 5-13. 空き状況を登録する

ファイルの修正は不要です。管理画面（本番は `https://ps-mellow.jp/admin`）で登録します。

- 日付の「午前」「午後」を押すと、空きあり（茶色）／空きなし（ベージュ）が切り替わり、すぐに公開ページへ反映されます。
- 「〇月をまとめて設定」で、曜日を選んで一括で空きにする／消すことができます。
- お客様に公開されるのは、今日から3ヶ月先の月末までです（`config/site.php` の `schedule_months_ahead`）。
- 予約フォームの申し込みでは空き枠は自動で埋まりません。日程が確定したら、その枠を「空きなし」にしてください。

---

## 6. 見た目（色・余白・レイアウト）を変える

見た目は **Tailwind CSS** で、`class="…"` の中に指定しています。

| 例 | 意味 |
|---|---|
| `mt-6` / `mb-10` | 上 / 下の余白（数字が大きいほど広い。1 = 4px） |
| `text-sm` / `text-lg` / `text-2xl` | 文字の大きさ |
| `text-brand-700` | 文字の色（ブランドカラー） |
| `bg-white` / `bg-brand-100` | 背景色 |
| `sm:` `lg:` で始まるもの | 画面幅が広いとき（スマホより大きいとき / PC）だけ効く指定 |
| `grid-cols-2` | 2列に並べる |

**ブランドカラー・フォント**は `src/resources/css/app.css` の `@theme` にまとまっています。例えば `--color-brand-700` を変えると、ボタンや見出しなど同じ色を使っている部分がすべて変わります。

### 共通部品の一覧

ページの中の `<x-…>` は共通部品です。部品のファイル（`resources/views/components/`）を直すと、使っている全ページに反映されます。各ファイルの先頭に使い方のコメントがあります。

| 部品 | 用途 | 例 |
|---|---|---|
| `<x-page-hero>` | ページ先頭の大見出し（写真付き可） | `<x-page-hero title="七五三プラン" en="Shichi-Go-San" image="plan-shichigosan/04" lead="…" />` |
| `<x-section>` | 区切りのまとまり（背景色・幅） | `<x-section tone="white" width="narrow">…</x-section>`（tone: base / white / cream / dark） |
| `<x-heading>` | セクションの見出し | `<x-heading en="Plan">撮影プラン</x-heading>` |
| `<x-picture>` | 写真 | `<x-picture src="home/01" alt="…" />` |
| `<x-gallery>` | 写真の一覧（押すと拡大） | `<x-gallery :images="['home/01', 'home/02']" :columns="3" />` |
| `<x-price-card>` | 料金カード | `<x-price-card name="…" :prices="[['平日', 22000]]" :reserve="route('reservation')">…</x-price-card>` |
| `<x-notice>` | お知らせ・注意の枠 | `<x-notice title="…" tone="alert">…</x-notice>` |
| `<x-button>` | ボタン | `<x-button :href="route('flow')" variant="outline" arrow>ご予約の流れ</x-button>`（variant: primary / outline / line / light） |
| `<x-steps>` `<x-step>` | 手順（STEP 1, 2…） | `<x-steps><x-step title="ご予約">…</x-step></x-steps>` |
| `<x-plan-card>` | プラン一覧のカード | `<x-plan-card plan="maternity" />`（内容は `config/site.php` の `plans`） |

ヘッダー（`site-header`）、フッター（`site-footer`）、スマホの下部ボタン（`mobile-action-bar`）、ページ下部の予約案内（`reservation-cta`）も同じフォルダにあります。

> 予約フォーム・空き状況カレンダー・写真の拡大表示・スマホメニューは React で作られています（`resources/js/islands/`）。文言の変更程度ならファイル内の日本語を書き換えれば反映されます。

---

## 7. 修正したら確認すること

**1. ブラウザで確認**

- PC: http://localhost:8000
- スマホ（Macと同じWi-Fi）: `http://MacのIPアドレス:8000`
  - MacのIPアドレスはターミナルで `ipconfig getifaddr en0` を実行すると表示されます（例 `192.168.1.20`）。
- 外出先のスマホや他の人に見せる場合: ngrok などで公開します（`ngrok http 8000`）。
- フォームを直した場合は、実際に送信して Mailpit（http://localhost:8025）にメールが届くか確認します。

**2. 自動テストを実行**

```bash
docker compose exec app php artisan test --compact
```

`Tests: 25 passed` のように **failed が無ければ** OK です。全ページが表示できるか、フォームの送信、旧URLの転送などを自動で確認しています。

**3. PHPファイルを直した場合は書式を整える**（任意）

```bash
docker compose exec app vendor/bin/pint --dirty
```

**4. 本番に公開する** → [8章](#8-公開用ファイルを作って本番に反映する)

---

## 8. 公開用ファイルを作って本番に反映する

修正と確認が終わったら、**手元で GitHub に送り、サーバーで取り込む**の2段階で本番（ロリポップ）に反映します。初めて公開するとき（GitHub・サーバーの準備）は [deploy.md の「初回の公開手順」](deploy.md#4-初回の公開手順) を参照してください。

### 8-1. GitHub に送る（手元）

開発環境（`docker compose up -d`）を起動した状態で、次を順に実行します。

```bash
cd ~/work/mellow

# 1. 修正を記録する（何を変えたかを書く）
git add -A
git commit -m "七五三の料金を変更"

# 2. 自動テスト（failed が無いことを確認）
docker compose exec app php artisan test --compact

# 3. React・CSS を本番用にビルドする
docker compose run --rm --no-deps node npm run build

# 4. ビルド結果を記録する（変更が無い場合は「nothing to commit」と出るだけで問題なし）
git add src/public/build
git commit -m "本番用ビルドを更新" src/public/build

# 5. GitHub へ送る
git push origin main
```

| 手順 | 内容と注意 |
|---|---|
| 1. 記録 | VS Code の「ソース管理」から記録しても同じです（9章）。 |
| 2. テスト | 全ページの表示・フォーム・旧URLの転送などを自動で確認します。`failed` が出たら、送らずに原因を直してください（7章）。 |
| 3. ビルド | React・CSS を本番用に変換して `src/public/build/` に出力します。**毎回必ず実行してください。** 本番サーバーではビルドできないため、忘れると React（予約フォーム・カレンダー等）や見た目の修正が本番に反映されません。 |
| 4. ビルド結果の記録 | 最後の `src/public/build` の指定で、ビルド結果だけを記録します（他の変更が混ざらない）。 |
| 5. 送信 | GitHub へ送ります。送っただけでは本番は変わりません（続けて 8-3）。 |

### 8-2. 送った内容を確認する（任意）

GitHub のリポジトリのページで、最新の記録（「本番用ビルドを更新」など）が表示されていれば送信できています。

### 8-3. 本番に反映する（サーバー）

```bash
ssh -p 2222 アカウント名@ssh.lolipop.jp
bash ~/web/mellow/scripts/deploy.sh
exit
```

`✓ 本番に反映しました（記録のID 内容）` と表示されれば反映済みです。サーバーでは `git pull` で GitHub の内容を取り込み、ライブラリの更新・データベースの更新・キャッシュの作り直しまで自動で行います（詳しくは [deploy.md の 3-2](deploy.md#3-2-deploysh-がすること)）。作業中（数十秒）はサイトに「メンテナンス中」と表示されます。`.env`・ログ・データベース（空き状況・管理者）はそのまま残ります。

### 8-4. 公開後の確認

- [ ] `https://ps-mellow.jp/` で修正した箇所が反映されている（古い表示のままならブラウザを再読み込み）
- [ ] スマホでも表示が崩れていない
- [ ] フォームを直した場合は、テスト送信して通知メールが届く

### 8-5. 公開した内容を元に戻したいとき

Git の記録を使って、問題のある変更を取り消してから、もう一度公開します。

```bash
git log --oneline                    # 記録の一覧から、取り消したい記録のIDを確認
git revert 記録のID                   # その変更を打ち消す記録を作る
# このあと 8-1 の手順 2〜5（テスト・ビルド・記録・git push）→ 8-3 で反映
```

> どの記録を取り消せばよいか分からない場合は、Claude Code に「さっき公開した〇〇の変更を元に戻したい」と頼んでください。

---

## 9. 困ったときは

| 症状 | 対処 |
|---|---|
| 保存しても画面が変わらない | ブラウザを再読み込み。それでも変わらなければ `docker compose exec app php artisan optimize:clear` |
| デザインが崩れる（文字だけになる） | 開発環境が起動しているか確認（`docker compose ps` で `node` が動いているか）。`docker compose up -d` で起動 |
| 画面にエラー（赤や黒の画面）が出る | エラー文の中のファイル名と行番号を確認。直前に編集した箇所の `{{ }}`・`@`・タグの閉じ忘れ（`</p>` など）が多い |
| 写真が表示されない | 画像キーの綴りを確認（`src/resources/data/images.json` にあるか）。新しい写真は `npm run images` を実行したか |
| `npm run images` で「半角英小文字…」と出る | ファイル名・フォルダ名に日本語・空白・大文字が含まれている。名前を変えて再実行 |
| テストが失敗する | 表示されたテスト名とメッセージを確認。ページを追加した場合は、`config/pages.php` の `description` と、ページに `<x-page-hero>`（h1）が1つあるかを確認 |
| 元に戻したい | 下の「変更の履歴」を参照 |

### 変更の履歴（Git）

このプロジェクトは **Git** で変更の履歴を管理しています。修正がひと区切りしたら「記録（コミット）」しておくと、あとで元に戻したり、いつ何を変えたかを確認したりできます。

> `.env`（パスワード類）・`vendor`・`node_modules`・`public/hot` は記録しない設定になっています。本番用のビルド結果（`public/build`）は記録します。

**VS Code で操作する場合**（左端の「ソース管理」アイコン）

| やりたいこと | 操作 |
|---|---|
| 変更したファイルを見る | 「ソース管理」を開くと一覧が出る。ファイルを押すと変更前後を並べて表示 |
| 記録する（コミット） | 上の入力欄に「七五三の料金を変更」のように内容を書き、「コミット」を押す（「すべての変更をステージしてコミットしますか」と聞かれたら「はい」） |
| 記録前の変更を取り消す | 一覧のファイルにマウスを乗せ、「変更を破棄」（↶）を押す |
| 過去の記録を見る | 下部の「タイムライン」または「グラフ」 |

**ターミナルで操作する場合**

```bash
git status                       # 変更したファイルの一覧
git diff                         # 変更内容
git add -A && git commit -m "七五三の料金を変更"    # 記録する
git restore src/resources/views/pages/home.blade.php  # 記録前の変更を取り消す（ファイル単位）
git log --oneline                # 記録の一覧
```

**記録のタイミングの目安**: 本番に公開する前には必ず記録しておくと、「公開した時点の状態」に戻せるので安心です。

> 記録は GitHub（非公開リポジトリ）にも送られ、本番サーバーはそこから取り込みます（8章）。別のパソコンで作業する場合は、GitHub から `git clone` して使えます。

### Claude Code に修正を頼む場合

このプロジェクトには、Claude Code 向けの説明（`src/CLAUDE.md`・`src/.ai/guidelines/project.md`）が用意されています。「トップのお知らせを〇〇に変えて」「七五三プランの料金を〇〇円にして」のように頼めば、この手順書と同じ場所を修正します。
