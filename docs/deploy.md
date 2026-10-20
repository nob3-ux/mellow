# ロリポップへのデプロイ手順

PHOTO STUDIO MELLOW のサイト（Laravel）を、**ロリポップ！レンタルサーバー**の `https://ps-mellow.jp/`（ドメイン直下）に公開・更新するための手順です。

- ソースコードは **GitHub（非公開リポジトリ）** に置き、サーバーは **`git pull` で取り込み**ます。
- サーバーでの作業（Composer・npm・artisan）は、**この手順書のコマンドを1つずつ手動で**実行します。
- **Composer と Node.js（npm）はサーバーに手動で入れ**、PHPのライブラリのインストールと React・CSS のビルドもサーバーで行います。
- 新サイトに関するものは、サーバーの **`~/web/mellow/` の1つのフォルダ**にまとめます。
- 現在の WordPress（`https://ps-mellow.jp/bb/`）はそのまま残し、ドメインの**公開フォルダを切り替える**ことで新サイトに移行します。問題があれば設定を戻すだけで元に戻せます。
- 旧サイトのURL（`/bb/753plan/` など）は、新サイトの対応するページへ自動で転送（301）されます。
- 手元（自分のパソコン）の開発環境の作り方は [setup.md](setup.md)、普段の修正方法は [editing.md](editing.md) を参照してください。

---

## 目次

1. [全体の流れ](#1-全体の流れ)
2. [事前に確認すること](#2-事前に確認すること)
3. [サーバー上のフォルダ構成](#3-サーバー上のフォルダ構成)
4. [初回の公開手順](#4-初回の公開手順)
5. [2回目以降の更新手順](#5-2回目以降の更新手順)
6. [困ったときは](#6-困ったときは)
7. [付録: サーバーで Node.js が動かない場合](#7-付録-サーバーで-nodejs-が動かない場合)

---

## 1. 全体の流れ

```
手元のMac                    GitHub（非公開）          ロリポップ（~/web/mellow）
──────────────────          ────────────────          ─────────────────────────────────
修正して git commit
テスト
git push ─────────────────→ main ブランチ ── git pull ─→ php artisan down
                                                          git pull
                                                          composer install   （PHPのライブラリ）
                                                          npm ci             （ビルド用の道具）
                                                          npm run build      （React・CSS）
                                                          php artisan migrate
                                                          php artisan optimize
                                                          php artisan up
```

| 作業 | 初回 | 更新時 |
|---|:-:|:-:|
| ロリポップの準備（PHP・SSH・データベース・メール） | ○ | |
| GitHub にリポジトリを作り、手元から送る | ○ | |
| サーバーに GitHub 用の鍵（デプロイキー）を作る | ○ | |
| サーバーにリポジトリを取り込む（`git clone`） | ○ | |
| サーバーに Composer・Node.js を入れる | ○ | |
| `.env`（本番の設定ファイル）を作る | ○ | |
| 手元で記録・テスト・`git push` | | ○ |
| サーバーで取り込み・インストール・ビルド・反映（コマンドを手動で実行） | ○ | ○ |
| 管理者アカウントの作成・公開フォルダの切り替え | ○ | |

---

## 2. 事前に確認すること

| 項目 | 必要な条件 | 確認する場所 |
|---|---|---|
| プラン | **スタンダード・ハイスピード・エンタープライズのいずれか**（SSHが使えるプラン） | ユーザー専用ページ |
| PHP | **8.4 以上**（推奨 8.5） | ユーザー専用ページ「PHP設定」 |
| Git | サーバーで `git` コマンドが使えること | SSH（4-4） |
| Node.js が動くこと | サーバーの glibc（OSの基本部品）が **2.28 以上** | SSH（4-5） |
| 空き容量 | 約 1GB（サイト・画像・ライブラリ・Node.js） | ユーザー専用ページ |
| データベース | MySQL（ロリポップで作成） | ユーザー専用ページ「データベース」 |
| SSL | ps-mellow.jp の独自SSLが有効（現在のWordPressで有効なはず） | ユーザー専用ページ「独自SSL証明書導入」 |
| GitHub | アカウントがあり、非公開リポジトリを作れること | https://github.com |

> **PHP 8.3 以下では動きません。** Laravel 13 が使う部品が PHP 8.4.1 以上を必要とします。
> PHP のバージョンはドメインごとに設定します。WordPress（/bb/）も同じドメインのため、PHP を上げると WordPress も新しい PHP で動きます。切り替えまでの間、WordPress の表示に問題がないか確認してください。

> **Node.js について**：ロリポップは Node.js を提供していないため、自分でサーバーに入れます（4-5）。サーバーのOSが古い（glibc が 2.28 未満）と動かないため、その場合は [7章](#7-付録-サーバーで-nodejs-が動かない場合) の方法（ビルドだけ手元で行う）に切り替えます。

---

## 3. サーバー上のフォルダ構成

ロリポップでは、FTPで見える一番上が `web/` フォルダです。新サイトに関するものは**すべて `~/web/mellow/` の1つのフォルダ**にまとめ、ドメインの公開フォルダには、その中の **`mellow/src/public`** を指定します。

```
~/web/                                ← FTPの一番上（/home/users/X/アカウント名/web）
├── （WordPressのフォルダ）            ← 今のまま残す
└── mellow/                           ← GitHub から取り込んだリポジトリ（新サイトのすべて）
    ├── .htaccess                     ← src/public 以外への外部アクセスを拒否（下記）
    ├── .tools/                       ← 4-5 で手動で入れる道具（Gitには記録しない）
    │   ├── bin/php                   ←   PHP 8.5 への近道（リンク）
    │   ├── bin/composer              ←   Composer
    │   └── node/                     ←   Node.js（npm を含む）
    ├── docs/  docker/ ...
    └── src/                          ← Laravel 本体
        ├── .env                      ← サーバー上で作成（Gitには記録されない）
        ├── vendor/                   ← composer install が作る
        ├── node_modules/             ← npm ci が作る（ビルド用の道具）
        ├── storage/                  ← ログ・セッション（更新しても残る）
        └── public/                   ← ★ ps-mellow.jp の公開フォルダ（mellow/src/public）
            └── build/                ←   npm run build が作る CSS・JavaScript
```

> SSH の鍵（`~/.ssh/`）と、コマンドの設定（`~/.bash_profile`）だけは、決まりでホームフォルダに置きます。

**外から見えないようにする仕組み**

`web/` の中は、ロリポップの初期ドメイン（`○○.lolipop.jp` など）から `https://初期ドメイン/mellow/...` の形で直接見えてしまいます。そのため、

- リポジトリの一番上の `.htaccess` で、`src/public` 以外（`.env`・`.git`・`.tools`・`node_modules`・プログラム・ログなど）へのアクセスをすべて拒否（403）しています。
- 初期ドメインから `src/public` を開かれた場合は、`.env` の `APP_URL`（`https://ps-mellow.jp`）へ自動で転送されます（同じ内容が別のURLで検索エンジンに登録されるのを防ぐため）。

公開前に、この保護が効いていることを確認します（[4-10](#4-10-外から見えないことを確認する)）。

> **サーバー上のファイルは直接編集しないでください。** 修正は必ず手元で行い、GitHub 経由で反映します。サーバーで編集すると、次の `git pull` が失敗する原因になります（`.env` だけは例外で、Git に記録されないため編集して構いません）。

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

### 4-2. GitHub にリポジトリを作り、手元から送る

1. GitHub で**非公開（Private）**の空のリポジトリを作る（README などは追加しない）。
2. 手元のMacで、リポジトリの場所を登録して送る。

```bash
cd ~/work/mellow
git remote add origin git@github.com:GitHubのユーザー名/リポジトリ名.git
git push -u origin main
```

> 手元のMacから GitHub へ送るには、Mac側の GitHub 認証（SSH鍵など）が必要です。GitHub の「Settings → SSH and GPG keys」で手元の公開鍵を登録してください。

### 4-3. サーバーに GitHub 用の鍵（デプロイキー）を作る

非公開リポジトリをサーバーから取り込むため、**読み取り専用の鍵**をサーバーに作り、GitHub に登録します。

```bash
# 手元のMacからサーバーに入る
ssh -p 2222 アカウント名@ssh.lolipop.jp

# 鍵を作る
mkdir -p ~/.ssh && chmod 700 ~/.ssh
ssh-keygen -t ed25519 -f ~/.ssh/github_mellow -N "" -C "lolipop-deploy"

# GitHub への接続設定（ポート22が使えない環境でもつながるよう 443 番を使う）
cat >> ~/.ssh/config <<'EOF'
Host github.com
    HostName ssh.github.com
    Port 443
    User git
    IdentityFile ~/.ssh/github_mellow
    IdentitiesOnly yes
EOF
chmod 600 ~/.ssh/config

# 公開鍵を表示してコピーする
cat ~/.ssh/github_mellow.pub
```

GitHub のリポジトリの「**Settings → Deploy keys → Add deploy key**」を開き、表示された1行（`ssh-ed25519 ...`）を貼り付けて保存します（「Allow write access」は**チェックしない**）。

接続を確認します。

```bash
ssh -T git@github.com
# 「Hi GitHubのユーザー名/リポジトリ名! You've successfully authenticated...」と出ればOK
#（初回は「Are you sure you want to continue connecting」と聞かれるので yes）
```

### 4-4. サーバーにリポジトリを取り込む

```bash
git --version                     # git が使えることを確認
cd ~/web
git clone git@github.com:GitHubのユーザー名/リポジトリ名.git mellow
```

- 取り込み先は必ず `~/web/mellow` にしてください（`web` の中の `mellow` フォルダ）。

### 4-5. Composer と Node.js を入れる

**① サーバーの情報を確認する**

```bash
ls /usr/local/bin/php8.5 || ls /usr/local/php/8.5/bin/php    # PHP 8.5 の場所（どちらかが表示される）
ldd --version | head -1                                      # glibc の版（2.28 以上なら Node.js が動く）
uname -m                                                     # x86_64 と表示されること
```

- glibc が **2.28 未満**の場合、この手順の Node.js は動きません。①〜②の Composer までを行い、Node.js は入れずに [7章](#7-付録-サーバーで-nodejs-が動かない場合) に進んでください。
- PHP 8.5 の場所は、以降 `/usr/local/bin/php8.5` として書きます。`/usr/local/php/8.5/bin/php` だった場合は読み替えてください。

**② 道具を置くフォルダを作り、PHP と Composer を入れる**

```bash
mkdir -p ~/web/mellow/.tools/bin
cd ~/web/mellow/.tools

# PHP 8.5 への近道を作る（以降 php と打つだけで 8.5 が使われる）
ln -sf /usr/local/bin/php8.5 bin/php

# Composer を入れる
curl -fsSL https://getcomposer.org/installer -o composer-setup.php
bin/php composer-setup.php --install-dir=bin --filename=composer
rm composer-setup.php
```

**③ Node.js を入れる**（Node.js 24 の最新版）

```bash
cd ~/web/mellow/.tools
NODE_FILE=$(curl -fsSL https://nodejs.org/dist/latest-v24.x/SHASUMS256.txt | grep -oE 'node-v[0-9.]+-linux-x64\.tar\.gz' | head -1)
echo $NODE_FILE                   # node-v24.xx.x-linux-x64.tar.gz と表示される
curl -fsSLO https://nodejs.org/dist/latest-v24.x/$NODE_FILE
tar xzf $NODE_FILE && rm $NODE_FILE
mv node-v*-linux-x64 node
```

**④ コマンドを使えるように設定する**（ログインのたびに自動で読み込まれる）

```bash
cat >> ~/.bash_profile <<'EOF'
# PHOTO STUDIO MELLOW: PHP 8.5・Composer・Node.js（~/web/mellow/.tools）
export PATH="$HOME/web/mellow/.tools/bin:$HOME/web/mellow/.tools/node/bin:$PATH"
export COMPOSER_MEMORY_LIMIT=-1
EOF
source ~/.bash_profile
```

`COMPOSER_MEMORY_LIMIT=-1` は、レンタルサーバーのメモリ制限で Composer が止まるのを防ぐ設定です。

**⑤ 確認する**

```bash
php -v          # PHP 8.5.x
composer -V     # Composer version 2.x
node -v         # v24.x.x
npm -v          # 11.x
```

4つとも表示されれば完了です。`node -v` でエラー（`GLIBC_2.28 not found` など）が出た場合は、Node.js がこのサーバーでは動きません。`rm -rf ~/web/mellow/.tools/node` で削除し、[7章](#7-付録-サーバーで-nodejs-が動かない場合) に進んでください。

### 4-6. ライブラリを入れて、React・CSS をビルドする

```bash
cd ~/web/mellow/src
composer install --no-dev --optimize-autoloader     # PHPのライブラリ（vendor/）
npm ci                                              # ビルド用の道具（node_modules/）
npm run build                                       # React・CSS をビルド（public/build/）
```

最後に `✓ built in ...` と表示されれば成功です。

| コマンド | 内容 |
|---|---|
| `composer install --no-dev --optimize-autoloader` | `composer.lock` に書かれた版どおりに、本番用の PHP ライブラリだけを入れる |
| `npm ci` | `package-lock.json` に書かれた版どおりに、ビルド用の道具を入れ直す（手元と同じ版になる） |
| `npm run build` | 予約フォーム・カレンダー等の React と CSS を本番用に変換する |

### 4-7. `.env`（本番の設定ファイル）を作る

サーバー上で `~/web/mellow/src/.env` を作ります。**このファイルは Git に記録されず、GitHub にも送られません。他人に渡さないでください。**

```bash
cd ~/web/mellow/src
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

### 4-8. データベースを準備し、キャッシュを作る

```bash
cd ~/web/mellow/src
chmod -R u+rwX,go+rX storage bootstrap/cache       # ログ・キャッシュを書き込めるようにする
php artisan migrate --seed --force                  # テーブル作成＋初期データ（10月分の空き状況）
php artisan optimize                                # 設定・URL・画面のキャッシュを作る
```

- `--seed`（初期データの投入）は**初回だけ**です。
- データベースの接続に失敗する場合は、`.env` の `DB_` の値を見直してから再実行してください。

### 4-9. 管理者アカウントを作る

```bash
cd ~/web/mellow/src
php artisan admin:create mariko
```

- パスワード（8文字以上）の入力を求められます。入力した文字は「•」で表示されます。決めておいたパスワードを入力してください。
- ログインはユーザー名（`mariko`）とパスワードで行います。

### 4-10. 外から見えないことを確認する

ロリポップの初期ドメイン（ユーザー専用ページに表示される `○○.lolipop.jp` などのドメイン）で、次のURLをブラウザで開きます。

| URL | 正しい結果 |
|---|---|
| `https://初期ドメイン/mellow/src/.env` | **403 Forbidden**（中身が表示されない） |
| `https://初期ドメイン/mellow/.git/config` | **403 Forbidden** |
| `https://初期ドメイン/mellow/.tools/bin/composer` | **403 Forbidden** |
| `https://初期ドメイン/mellow/src/public/` | `https://ps-mellow.jp/` へ移動する（切り替え前は表示されなくて構いません） |

> `.env` の中身が表示された場合は、**公開フォルダの切り替えをせず**、`~/web/mellow/.htaccess` があるか（`ls -a ~/web/mellow`）を確認してください。

### 4-11. 公開フォルダを切り替える

1. ユーザー専用ページの「**独自ドメイン設定**」を開く。
2. ps-mellow.jp の「**確認・変更**」をクリックする。
3. 現在の公開フォルダ名を**メモしておく**（元に戻すときに使う）。
4. 公開フォルダを `mellow/src/public` に変更し、「**更新**」をクリックする。
5. 数分おいて `https://ps-mellow.jp/` を開き、新しいサイトが表示されることを確認する。

> 元に戻すには、公開フォルダを手順3でメモした値に戻すだけです。

### 4-12. 動作確認

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
- [ ] 初期ドメインから `.env` などが見えない（4-10 をもう一度確認）

### 4-13. 公開後にやること

- **管理画面で空き状況を登録**する（初期データは旧サイトの10月分のみです）。
- **Google Search Console** で `https://ps-mellow.jp/sitemap.xml` を送信する。旧URLは301転送されるので、検索結果は順次新しいURLに置き換わります。
- Googleビジネスプロフィール・Instagram・LINE に載せているURLを新しいURLに更新する（旧URLのままでも転送されます）。
- 新サイトで問題がないことを確認できたら、WordPress のフォルダとデータベースは、バックアップを取ったうえで削除して構いません。

---

## 5. 2回目以降の更新手順

### 5-1. 手元のMac（記録・テスト・送信）

```bash
cd ~/work/mellow

# 1. 修正を記録する（何を変えたかを書く）
git add -A
git commit -m "七五三の料金を変更"

# 2. 自動テスト（failed が無いことを確認）
docker compose exec app php artisan test --compact

# 3. GitHub へ送る
git push origin main
```

> テストで `failed` が出たら、送らずに原因を直してください。React・CSS のビルドはサーバーで行うので、手元でのビルドは不要です。

### 5-2. サーバー（取り込み・反映）

```bash
ssh -p 2222 アカウント名@ssh.lolipop.jp
cd ~/web/mellow/src

php artisan down --retry=30                         # 1. メンテナンス表示にする
git pull                                            # 2. GitHub から取り込む
composer install --no-dev --optimize-autoloader     # 3. PHPのライブラリを更新
npm ci                                              # 4. ビルド用の道具を更新
npm run build                                       # 5. React・CSS をビルド
php artisan migrate --force                         # 6. データベースの変更を反映
php artisan optimize                                # 7. キャッシュを作り直す
php artisan up                                      # 8. メンテナンス表示を解除

exit
```

| 手順 | 内容と注意 |
|---|---|
| 1. メンテナンス表示 | 作業中にアクセスした人には「メンテナンス中」と表示されます。**途中でエラーが出ても、最後に必ず `php artisan up` を実行してください**（忘れるとメンテナンス表示のままになります）。 |
| 2. 取り込み | `Already up to date.` と出たら、送った内容がまだ GitHub に無い（手元で `git push` したか確認）。 |
| 3〜4. ライブラリ | 変更が無ければすぐ終わります。`npm ci` は毎回入れ直すため1分ほどかかります。 |
| 5. ビルド | `✓ built in ...` と出れば成功。**毎回必ず実行してください**（忘れると React や見た目の修正が反映されません）。 |
| 6. データベース | 変更が無ければ `Nothing to migrate.` と出ます。`--seed` は付けない。 |
| 7. キャッシュ | `.env` を変えたときも、これを実行すると反映されます。 |

`.env`・ログ・データベース（空き状況・管理者）はそのまま残ります。

### 5-3. 公開した内容を元に戻したいとき

手元で問題のある変更を取り消す記録を作り、もう一度公開します。

```bash
git log --oneline              # 取り消したい記録のIDを確認
git revert 記録のID             # その変更を打ち消す記録を作る
# このあと 5-1 の手順 2〜3（テスト・git push）→ 5-2（サーバー）
```

---

## 6. 困ったときは

| 症状 | 主な原因と対処 |
|---|---|
| 「メンテナンス中」のまま戻らない | `cd ~/web/mellow/src && php artisan up` で解除する。そのあと、途中で止まった手順から続きを実行する。 |
| `php` `composer` `npm` が「command not found」 | 4-5 ④の設定が読み込まれていない。`source ~/.bash_profile` を実行する（次回ログインからは自動）。 |
| `php -v` が古いバージョンを表示する | 4-5 ②の近道（`~/web/mellow/.tools/bin/php`）と4-5 ④の設定を確認する。 |
| `ssh -T git@github.com` で接続できない | 4-3 の `~/.ssh/config` と、GitHub のデプロイキーの登録を確認する。`ssh -vT git@github.com` で詳しい原因が表示される。 |
| `git clone`・`git pull` で「Permission denied (publickey)」 | デプロイキーが登録されていない、または別のリポジトリに登録している。 |
| `git pull` で失敗する | サーバー上でファイルを編集した可能性がある。`cd ~/web/mellow && git status` で確認し、`git checkout -- .` で元に戻してから再実行する（`.env` は消えない）。 |
| `git push`（手元）が「rejected」で失敗する | GitHub 側に手元に無い記録がある（別のパソコンで送った等）。`git pull` してから再実行する。 |
| `composer install` で失敗する | 通信エラーなら再実行する。メモリ不足と表示される場合は、`echo $COMPOSER_MEMORY_LIMIT` で `-1` と出るか確認する（4-5 ④）。 |
| `node -v`・`npm ci` で「GLIBC_2.xx not found」 | サーバーのOSが古く Node.js が動かない。[7章](#7-付録-サーバーで-nodejs-が動かない場合) の方法に切り替える。 |
| `npm ci` が途中で止まる・失敗する | 通信エラーなら再実行する。`Killed` と出る場合はメモリ不足の可能性があるため、時間をおいて再実行するか、[7章](#7-付録-サーバーで-nodejs-が動かない場合) の方法に切り替える。 |
| `npm run build` が失敗する | 表示されたエラーを確認する。手元（`docker compose run --rm --no-deps node npm run build`）でも失敗するならプログラムの問題なので、手元で直して送り直す。 |
| デザインが崩れる・React の修正が反映されない | `npm run build` を実行し忘れた可能性がある。5-2 の手順 5・7 を実行する。 |
| 「500 Server Error」と表示される | `~/web/mellow/src/storage/logs/` のログを確認する。よくある原因: `.env` の値の誤り・`APP_KEY` が空・PHPのバージョンが8.4未満（「PHP設定」を確認）・`vendor` が無い（`composer install` の実行忘れ）。 |
| フォーム送信で「送信できませんでした」 | ログを確認。メール設定（`MAIL_HOST`・`MAIL_PORT`・`MAIL_SCHEME`・パスワード）の誤りが多い。 |
| フォーム送信や管理画面の保存が「403 Forbidden」になる | ロリポップの **WAF**（不正アクセス防止機能）が送信内容を誤って遮断している可能性がある。ユーザー専用ページの「WAF設定」でログを確認する。 |
| `.env` を変えたのに反映されない | `cd ~/web/mellow/src && php artisan optimize` を実行する（キャッシュが作り直される）。 |
| 管理者のパスワードを忘れた | `cd ~/web/mellow/src && php artisan admin:create mariko` で再設定できる。 |
| `git clone` で「already exists and is not an empty directory」 | `~/web/mellow` が既にある。中身を確認し、`mv ~/web/mellow ~/web/mellow-old` で退避してから取り込み直す。 |

ログの場所: `~/web/mellow/src/storage/logs/laravel-YYYY-MM-DD.log`

---

## 7. 付録: サーバーで Node.js が動かない場合

サーバーのOSが古く Node.js が動かない場合は、**React・CSS のビルドだけ手元で行い、できたファイル（`src/public/build`）をサーバーへ送ります**。それ以外の手順（Composer・artisan）は変わりません。

**初回**：4-5 は ①・②・④（Node.js の行を除く）・⑤ の `php -v`・`composer -V` まで行い、4-6 は `composer install` だけを実行します。そのあと、下の「手元でビルドして送る」を行ってから 4-7 に進みます。

**更新時**：5-2 の手順 4（`npm ci`）・5（`npm run build`）の代わりに、下の「手元でビルドして送る」を行います（5-2 の手順 2 の `git pull` のあと、手順 6 の前）。

**手元でビルドして送る**（手元のMacで実行）

```bash
cd ~/work/mellow
docker compose run --rm --no-deps node npm run build
scp -P 2222 -r src/public/build アカウント名@ssh.lolipop.jp:~/web/mellow/src/public/
```

> 送ったあとは、サーバーで `php artisan optimize` を実行してください。

---

## 補足: このサイトの公開に関するファイル

| ファイル | 役割 |
|---|---|
| `.htaccess`（リポジトリ直下） | `src/public` 以外への外部アクセスを拒否する（本番サーバー用） |
| `src/public/.htaccess` | `http://` を `https://` へ転送する設定を含む（手元の開発環境では使われない） |
| `src/app/Http/Middleware/RedirectToCanonicalHost.php` | 本番で `APP_URL` 以外のURLからのアクセスを `APP_URL` へ転送する |
| `src/config/pages.php` の `redirects` | 旧URL（`/bb/〇〇`）から新しいページへの転送の対応表 |
| `src/composer.json`・`composer.lock` | PHP のライブラリの一覧と版（`composer install` が参照） |
| `src/package.json`・`package-lock.json` | ビルド用の道具の一覧と版（`npm ci` が参照） |
