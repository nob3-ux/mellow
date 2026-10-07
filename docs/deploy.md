# ロリポップへのデプロイ手順

PHOTO STUDIO MELLOW のサイト（Laravel）を、**ロリポップ！レンタルサーバー**の `https://ps-mellow.jp/`（ドメイン直下）に公開・更新するための手順です。

- 公開用のファイルは**手元のMac（Docker）で作り**、サーバーには**アップロードして1つのスクリプトを実行するだけ**です。サーバーに Node.js や Composer は不要です。
- 現在の WordPress（`https://ps-mellow.jp/bb/`）はそのまま残し、ドメインの**公開フォルダを切り替える**ことで新サイトに移行します。問題があれば設定を戻すだけで元に戻せます。
- 旧サイトのURL（`/bb/753plan/` など）は、新サイトの対応するページへ自動で転送（301）されます。

---

## 目次

1. [全体の流れ](#1-全体の流れ)
2. [事前に確認すること](#2-事前に確認すること)
3. [サーバー上のフォルダ構成](#3-サーバー上のフォルダ構成)
4. [初回の公開手順](#4-初回の公開手順)
5. [2回目以降の更新手順](#5-2回目以降の更新手順)
6. [困ったときは](#6-困ったときは)

---

## 1. 全体の流れ

```
手元のMac                                       ロリポップ
───────────────────────────                     ──────────────────────────────────────
scripts/build-release.sh                        ~/mellow-release.tar.gz
  └ release/mellow-release.tar.gz ─ アップロード →  └ deploy.sh を実行すると…
                                                     ~/mellow-app/   ← アプリ本体（公開されない）
                                                     ~/web/mellow/   ← 公開フォルダ
                                                ps-mellow.jp の公開フォルダを「mellow」に切り替え
```

| 作業 | 初回 | 更新時 |
|---|:-:|:-:|
| ロリポップの準備（PHP・SSH・データベース・メール） | ○ | |
| 公開用ファイルを作る（手元で1コマンド） | ○ | ○ |
| アップロード（手元で1コマンド） | ○ | ○ |
| `.env`（本番の設定ファイル）を作る | ○ | |
| 配置スクリプトの実行（サーバーで1コマンド） | ○ | ○ |
| 管理者アカウントの作成 | ○ | |
| 公開フォルダの切り替え | ○ | |

---

## 2. 事前に確認すること

| 項目 | 必要な条件 | 確認する場所 |
|---|---|---|
| プラン | **スタンダード・ハイスピード・エンタープライズのいずれか**（SSHが使えるプラン） | ユーザー専用ページ |
| PHP | **8.4 以上**（推奨 8.5） | ユーザー専用ページ「PHP設定」 |
| データベース | MySQL（ロリポップで作成） | ユーザー専用ページ「データベース」 |
| SSL | ps-mellow.jp の独自SSLが有効（現在のWordPressで有効なはず） | ユーザー専用ページ「独自SSL証明書導入」 |

> **PHP 8.3 以下では動きません。** Laravel 13 が使う部品が PHP 8.4.1 以上を必要とします。
> PHP のバージョンはドメインごとに設定します。WordPress（/bb/）も同じドメインのため、PHP を上げると WordPress も新しい PHP で動きます。切り替えまでの間、WordPress の表示に問題がないか確認してください。

> **ライトプラン以下の場合**: SSH が使えないため、この手順ではデプロイできません。プラン変更をご検討ください。

---

## 3. サーバー上のフォルダ構成

ロリポップでは、FTPで見える一番上が `web/` フォルダで、**SSH でログインした場所（`~`）はその1つ上**です。アプリ本体は、パスワード等を含む `.env` があるため、**公開されない `~/mellow-app/`** に置きます。

```
~（/home/users/X/アカウント名/）     ← SSHでログインした場所
├── mellow-app/                      ← アプリ本体（公開されない・FTPからは見えない）
│   ├── .env                         ← サーバー上で作成（4-5）
│   ├── app/ bootstrap/ config/ database/ resources/ routes/ vendor/
│   └── storage/                     ← ログ・セッション（更新時も残る）
├── mellow-release.tar.gz            ← アップロードしたファイル（更新のたびに置き換え）
├── mellow-release/                  ← 上を展開したもの
└── web/                             ← FTPの一番上
    ├── （WordPressのフォルダ）       ← 今のまま残す
    └── mellow/                      ← 新サイトの公開フォルダ
        ├── .htaccess  index.php
        ├── build/                   ← CSS・JS
        └── images/                  ← 画像（WebP）
```

### 3-1. tar.gz を置く場所

アップロードする `mellow-release.tar.gz` は、**SSHでログインした直後の場所（ホームフォルダ `~`）** に置きます。ロリポップでは `/home/users/番号/アカウント名/` にあたり、公開フォルダ（`web/`）の1つ上の、外からは見えない場所です。

手元のMacで次のように送れば、自動でそこに置かれます（`:~/` がホームフォルダの意味です）。

```bash
scp -P 2222 release/mellow-release.tar.gz アカウント名@ssh.lolipop.jp:~/
```

> **FTPでは置かないでください。** FTPで見える一番上は `web/`（公開フォルダ）です。ここに置くと、tar.gz がインターネットから誰でもダウンロードできる状態になります（中身はサイトのプログラム一式。`.env` は含まれません）。

tar.gz は置いただけでは使えません。**展開してから** `deploy.sh` を実行します（展開せずに実行すると「そのようなファイルはありません」というエラーになります）。

```bash
ssh -p 2222 アカウント名@ssh.lolipop.jp     # ホームフォルダに入る
rm -rf mellow-release                       # 前回展開したものを消す（初回は不要）
tar xzf mellow-release.tar.gz               # 展開 → ~/mellow-release/ ができる
bash mellow-release/deploy.sh               # 配置（初回は --first を付ける）
```

### 3-2. deploy.sh がすること

| 順 | 処理 | 内容 |
|:-:|---|---|
| 1 | PHPを探す | 8.4以上のPHPを自動で探す（`/usr/local/bin/php8.5` など）。見つからなければ止まる |
| 2 | メンテナンス表示 | 2回目以降のみ。作業中にアクセスした人には「メンテナンス中」と表示される |
| 3 | アプリ本体を配置 | `~/mellow-app/` のプログラム（app・config・resources・vendor など）を新しいものに入れ替える。**`.env` と `storage/`（ログ・セッション）はそのまま残す** |
| 4 | 公開フォルダを配置 | `~/web/mellow/` に CSS/JS（`build/`）・画像・`index.php`・`.htaccess` を上書きコピーする（フォルダが無ければ作る） |
| 5 | `.env` の確認 | `~/mellow-app/.env` が無ければ、ここで「作成してください」と表示して止まる（初回の1回目はここで止まるのが正常） |
| 6 | データベースを更新 | テーブルの追加・変更があれば反映する。`--first` のときだけ初期データ（10月分の空き状況）も入れる |
| 7 | キャッシュを作り直す | 設定・URL・画面のキャッシュを作り直す（`.env` の変更もここで反映される） |
| 8 | 公開を再開 | メンテナンス表示を解除し、「✓ 配置が完了しました」と表示する |

**実行前と実行後のフォルダ**

```
実行前                                実行後
~/                                    ~/
├── mellow-release.tar.gz             ├── mellow-release.tar.gz   （残る）
├── mellow-release/  ← 展開したもの    ├── mellow-release/         （残る。次回 rm -rf で消す）
└── web/                              ├── mellow-app/             ★アプリ本体（公開されない）
    └── （WordPress）                 │   ├── .env                 （初回だけ手で作る。以後は触らない）
                                      │   └── storage/             （ログ等。以後も残る）
                                      └── web/
                                          ├── （WordPress）         （そのまま）
                                          └── mellow/              ★公開フォルダ
```

### 3-3. deploy.sh がしないこと

- **公開フォルダの切り替え**：ps-mellow.jp をどのフォルダで表示するかは、ロリポップの管理画面（独自ドメイン設定）で、初回に1度だけ `mellow` に変更します（[4-8](#4-8-公開フォルダを切り替える)）。実行しただけでは、サイトの表示は切り替わりません。
- **WordPress や `web/` 内の他のフォルダの変更・削除**
- **管理者アカウントの作成**：初回に `admin:create mariko` を別途実行します（[4-7](#4-7-管理者アカウントを作る)）。
- **tar.gz と展開したフォルダの削除**：次回の更新時に上書き・削除されるので、残しておいて問題ありません。

---

## 4. 初回の公開手順

### 4-1. ロリポップの準備（ユーザー専用ページで行う）

1. **PHPのバージョン**
   「PHP設定」で ps-mellow.jp の PHP を **8.5**（なければ 8.4）に変更する。反映まで5〜10分かかります。
2. **SSH**
   「SSH」の設定画面で SSH を有効にし、次の接続情報を控える。
   - サーバー: `ssh.lolipop.jp` / ポート: `2222`
   - アカウント・パスワード: FTP と同じ
3. **データベース**
   「データベース」で新しくデータベースを作成し、画面に表示される次の情報を控える。
   - サーバー（ホスト名）/ データベース名 / ユーザー名 / パスワード
4. **メールアカウント**（通知メールの送信元）
   「メール」で `info@ps-mellow.jp` などのアカウントを用意し、ユーザー専用ページに表示される**送信（SMTP）サーバーの情報**を控える。
   - SMTPサーバー名・ポート・暗号化方式（SSL/STARTTLS）・ユーザー名（通常はメールアドレス全体）・パスワード

### 4-2. SSH接続を確認する（手元のMacのターミナル）

```bash
ssh -p 2222 アカウント名@ssh.lolipop.jp
# パスワードを入力してログインできたら、続けて確認
pwd                       # /home/users/X/アカウント名 のように表示される
ls                        # web が見えること
/usr/local/bin/php8.5 -v || /usr/local/php/8.5/bin/php -v   # どちらかで PHP 8.5 が表示される
exit
```

> PHP のコマンドの場所はサーバーの世代によって異なります。配置スクリプト（deploy.sh）は自動で探すので、ここでは「どちらかで表示される」ことだけ確認できれば十分です。

毎回パスワードを入力しなくて済むよう、SSH鍵を登録しておくと便利です（任意）。

### 4-3. 公開用ファイルを作る（手元のMac）

```bash
cd ~/work/mellow
docker compose up -d          # まだ起動していなければ
scripts/build-release.sh
```

`release/mellow-release.tar.gz`（約100MB）ができます。中身は次のとおりで、毎回作り直されます。

| 中身 | 配置先 | 内容 |
|---|---|---|
| `mellow-app/` | `~/mellow-app/` | アプリ本体と本番用ライブラリ |
| `public/` | `~/web/mellow/` | `.htaccess`・`index.php`・CSS/JS・画像 |
| `deploy.sh` | （実行するだけ） | 上記の配置・データベース更新・キャッシュ作成を行うスクリプト |

### 4-4. アップロードして配置する（1回目）

tar.gz を置く場所と、`deploy.sh` が何をするかは [3-1〜3-3](#3-1-targz-を置く場所) を参照してください。

```bash
# 手元のMacから送る
scp -P 2222 release/mellow-release.tar.gz アカウント名@ssh.lolipop.jp:~/

# サーバーに入って展開・配置
ssh -p 2222 アカウント名@ssh.lolipop.jp
tar xzf mellow-release.tar.gz
bash mellow-release/deploy.sh --first
```

初回は `.env` がまだ無いため、ファイルを配置したところで次のように表示されて止まります（正常です）。

```
※ /home/users/X/アカウント名/mellow-app/.env がありません。手順書の「.env を作る」に従って作成し、もう一度実行してください。
```

### 4-5. `.env`（本番の設定ファイル）を作る

サーバー上で `~/mellow-app/.env` を作ります。**このファイルは他人に渡さないでください。**

```bash
cd ~/mellow-app
cp .env.example .env
vi .env          # vi が苦手な場合は nano .env
```

内容を次のように書き換えます（`（ ）` の部分は 4-1 で控えた値に置き換える）。

```dotenv
APP_NAME="PHOTO STUDIO MELLOW"
APP_ENV=production
APP_KEY=                                  # 下の「APP_KEY の作り方」で作成した値
APP_DEBUG=false
APP_URL=https://ps-mellow.jp

APP_LOCALE=ja
APP_FALLBACK_LOCALE=ja
APP_FAKER_LOCALE=ja_JP

LOG_CHANNEL=daily
LOG_LEVEL=warning

DB_CONNECTION=mysql
DB_HOST=（データベースのサーバー名）
DB_PORT=3306
DB_DATABASE=（データベース名）
DB_USERNAME=（ユーザー名）
DB_PASSWORD=（パスワード）

SESSION_DRIVER=database
SESSION_SECURE_COOKIE=true
CACHE_STORE=database
QUEUE_CONNECTION=sync

MAIL_MAILER=smtp
MAIL_HOST=（SMTPサーバー名）
MAIL_PORT=465                             # 4-1で控えたポート
MAIL_SCHEME=smtps                         # ポート465(SSL)は smtps、587(STARTTLS)は smtp
MAIL_USERNAME=（メールアドレス全体）
MAIL_PASSWORD=（メールのパスワード）
MAIL_FROM_ADDRESS="info@ps-mellow.jp"
MAIL_FROM_NAME="${APP_NAME}"

# 予約・お問い合わせの通知先
SITE_MAIL_TO=info@ps-mellow.jp

# 本番では空のまま
TRUSTED_PROXIES=
```

| 項目 | 注意 |
|---|---|
| `APP_DEBUG=false` | **必ず false。** true のままだとエラー時にパスワード等が画面に表示されます。 |
| `APP_ENV=production` | 検索エンジンに公開されます（robots.txt が許可になり、noindex が外れる）。 |
| `APP_URL` | `https://` で書き、末尾に `/` を付けない。 |
| `MAIL_FROM_ADDRESS` | ps-mellow.jp のアドレスにする（迷惑メール扱いを防ぐため）。 |

**APP_KEY の作り方**（手元のMacで実行し、表示された `base64:...` をそのまま貼り付ける）

```bash
docker compose exec app php artisan key:generate --show
```

> APP_KEY は一度決めたら変えないでください（変えると管理画面のログインが切れます）。

### 4-6. もう一度配置スクリプトを実行する

```bash
bash ~/mellow-release/deploy.sh --first
```

テーブルの作成、初期データ（旧サイトの10月分の空き状況）の投入、キャッシュの作成まで行い、最後に `✓ 配置が完了しました` と表示されれば成功です。

> `--first` は**初回だけ**付けます。データベースの接続に失敗する場合は、`.env` の `DB_` の値を見直してから再実行してください。

### 4-7. 管理者アカウントを作る

```bash
cd ~/mellow-app
/usr/local/bin/php8.5 artisan admin:create mariko
```

- `php8.5` のパスは 4-2 で表示された方を使ってください（`/usr/local/php/8.5/bin/php artisan ...` の場合もあります）。
- パスワード（8文字以上）の入力を求められます。入力した文字は「•」で表示されます。決めたパスワード（[editing.md の「管理画面のログイン情報」](editing.md#管理画面のログイン情報)）を入力してください。
- ログインはユーザー名（`mariko`）とパスワードで行います。
- 管理画面は `https://ps-mellow.jp/admin` です（切り替え後に使えます）。

### 4-8. 公開フォルダを切り替える

1. ユーザー専用ページの「**独自ドメイン設定**」を開く。
2. ps-mellow.jp の「**確認・変更**」をクリックする。
3. 現在の公開フォルダ名を**メモしておく**（元に戻すときに使う）。
4. 公開フォルダを `mellow` に変更し、「**更新**」をクリックする。
5. 数分おいて `https://ps-mellow.jp/` を開き、新しいサイトが表示されることを確認する。

> 元に戻すには、公開フォルダを手順3でメモした値に戻すだけです。

### 4-9. 動作確認

- [ ] `https://ps-mellow.jp/` が表示され、デザイン（色・フォント）が崩れていない
- [ ] `http://ps-mellow.jp/` を開くと `https://` に切り替わる
- [ ] 画像が表示される／ギャラリーの写真を押すと拡大表示される
- [ ] スマホで、メニューと画面下の予約ボタンが動く
- [ ] 旧URLが転送される
  - `https://ps-mellow.jp/bb/` → トップページ
  - `https://ps-mellow.jp/bb/753plan/` → 七五三プラン（`/plans/shichigosan`）
  - `https://ps-mellow.jp/bb/yoyaku/` → 予約フォーム（`/reservation`）
- [ ] `https://ps-mellow.jp/admin` にログインし、空き枠の切り替えが「空き状況」ページに反映される
- [ ] お問い合わせフォームを送信し、`SITE_MAIL_TO` に通知メールが届く
- [ ] 予約フォームを最後まで進めて送信し、通知メールが届く（テストである旨を記入）
- [ ] 存在しないURL（例 `https://ps-mellow.jp/test`）で「404」と表示される（エラーの詳細が出ない）

### 4-10. 公開後にやること

- **管理画面で空き状況を登録**する（初期データは旧サイトの10月分のみです）。
- **Google Search Console** で `https://ps-mellow.jp/sitemap.xml` を送信する。旧URLは301転送されるので、検索結果は順次新しいURLに置き換わります。
- Googleビジネスプロフィール・Instagram・LINE に載せているURLを新しいURLに更新する（旧URLのままでも転送されます）。
- 新サイトで問題がないことを確認できたら、WordPress のフォルダとデータベースは、バックアップを取ったうえで削除して構いません。

---

## 5. 2回目以降の更新手順

```bash
# 手元のMacで
scripts/build-release.sh
scp -P 2222 release/mellow-release.tar.gz アカウント名@ssh.lolipop.jp:~/

# サーバーで
ssh -p 2222 アカウント名@ssh.lolipop.jp
rm -rf mellow-release && tar xzf mellow-release.tar.gz
bash mellow-release/deploy.sh
```

配置スクリプトは次のことを自動で行います（詳しくは [3-2](#3-2-deploysh-がすること)）。

1. メンテナンス表示に切り替える（作業中にアクセスしたお客様には「メンテナンス中」と表示）
2. アプリ本体と公開フォルダを入れ替える（**`.env` と `storage/`（ログ等）は残す**）
3. データベースの変更があれば反映する（無ければ何もしない）
4. キャッシュを作り直し、メンテナンス表示を解除する

> 更新時は `--first` を付けないでください。

---

## 6. 困ったときは

| 症状 | 主な原因と対処 |
|---|---|
| `bash mellow-release/deploy.sh` で「No such file or directory」 | tar.gz を展開していない、またはホームフォルダ以外にいる。`cd ~` してから `tar xzf mellow-release.tar.gz` を実行し、もう一度実行する（[3-1](#3-1-targz-を置く場所)）。 |
| 「500 Server Error」と表示される | `~/mellow-app/storage/logs/` のログを確認する。よくある原因: `.env` の値の誤り・`APP_KEY` が空・PHPのバージョンが8.4未満（「PHP設定」を確認）。 |
| 「メンテナンス中」のまま戻らない | 更新が途中で止まっています。`cd ~/mellow-app && /usr/local/bin/php8.5 artisan up` で解除し、エラー内容を確認して再実行する。 |
| デザインが崩れる（文字だけになる） | `~/web/mellow/build/` が無い。手元で `build-release.sh` から作り直して配置する。 |
| トップ以外が「404 Not Found」になる | `~/web/mellow/.htaccess` が無い。`ls -a ~/web/mellow` で確認する。 |
| フォーム送信で「送信できませんでした」 | ログを確認。メール設定（`MAIL_HOST`・`MAIL_PORT`・`MAIL_SCHEME`・パスワード）の誤りが多い。 |
| フォーム送信や管理画面の保存が「403 Forbidden」になる | ロリポップの **WAF**（不正アクセス防止機能）が送信内容を誤って遮断している可能性がある。ユーザー専用ページの「WAF設定」でログを確認する。 |
| 管理画面で「ログインの有効期限が切れました」 | ページを再読み込みして再ログイン。頻発する場合は `APP_URL` が `https://` になっているか確認。 |
| `.env` を変えたのに反映されない | `cd ~/mellow-app && /usr/local/bin/php8.5 artisan optimize` を実行する（設定のキャッシュを作り直す）。 |
| 管理者のパスワードを忘れた | `artisan admin:create mariko` を実行すると再設定できる（4-7と同じ）。 |
| `deploy.sh` が「PHP 8.4.1 以上が見つかりません」 | 「PHP設定」で 8.4 以上にしたか確認。場所が特殊な場合は `PHP=/path/to/php bash mellow-release/deploy.sh` で指定できる。 |

ログの場所: `~/mellow-app/storage/logs/laravel-YYYY-MM-DD.log`

---

## 補足: このサイトの公開に関するファイル

| ファイル | 役割 |
|---|---|
| `scripts/build-release.sh` | 手元で公開用ファイル一式（`release/mellow-release.tar.gz`）を作る |
| `scripts/server-deploy.sh` | サーバー上で実行する配置スクリプト（tar.gz に `deploy.sh` として入る） |
| `src/config/pages.php` の `redirects` | 旧URL（`/bb/〇〇`）から新しいページへの転送の対応表 |

公開フォルダの場所を `~/web/mellow` 以外にしたい場合は、`scripts/build-release.sh` の `APP_FROM_PUBLIC`（公開フォルダから見たアプリ本体の場所）と、`deploy.sh` 実行時の環境変数 `PUBLIC_DIR` を合わせて変更してください。
