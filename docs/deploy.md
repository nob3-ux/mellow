# ロリポップへのデプロイ手順

PHOTO STUDIO MELLOW のサイト（Laravel）を、**ロリポップ！レンタルサーバー**の `https://ps-mellow.jp/`（ドメイン直下）に公開・更新するための手順です。

- ソースコードは **GitHub（非公開リポジトリ）** に置き、サーバーは **`git pull` で取り込んで** 反映します。
- React・CSS のビルドは**手元のMacで行い、ビルド結果も Git に記録**します。サーバーに Node.js は不要です。サーバーでは PHP のライブラリ（Composer）だけをインストールします。
- 現在の WordPress（`https://ps-mellow.jp/bb/`）はそのまま残し、ドメインの**公開フォルダを切り替える**ことで新サイトに移行します。問題があれば設定を戻すだけで元に戻せます。
- 旧サイトのURL（`/bb/753plan/` など）は、新サイトの対応するページへ自動で転送（301）されます。

---

## 目次

1. [全体の流れ](#1-全体の流れ)
2. [事前に確認すること](#2-事前に確認すること)
3. [サーバー上のフォルダ構成と deploy.sh の動き](#3-サーバー上のフォルダ構成と-deploysh-の動き)
4. [初回の公開手順](#4-初回の公開手順)
5. [2回目以降の更新手順](#5-2回目以降の更新手順)
6. [困ったときは](#6-困ったときは)

---

## 1. 全体の流れ

```
手元のMac                         GitHub（非公開）              ロリポップ
────────────────────             ────────────────             ─────────────────────────────
修正して git commit
テスト（php artisan test）
本番用ビルド（npm run build）
ビルド結果を git commit
git push ───────────────────────→ main ブランチ
                                       │
                                       └──── git pull ────→ ~/web/mellow/        （すべてここ）
                                                             bash ~/web/mellow/scripts/deploy.sh
                                                               └ Composer・DB更新・キャッシュ
                                                             ドメインの公開フォルダ = mellow/src/public
```

| 作業 | 初回 | 更新時 |
|---|:-:|:-:|
| ロリポップの準備（PHP・SSH・データベース・メール） | ○ | |
| GitHub にリポジトリを作り、手元から送る | ○ | |
| サーバーに GitHub 用の鍵（デプロイキー）を作る | ○ | |
| サーバーにリポジトリを取り込む（`git clone`） | ○ | |
| `.env`（本番の設定ファイル）を作る | ○ | |
| 手元でテスト・本番用ビルド・記録・`git push` | | ○ |
| サーバーで `scripts/deploy.sh`（取り込み・反映） | ○ | ○ |
| 管理者アカウントの作成・公開フォルダの切り替え | ○ | |

---

## 2. 事前に確認すること

| 項目 | 必要な条件 | 確認する場所 |
|---|---|---|
| プラン | **スタンダード・ハイスピード・エンタープライズのいずれか**（SSHが使えるプラン） | ユーザー専用ページ |
| PHP | **8.4 以上**（推奨 8.5） | ユーザー専用ページ「PHP設定」 |
| Git | サーバーで `git` コマンドが使えること（4-4で確認） | SSH |
| データベース | MySQL（ロリポップで作成） | ユーザー専用ページ「データベース」 |
| SSL | ps-mellow.jp の独自SSLが有効（現在のWordPressで有効なはず） | ユーザー専用ページ「独自SSL証明書導入」 |
| GitHub | アカウントがあり、非公開リポジトリを作れること | https://github.com |

> **PHP 8.3 以下では動きません。** Laravel 13 が使う部品が PHP 8.4.1 以上を必要とします。
> PHP のバージョンはドメインごとに設定します。WordPress（/bb/）も同じドメインのため、PHP を上げると WordPress も新しい PHP で動きます。切り替えまでの間、WordPress の表示に問題がないか確認してください。

---

## 3. サーバー上のフォルダ構成と deploy.sh の動き

### 3-1. フォルダ構成

ロリポップでは、FTPで見える一番上が `web/` フォルダです。新サイトに関するものは**すべて `~/web/mellow/` の1つのフォルダ**にまとめ、ドメインの公開フォルダには、その中の **`mellow/src/public`** を指定します。

```
~/web/                                ← FTPの一番上（/home/users/X/アカウント名/web）
├── （WordPressのフォルダ）            ← 今のまま残す
└── mellow/                           ← GitHub から取り込んだリポジトリ（新サイトのすべて）
    ├── .htaccess                     ← src/public 以外への外部アクセスを拒否（下記）
    ├── composer.phar                 ← deploy.sh が初回に自動でダウンロード（Gitには記録しない）
    ├── docs/  scripts/  docker/ ...
    └── src/                          ← Laravel 本体
        ├── .env                      ← サーバー上で作成（Gitには記録されない）
        ├── vendor/                   ← deploy.sh が Composer でインストール
        ├── storage/                  ← ログ・セッション（更新しても残る）
        └── public/                   ← ★ ps-mellow.jp の公開フォルダ（mellow/src/public）
```

> SSH の鍵（`~/.ssh/`）だけは、SSH の決まりでホームフォルダに置きます（4-3）。

**外から見えないようにする仕組み**

`web/` の中は、ロリポップの初期ドメイン（`○○.lolipop.jp` など）から `https://初期ドメイン/mellow/...` の形で直接見えてしまいます。そのため、

- リポジトリの一番上の `.htaccess` で、`src/public` 以外（`.env`・`.git`・プログラム・ログなど）へのアクセスをすべて拒否（403）しています。
- 初期ドメインから `src/public` を開かれた場合は、`.env` の `APP_URL`（`https://ps-mellow.jp`）へ自動で転送されます（同じ内容が別のURLで検索エンジンに登録されるのを防ぐため）。

公開後に、この保護が効いていることを確認します（[4-10](#4-10-動作確認)）。

> **サーバー上のファイルは直接編集しないでください。** 修正は必ず手元で行い、GitHub 経由で反映します。サーバーで編集すると、次の `git pull` が失敗する原因になります（`.env` だけは例外で、Git に記録されないため編集して構いません）。

### 3-2. deploy.sh がすること

`bash ~/web/mellow/scripts/deploy.sh` を実行すると、次の順に処理します。

| 順 | 処理 | 内容 |
|:-:|---|---|
| 1 | PHPを探す | 8.4以上のPHPを自動で探す（`/usr/local/bin/php8.5` など）。見つからなければ止まる |
| 2 | メンテナンス表示 | 2回目以降のみ。作業中にアクセスした人には「メンテナンス中」と表示される |
| 3 | 取り込み | `git pull` で GitHub の最新の内容を取り込む |
| 4 | ライブラリ | Composer で本番用ライブラリ（`vendor`）をインストール。Composer が無ければ `~/web/mellow/composer.phar` に自動でダウンロード |
| 5 | `.env` の確認 | `~/web/mellow/src/.env` が無ければ「作成してください」と表示して止まる（初回の1回目はここで止まるのが正常） |
| 6 | データベース | テーブルの追加・変更があれば反映。`--first` のときだけ初期データ（10月分の空き状況）も入れる |
| 7 | キャッシュ | 設定・URL・画面のキャッシュを作り直す（`.env` の変更もここで反映される） |
| 8 | 公開を再開 | メンテナンス表示を解除し、「✓ 本番に反映しました」と表示する |

途中で失敗した場合も、メンテナンス表示は自動で解除されます。

### 3-3. deploy.sh がしないこと

- **公開フォルダの切り替え**：ps-mellow.jp をどのフォルダで表示するかは、ロリポップの管理画面で初回に1度だけ `mellow/src/public` に変更します（[4-9](#4-9-公開フォルダを切り替える)）。
- **WordPress や `web/` 内の他のフォルダの変更・削除**
- **管理者アカウントの作成**：初回に `admin:create mariko` を別途実行します（[4-8](#4-8-管理者アカウントを作る)）。
- **React・CSS のビルド**：手元で `npm run build` を実行し、ビルド結果を記録して GitHub に送ります（[5章](#5-2回目以降の更新手順)）。

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

### 4-4. サーバーにリポジトリを取り込み、1回目の配置をする

```bash
git --version                     # git が使えることを確認
cd ~/web
git clone git@github.com:GitHubのユーザー名/リポジトリ名.git mellow
bash ~/web/mellow/scripts/deploy.sh --first
```

- 取り込み先は必ず `~/web/mellow` にしてください（`web` の中の `mellow` フォルダ）。
- 初回は `.env` がまだ無いため、ライブラリのインストールと公開フォルダのリンク作成のあと、次のように表示されて止まります（正常です）。

```
※ /home/users/X/アカウント名/web/mellow/src/.env がありません。docs/deploy.md の「.env を作る」に従って作成し、もう一度実行してください。
```

### 4-5. `.env`（本番の設定ファイル）を作る

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

### 4-6. もう一度配置スクリプトを実行する

```bash
bash ~/web/mellow/scripts/deploy.sh --first
```

テーブルの作成、初期データ（旧サイトの10月分の空き状況）の投入、キャッシュの作成まで行い、最後に `✓ 本番に反映しました` と表示されれば成功です。

> `--first` は**初回だけ**付けます。データベースの接続に失敗する場合は、`.env` の `DB_` の値を見直してから再実行してください。

### 4-7. 外から見えないことを確認する

ロリポップの初期ドメイン（ユーザー専用ページの「サーバーの管理・設定」等に表示される `○○.lolipop.jp` などのドメイン）で、次のURLをブラウザで開きます。

| URL | 正しい結果 |
|---|---|
| `https://初期ドメイン/mellow/src/.env` | **403 Forbidden**（中身が表示されない） |
| `https://初期ドメイン/mellow/.git/config` | **403 Forbidden** |
| `https://初期ドメイン/mellow/src/public/` | `https://ps-mellow.jp/` へ移動する（切り替え前は表示されなくて構いません） |

> `.env` の中身が表示された場合は、**すぐに公開フォルダの切り替えをやめ**、`~/web/mellow/.htaccess` があるか（`ls -a ~/web/mellow`）を確認してください。

### 4-8. 管理者アカウントを作る

```bash
cd ~/web/mellow/src
/usr/local/bin/php8.5 artisan admin:create mariko
```

- `php8.5` の場所は環境によって `/usr/local/php/8.5/bin/php` の場合もあります（`deploy.sh` の最初に表示された `PHP:` のパスを使ってください）。
- パスワード（8文字以上）の入力を求められます。入力した文字は「•」で表示されます。決めておいたパスワードを入力してください。
- ログインはユーザー名（`mariko`）とパスワードで行います。

### 4-9. 公開フォルダを切り替える

1. ユーザー専用ページの「**独自ドメイン設定**」を開く。
2. ps-mellow.jp の「**確認・変更**」をクリックする。
3. 現在の公開フォルダ名を**メモしておく**（元に戻すときに使う）。
4. 公開フォルダを `mellow/src/public` に変更し、「**更新**」をクリックする。
5. 数分おいて `https://ps-mellow.jp/` を開き、新しいサイトが表示されることを確認する。

> 元に戻すには、公開フォルダを手順3でメモした値に戻すだけです。

### 4-10. 動作確認

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
- [ ] 初期ドメインから `.env` などが見えない（4-7 をもう一度確認）

### 4-11. 公開後にやること

- **管理画面で空き状況を登録**する（初期データは旧サイトの10月分のみです）。
- **Google Search Console** で `https://ps-mellow.jp/sitemap.xml` を送信する。旧URLは301転送されるので、検索結果は順次新しいURLに置き換わります。
- Googleビジネスプロフィール・Instagram・LINE に載せているURLを新しいURLに更新する（旧URLのままでも転送されます）。
- 新サイトで問題がないことを確認できたら、WordPress のフォルダとデータベースは、バックアップを取ったうえで削除して構いません。

---

## 5. 2回目以降の更新手順

**手元のMac**（開発環境を起動した状態で、次を順に実行）

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

| 手順 | 注意 |
|---|---|
| 2. テスト | `failed` が出たら、送らずに原因を直してください。 |
| 3. ビルド | **毎回必ず実行してください。** 本番サーバーではビルドできないため、ここで作った CSS・JavaScript がそのまま本番で使われます。忘れると、React（予約フォーム・カレンダー等）や見た目の修正が本番に反映されません。 |
| 4. ビルド結果の記録 | 最後の `src/public/build` の指定で、ビルド結果だけを記録します（他の変更が混ざらない）。 |
| 5. 送信 | 送っただけでは本番は変わりません。続けてサーバーで反映します。 |

**サーバー**

```bash
ssh -p 2222 アカウント名@ssh.lolipop.jp
bash ~/web/mellow/scripts/deploy.sh
exit
```

`✓ 本番に反映しました（記録のID 内容）` と表示されれば完了です。作業中（数十秒）はサイトに「メンテナンス中」と表示されます。`.env`・ログ・データベース（空き状況・管理者）はそのまま残ります。

> 更新時は `--first` を付けないでください。

### 公開した内容を元に戻したいとき

手元で問題のある変更を取り消す記録を作り、もう一度公開します。

```bash
git log --oneline              # 取り消したい記録のIDを確認
git revert 記録のID             # その変更を打ち消す記録を作る
# このあと 5章の手順 2〜5（テスト・ビルド・記録・git push）→ サーバーで deploy.sh
```

---

## 6. 困ったときは

| 症状 | 主な原因と対処 |
|---|---|
| `ssh -T git@github.com` で接続できない | 4-3 の `~/.ssh/config` と、GitHub のデプロイキーの登録を確認する。`ssh -vT git@github.com` で詳しい原因が表示される。 |
| `git clone`・`git pull` で「Permission denied (publickey)」 | デプロイキーが登録されていない、または別のリポジトリに登録している。 |
| `deploy.sh` の `git pull` で失敗する | サーバー上でファイルを編集した可能性がある。`cd ~/web/mellow && git status` で確認し、`git checkout -- .` で元に戻してから再実行する（`.env` は消えない）。 |
| `git push` が「rejected」で失敗する | GitHub 側に手元に無い記録がある（別のパソコンで送った等）。`git pull` してから再実行する。 |
| Composer のインストールで失敗する | 通信エラーなら再実行する。メモリ不足と表示される場合は、時間をおいて再実行するか、ロリポップのサポートに相談する。 |
| 「500 Server Error」と表示される | `~/web/mellow/src/storage/logs/` のログを確認する。よくある原因: `.env` の値の誤り・`APP_KEY` が空・PHPのバージョンが8.4未満（「PHP設定」を確認）。 |
| 「メンテナンス中」のまま戻らない | `cd ~/web/mellow/src && /usr/local/bin/php8.5 artisan up` で解除し、エラー内容を確認して再実行する。 |
| デザインが崩れる・React の修正が反映されない | ビルドを忘れて送った可能性がある。手元で 5章の手順 3〜5（ビルド・記録・`git push`）を行い、サーバーで `deploy.sh` を実行する。 |
| フォーム送信で「送信できませんでした」 | ログを確認。メール設定（`MAIL_HOST`・`MAIL_PORT`・`MAIL_SCHEME`・パスワード）の誤りが多い。 |
| フォーム送信や管理画面の保存が「403 Forbidden」になる | ロリポップの **WAF**（不正アクセス防止機能）が送信内容を誤って遮断している可能性がある。ユーザー専用ページの「WAF設定」でログを確認する。 |
| `.env` を変えたのに反映されない | `bash ~/web/mellow/scripts/deploy.sh` を実行する（キャッシュが作り直される）。 |
| 管理者のパスワードを忘れた | `cd ~/web/mellow/src && /usr/local/bin/php8.5 artisan admin:create mariko` で再設定できる。 |
| `deploy.sh` が「PHP 8.4.1 以上が見つかりません」 | 「PHP設定」で 8.4 以上にしたか確認。場所が特殊な場合は `PHP=/path/to/php bash ~/web/mellow/scripts/deploy.sh` で指定できる。 |
| `git clone` で「already exists and is not an empty directory」 | `~/web/mellow` が既にある。中身を確認し、`mv ~/web/mellow ~/web/mellow-old` で退避してから取り込み直す。 |

ログの場所: `~/web/mellow/src/storage/logs/laravel-YYYY-MM-DD.log`

---

## 補足: このサイトの公開に関するファイル

| ファイル | 役割 |
|---|---|
| `scripts/deploy.sh` | サーバーで `git pull`・Composer・DB更新・キャッシュ作成を行う |
| `src/public/build/` | 本番用にビルドした CSS・JavaScript（手元で `npm run build` して記録する） |
| `.htaccess`（リポジトリ直下） | `src/public` 以外への外部アクセスを拒否する（本番サーバー用） |
| `src/public/.htaccess` | `http://` を `https://` へ転送する設定を含む（手元の開発環境では使われない） |
| `src/app/Http/Middleware/RedirectToCanonicalHost.php` | 本番で `APP_URL` 以外のURLからのアクセスを `APP_URL` へ転送する |
| `src/config/pages.php` の `redirects` | 旧URL（`/bb/〇〇`）から新しいページへの転送の対応表 |
