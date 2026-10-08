#!/usr/bin/env bash
#
# 【サーバー上で実行】GitHub から最新の内容を取り込み（git pull）、本番に反映する。
#
#   初回:   bash ~/mellow/scripts/deploy.sh --first
#   更新時: bash ~/mellow/scripts/deploy.sh
#
# 行うこと:
#   1. メンテナンス表示（2回目以降）
#   2. git pull で最新の内容を取り込む
#   3. 本番用ライブラリ（Composer）をインストール
#   4. 公開フォルダ（~/web/mellow）を src/public へのシンボリックリンクにする（初回のみ）
#   5. データベースを更新（--first のときは初期データも投入）
#   6. キャッシュを作り直し、メンテナンス表示を解除
#
# 場所を変える場合は環境変数で指定: PUBLIC_DIR（公開フォルダ）・PHP（PHPコマンド）・COMPOSER_PHAR
#
set -euo pipefail

# git pull でこのファイル自体が書き換わっても実行が乱れないよう、一時コピーから実行し直す
if [ -z "${MELLOW_DEPLOY_COPY:-}" ]; then
    copy="$(mktemp)"
    cp "$0" "$copy"
    MELLOW_DEPLOY_COPY="$copy" MELLOW_REPO="$(cd "$(dirname "$0")/.." && pwd)" exec bash "$copy" "$@"
fi
trap 'rm -f "$MELLOW_DEPLOY_COPY"' EXIT

REPO="$MELLOW_REPO"
APP="$REPO/src"
PUBLIC_DIR="${PUBLIC_DIR:-$HOME/web/mellow}"
COMPOSER_PHAR="${COMPOSER_PHAR:-$HOME/bin/composer.phar}"
FIRST=false
[ "${1:-}" = "--first" ] && FIRST=true

# PHP 8.4 以上のコマンドを探す（ロリポップはサーバーの世代でパスが異なる）
find_php() {
    local candidates=(
        "${PHP:-}"
        /usr/local/bin/php8.5 /usr/local/php/8.5/bin/php
        /usr/local/bin/php8.4 /usr/local/php/8.4/bin/php
        php
    )
    for c in "${candidates[@]}"; do
        [ -n "$c" ] && command -v "$c" >/dev/null 2>&1 || continue
        if "$c" -r 'exit(version_compare(PHP_VERSION, "8.4.1", ">=") ? 0 : 1);'; then
            echo "$c"
            return
        fi
    done
    echo "エラー: PHP 8.4.1 以上が見つかりません。環境変数 PHP=/path/to/php で指定してください。" >&2
    exit 1
}
PHP_BIN="$(find_php)"
echo "▶ PHP: $PHP_BIN ($("$PHP_BIN" -r 'echo PHP_VERSION;'))"

artisan() { (cd "$APP" && "$PHP_BIN" artisan "$@"); }
installed() { [ -f "$APP/.env" ] && [ -f "$APP/vendor/autoload.php" ]; }

if installed && ! $FIRST; then
    artisan down --retry=30 || true
    # 途中で失敗しても、メンテナンス表示のままにならないようにする
    trap 'artisan up >/dev/null 2>&1 || true; rm -f "$MELLOW_DEPLOY_COPY"' EXIT
fi

echo "▶ GitHub から取り込み（git pull）"
git -C "$REPO" pull --ff-only

echo "▶ 本番用ライブラリをインストール（Composer）"
if [ ! -f "$COMPOSER_PHAR" ]; then
    echo "  Composer を $COMPOSER_PHAR にダウンロードします"
    mkdir -p "$(dirname "$COMPOSER_PHAR")"
    curl -fsSL https://getcomposer.org/download/latest-stable/composer.phar -o "$COMPOSER_PHAR"
fi
# レンタルサーバーのメモリ制限で失敗しないよう、上限を外して実行する
"$PHP_BIN" -d memory_limit=-1 "$COMPOSER_PHAR" install \
    --working-dir="$APP" --no-dev --optimize-autoloader --no-interaction --no-progress

echo "▶ 公開フォルダ: $PUBLIC_DIR → $APP/public"
if [ -L "$PUBLIC_DIR" ]; then
    echo "  設定済み"
elif [ -e "$PUBLIC_DIR" ]; then
    echo "エラー: $PUBLIC_DIR が既にあります（シンボリックリンクではない）。中身を確認し、退避してから再実行してください。" >&2
    exit 1
else
    mkdir -p "$(dirname "$PUBLIC_DIR")"
    ln -s "$APP/public" "$PUBLIC_DIR"
    echo "  シンボリックリンクを作成しました"
fi

chmod -R u+rwX,go+rX "$APP/storage" "$APP/bootstrap/cache"

if [ ! -f "$APP/.env" ]; then
    echo
    echo "※ $APP/.env がありません。docs/deploy.md の「.env を作る」に従って作成し、もう一度実行してください。"
    exit 1
fi

echo "▶ データベースを更新"
if $FIRST; then
    artisan migrate --seed --force
else
    artisan migrate --force
fi

echo "▶ キャッシュを作り直す"
artisan optimize:clear >/dev/null
artisan optimize

artisan up >/dev/null 2>&1 || true

echo
echo "✓ 本番に反映しました（$(git -C "$REPO" log -1 --format='%h %s')）"
$FIRST && echo "  管理者アカウントを作成してください: cd $APP && $PHP_BIN artisan admin:create mariko"
exit 0
