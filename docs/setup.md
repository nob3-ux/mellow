# 開発環境の作り方（メンバー向け）

PHOTO STUDIO MELLOW のサイトを、**自分のパソコンで動かして修正できるようにする**ための手順です。初めて参加するときに1回だけ行います。

- サイトは **Docker** の中で動きます。パソコンに PHP・MySQL・Node.js などを直接入れる必要はありません。
- 環境ができたら、普段の修正方法は [editing.md](editing.md) を参照してください。
- 本番（ロリポップ）への公開は担当者が行います（[deploy.md](deploy.md)）。

---

## 目次

1. [必要なもの](#1-必要なもの)
2. [GitHub の準備](#2-github-の準備)
3. [リポジトリを取り込む](#3-リポジトリを取り込む)
4. [初回の起動](#4-初回の起動)
5. [動いているか確認する](#5-動いているか確認する)
6. [毎日の使い方](#6-毎日の使い方)
7. [他のメンバーの変更を取り込む](#7-他のメンバーの変更を取り込む)
8. [困ったときは](#8-困ったときは)

---

## 1. 必要なもの

| もの | 用途 | 入手先 |
|---|---|---|
| **Docker Desktop** | サイトを動かす | https://www.docker.com/products/docker-desktop/ |
| **Git** | 変更の記録・GitHub とのやり取り | Mac は最初から入っている（無い場合は下記） |
| **VS Code**（推奨） | ファイルの編集 | https://code.visualstudio.com/ |
| **GitHub アカウント** | リポジトリ（非公開）への参加 | https://github.com |
| 空き容量 | 約 5GB（Docker のイメージ・ライブラリ・写真） | |

### Mac の場合

- Docker Desktop は、お使いの Mac に合わせて「Apple Silicon（M1〜）」または「Intel」版を入れてください。どちらでも動きます。
- ターミナルで `git --version` と打って、版が表示されれば Git は入っています。「インストールしますか」と聞かれたら「インストール」を選んでください。

### Windows の場合

Windows では、**WSL2（Windows の中で動く Linux）** を使います。

1. 「ターミナル」または「PowerShell」を**管理者として**開き、`wsl --install` を実行して再起動する（Ubuntu が入ります）。
2. Docker Desktop を入れ、設定の「Resources → WSL integration」で Ubuntu を有効にする。
3. 以降のコマンドは、すべて **Ubuntu のターミナル**で実行する。
4. リポジトリは **Ubuntu 側のフォルダ（`~/work`）** に取り込む（`C:\` 側（`/mnt/c/...`）に置くと非常に遅くなります）。
5. 改行コードの設定を合わせておく。

```bash
git config --global core.autocrlf input
```

VS Code から Ubuntu 側のフォルダを開くには、拡張機能「WSL」を入れ、Ubuntu のターミナルで `code .` と打ちます。

---

## 2. GitHub の準備

### 2-1. リポジトリに招待してもらう

リポジトリは非公開です。管理者に自分の **GitHub のユーザー名** を伝え、招待してもらってください（管理者は GitHub のリポジトリの「Settings → Collaborators → Add people」から招待します）。届いた招待メールの「Accept invitation」を押すと参加できます。

### 2-2. パソコンを GitHub に登録する（SSH 鍵）

GitHub とやり取りするための鍵を作り、GitHub に登録します（すでに登録済みなら不要です）。

```bash
ssh-keygen -t ed25519 -C "自分のメールアドレス"   # 質問にはすべて Enter
cat ~/.ssh/id_ed25519.pub                           # 表示された1行をコピー
```

GitHub の右上のアイコン →「**Settings → SSH and GPG keys → New SSH key**」を開き、コピーした1行を貼り付けて保存します。

接続を確認します。

```bash
ssh -T git@github.com
# 「Hi ユーザー名! You've successfully authenticated...」と出ればOK
#（初回は「Are you sure you want to continue connecting」と聞かれるので yes）
```

### 2-3. 記録者の名前を設定する

変更を記録（コミット）したときに残る名前です。

```bash
git config --global user.name "自分の名前"
git config --global user.email "GitHubに登録したメールアドレス"
```

---

## 3. リポジトリを取り込む

```bash
mkdir -p ~/work
cd ~/work
git clone git@github.com:GitHubのユーザー名/リポジトリ名.git mellow
cd mellow
```

- 取り込み先のURLは、GitHub のリポジトリのページの「**Code → SSH**」でコピーできます。
- 以降のコマンドは、すべて `~/work/mellow` フォルダで実行します。

---

## 4. 初回の起動

**Docker Desktop を起動してから**、次を順に実行します。

```bash
cd ~/work/mellow

# 1. 設定ファイルを作る（開発環境用の値が入った見本をコピー）
cp src/.env.example src/.env

# 2. PHP の入った環境を作る（初回は数分かかる）
docker compose build

# 3. PHP のライブラリを入れる
docker compose run --rm --no-deps app composer install

# 4. 起動する（初回は Node.js のライブラリの準備に1〜2分かかる）
docker compose up -d

# 5. 暗号化の鍵を作る
docker compose exec app php artisan key:generate

# 6. データベースを準備する（テーブル作成＋初期データ）
docker compose exec app php artisan migrate --seed

# 7. 管理画面のアカウントを作る（パスワードは自分で決める。8文字以上）
docker compose exec app php artisan admin:create mariko
```

| 手順 | 注意 |
|---|---|
| 4. 起動 | 初回は Node.js のライブラリの準備に1〜2分かかり、その間はページのデザインが崩れて見えます。`docker compose logs -f node` で `VITE ... ready` と出れば準備完了です（`Ctrl + C` で表示を終了）。 |
| 6. データベース | 起動直後は「Connection refused」と出ることがあります。データベースの起動待ちなので、10〜20秒おいて再実行してください。 |
| 7. 管理画面のアカウント | 手元の開発環境だけで使うアカウントです。本番の管理画面のパスワードとは別のものにしてください。 |

> 手順 1〜3・5〜7 は初回だけです。2回目からは `docker compose up -d` で起動するだけです（6章）。

---

## 5. 動いているか確認する

ブラウザで次を開きます。

| 画面 | URL | 確認すること |
|---|---|---|
| サイト | http://localhost:8000 | トップページがデザイン付きで表示される |
| 管理画面 | http://localhost:8000/admin | 4 の手順 7 で作ったアカウントでログインできる |
| Mailpit | http://localhost:8025 | お問い合わせフォームを送ると、ここにメールが届く（実際には送信されない） |
| phpMyAdmin | http://localhost:8080 | データベースの中身が見られる |

自動テストも実行してみます。

```bash
docker compose exec app php artisan test --compact
# 「Tests: 〇〇 passed」と出て、failed が無ければ OK
```

ここまでできれば、開発環境の準備は完了です。

---

## 6. 毎日の使い方

```bash
cd ~/work/mellow
docker compose up -d       # 起動（Docker Desktop を先に起動しておく）
docker compose down        # 停止（データは消えません）
```

- 起動中は、ファイルを保存するとブラウザに自動で反映されます（ビルドは不要です）。
- 文章・料金・写真などの直し方は [editing.md](editing.md) を参照してください。
- 作業を始める前に、他のメンバーの変更を取り込んでください（7章）。

---

## 7. 他のメンバーの変更を取り込む

作業を始める前に、必ず実行してください。

```bash
cd ~/work/mellow
git pull                                                     # 1. 最新の内容を取り込む
docker compose run --rm --no-deps app composer install       # 2. PHPのライブラリの追加・更新を反映
docker compose restart node                                  # 3. Node.js のライブラリの追加・更新を反映
docker compose exec app php artisan migrate                  # 4. データベースの変更を反映
```

- 変更が無い項目は、何もせずにすぐ終わります。毎回4つとも実行して構いません。
- 自分の作業を記録して送る方法は [editing.md の8章](editing.md#8-github-に送って本番に反映する) を参照してください。

**複数人で作業するときの約束**

- 作業の前に `git pull`、ひと区切りしたら記録して `git push` する（溜め込まない）。
- `git push` が「rejected」で失敗したら、`git pull` してからもう一度 `git push` する。
- 同じファイルを同時に直すと「競合（conflict）」が起きることがあります。どう直せばよいか分からない場合は、無理に進めず管理者に相談してください。
- `.env`（自分のパソコンの設定）は Git に記録されません。パスワードなどを他のファイルに書かないでください。

---

## 8. 困ったときは

| 症状 | 対処 |
|---|---|
| 「Cannot connect to the Docker daemon」 | Docker Desktop が起動していない。起動してから再実行する。 |
| 「port is already allocated」（ポートが使われている） | 他のアプリが同じ番号を使っている。下の「ポートを変える」を参照。 |
| ページのデザインが崩れる（文字だけになる） | Node.js の準備中か停止している。`docker compose ps` で `node` が動いているか確認し、`docker compose logs node` でエラーを確認する。`docker compose restart node` で再起動。 |
| 「Vite manifest not found」と表示される | Node.js（開発サーバー）が動いていない。`docker compose up -d` で起動する。 |
| 「Connection refused」「SQLSTATE」 | データベースが起動中。10〜20秒おいて再実行する。続く場合は `docker compose ps` で `db` が動いているか確認。 |
| 「No application encryption key」 | 4 の手順 5（`key:generate`）を実行していない。 |
| 「Class ... not found」 | ライブラリが古い。`docker compose run --rm --no-deps app composer install` を実行する。 |
| `git clone` で「Permission denied (publickey)」 | 2-2 の SSH 鍵が GitHub に登録されていない、またはリポジトリに招待されていない（2-1）。 |
| 管理画面のパスワードを忘れた | `docker compose exec app php artisan admin:create mariko` で再設定できる。 |
| データベースを初期状態に戻したい | `docker compose exec app php artisan migrate:fresh --seed` を実行し、4 の手順 7 でアカウントを作り直す（**手元のデータはすべて消えます**）。 |
| 動作がとても遅い（Windows） | リポジトリが `C:\` 側にある。1章の「Windows の場合」4 のとおり、Ubuntu 側の `~/work` に取り込み直す。 |

### ポートを変える

他のアプリが 8000 番などを使っている場合は、リポジトリの一番上（`~/work/mellow/.env`。`src/.env` とは別）に、使いたい番号を書きます。このファイルは Git に記録されず、自分のパソコンだけの設定になります。

```dotenv
# ~/work/mellow/.env の例（変えたいものだけ書く）
WEB_PORT=8001          # サイト（既定 8000）
PMA_PORT=8081          # phpMyAdmin（既定 8080）
DB_PORT=3307           # MySQL（既定 3306）
MAILPIT_UI_PORT=8026   # Mailpit（既定 8025）
VITE_PORT=5174         # 開発サーバー（既定 5173）
```

`WEB_PORT` を変えた場合は、`src/.env` の `APP_URL` も合わせて変えてから（例 `APP_URL=http://localhost:8001`）、`docker compose down` → `docker compose up -d` で起動し直してください。

### スマホで確認したい

- Mac と同じ Wi-Fi のスマホから: `http://パソコンのIPアドレス:8000`（Mac は `ipconfig getifaddr en0` で表示）。
- 外出先や他の人に見せる場合は ngrok などを使います（`ngrok http 8000`）。そのときは `src/.env` に `TRUSTED_PROXIES=*` を設定してください（画像などの URL が https で正しく作られるようになります）。
