# サイトの修正方法

PHOTO STUDIO MELLOW のサイトで、文章・料金・写真・メニューなどを修正するときの手順です。
修正したものを本番（ロリポップ）に反映する方法は [deploy.md](deploy.md) を参照してください。

---

## 目次

1. [修正の流れ](#1-修正の流れ)
2. [開発環境の起動と停止](#2-開発環境の起動と停止)
3. [どこを直せばいいか（早見表）](#3-どこを直せばいいか早見表)
4. [よくある修正](#4-よくある修正)
5. [見た目（色・余白・レイアウト）を変える](#5-見た目色余白レイアウトを変える)
6. [修正したら確認すること](#6-修正したら確認すること)
7. [困ったときは](#7-困ったときは)

---

## 1. 修正の流れ

```
① 開発環境を起動する      docker compose up -d
② ファイルを修正する       VS Code などで編集して保存
③ ブラウザで確認する       http://localhost:8000 （保存すると自動で再読み込み）
④ 自動テストを実行する     （6章）
⑤ 本番に公開する           deploy.md の「2回目以降の更新手順」
```

修正中は**ビルド不要**です。保存するとブラウザに自動で反映されます。ビルドは本番に公開するときだけ `build-release.sh` が行います。

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

手元の管理画面のアカウントは次のコマンドで作成・パスワード変更できます。

```bash
docker compose exec app php artisan admin:create メールアドレス
```

---

## 3. どこを直せばいいか（早見表）

`src/` 以下のファイルです。

| やりたいこと | 直すファイル |
|---|---|
| ページの文章・写真・レイアウト | `resources/views/pages/〇〇.blade.php`（[4-1](#4-1-文章を直す)） |
| トップページのお知らせ | `resources/views/pages/home.blade.php`（[4-2](#4-2-お知らせを変える)） |
| 料金 | 複数ファイル（[4-3](#4-3-料金を変える)） |
| 写真の追加・差し替え | `resources/images/` に置いて変換（[4-4](#4-4-写真を追加差し替える)） |
| ヘッダーのメニュー | `config/site.php` の `navigation`（[4-5](#4-5-メニューフッターのリンクを変える)） |
| フッターのリンク | `resources/views/components/site-footer.blade.php` |
| 住所・メール・LINE・Instagram のURL | `config/site.php`（[4-6](#4-6-店舗情報住所連絡先を変える)） |
| ページのタイトル・検索結果の説明文 | `config/pages.php`（[4-7](#4-7-ページのタイトル検索結果の説明文を変える)） |
| ページの追加 | `config/pages.php` ＋ ビューを作成（[4-8](#4-8-ページを追加する)） |
| 予約フォーム・お問い合わせの項目 | `config/forms.php`（[4-10](#4-10-予約フォームお問い合わせの項目を変える)） |
| 通知メールの宛先 | `.env` の `SITE_MAIL_TO`（[4-11](#4-11-通知メールの宛先を変える)） |
| 空き状況 | 管理画面（ファイルの修正は不要） |
| トップの Instagram 欄の写真 | `resources/data/instagram.json`（[4-12](#4-12-トップの-instagram-欄を更新する)） |
| 色・フォント | `resources/css/app.css`（[5章](#5-見た目色余白レイアウトを変える)） |

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

## 4. よくある修正

### 4-1. 文章を直す

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
- `<x-…>` で始まるタグは共通部品です（[5章](#共通部品の一覧)）。

### 4-2. お知らせを変える

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

### 4-3. 料金を変える

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

### 4-4. 写真を追加・差し替える

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

### 4-5. メニュー・フッターのリンクを変える

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

### 4-6. 店舗情報（住所・連絡先）を変える

`config/site.php` の次の項目です。フッター・アクセス・検索エンジン向けの店舗情報（構造化データ）にまとめて反映されます。

| 項目 | 内容 |
|---|---|
| `contact.email` | メールアドレス |
| `contact.line_url` / `line_id` | LINE公式アカウントのURL・ID |
| `contact.instagram_url` / `instagram_id` | Instagram |
| `address` | 郵便番号・住所・最寄り駅・地図の埋め込みURL |

### 4-7. ページのタイトル・検索結果の説明文を変える

`config/pages.php` の各ページの次の項目です。

| 項目 | 内容 |
|---|---|
| `title` | ブラウザのタブと検索結果のタイトル（後ろに「｜PHOTO STUDIO MELLOW」が自動で付く） |
| `description` | 検索結果に表示される説明文（全角80〜120文字が目安） |
| `parent` | パンくずリストの親ページ |

> ページ内の大きな見出し（h1）は、各ページのファイルの `<x-page-hero title="…">` です。

### 4-8. ページを追加する

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

**3. 必要ならメニューに追加**（[4-5](#4-5-メニューフッターのリンクを変える)）

ページの表示・サイトマップ（sitemap.xml）・パンくずリストへの追加は自動で行われます。

### 4-9. 旧サイトのURLの転送を追加する

旧WordPressのURL（`https://ps-mellow.jp/bb/〇〇/`）を新しいページへ転送するには、`config/pages.php` の `redirects` に追加します。

```php
'redirects' => [
    '753plan' => 'plans.shichigosan',   // /bb/753plan → 七五三プラン
    '旧スラッグ' => '新しいページ名',
],
```

### 4-10. 予約フォーム・お問い合わせの項目を変える

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

### 4-11. 通知メールの宛先を変える

フォームの通知先は `.env` の `SITE_MAIL_TO` です。

- 手元: `src/.env`
- 本番: サーバーの `~/mellow-app/.env`（変更後に `artisan optimize` が必要。deploy.md の「困ったときは」参照）

### 4-12. トップの Instagram 欄を更新する

トップの Instagram 欄は、自動では更新されません（Instagram の仕様変更で止まらないよう、写真を保存して表示しています）。更新する場合は次のとおりです。

1. 写真を `src/resources/images/instagram/<名前>.jpg` に置いて変換する（[4-4](#4-4-写真を追加差し替える)）
2. `src/resources/data/instagram.json` の各投稿を書き換える

```json
{
  "url": "https://www.instagram.com/p/投稿のID/",
  "caption": "投稿の1行目（写真の説明として使われる）",
  "image": "instagram/<名前>"
}
```

9件（3列×3段）にするときれいに並びます。

### 4-13. 空き状況を登録する

ファイルの修正は不要です。管理画面（本番は `https://ps-mellow.jp/admin`）で登録します。

- 日付の「午前」「午後」を押すと、空きあり（茶色）／空きなし（ベージュ）が切り替わり、すぐに公開ページへ反映されます。
- 「〇月をまとめて設定」で、曜日を選んで一括で空きにする／消すことができます。
- お客様に公開されるのは、今日から3ヶ月先の月末までです（`config/site.php` の `schedule_months_ahead`）。
- 予約フォームの申し込みでは空き枠は自動で埋まりません。日程が確定したら、その枠を「空きなし」にしてください。

---

## 5. 見た目（色・余白・レイアウト）を変える

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

## 6. 修正したら確認すること

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

**4. 本番に公開する** → [deploy.md の「2回目以降の更新手順」](deploy.md#5-2回目以降の更新手順)

---

## 7. 困ったときは

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

> `.env`（パスワード類）・`release/`（公開用ファイル）・`vendor`・`node_modules`・`public/build` は記録しない設定になっています。

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

> 他のパソコンでも作業したい・インターネット上にもバックアップしたい場合は、GitHub（非公開リポジトリ）に置くことができます。必要になったら Claude Code に頼んでください。

### Claude Code に修正を頼む場合

このプロジェクトには、Claude Code 向けの説明（`src/CLAUDE.md`・`src/.ai/guidelines/project.md`）が用意されています。「トップのお知らせを〇〇に変えて」「七五三プランの料金を〇〇円にして」のように頼めば、この手順書と同じ場所を修正します。
