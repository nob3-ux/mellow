#!/usr/bin/env bash
#
# 【サーバー上で実行】公開用ファイル一式を所定の場所へ配置し、データベース・キャッシュを更新する。
# build-release.sh が作る mellow-release.tar.gz に deploy.sh として同梱される。
#
#   初回:   bash ~/mellow-release/deploy.sh --first
#   更新時: bash ~/mellow-release/deploy.sh
#
# 配置先（ロリポップの例。環境変数で変更可）
#   APP_DIR=~/mellow-app   … アプリ本体（公開されない場所）
#   PUBLIC_DIR=~/web/mellow … 公開フォルダ（独自ドメインの公開フォルダに指定する）
#
set -euo pipefail

RELEASE="$(cd "$(dirname "$0")" && pwd)"
APP_DIR="${APP_DIR:-$HOME/mellow-app}"
PUBLIC_DIR="${PUBLIC_DIR:-$HOME/web/mellow}"
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

artisan() { (cd "$APP_DIR" && "$PHP_BIN" artisan "$@"); }

# 2回目以降はメンテナンス表示にしてから入れ替える
if [ -f "$APP_DIR/artisan" ] && [ -f "$APP_DIR/.env" ] && ! $FIRST; then
    artisan down --retry=30 || true
fi

echo "▶ アプリ本体を配置: $APP_DIR"
mkdir -p "$APP_DIR"
# .env と storage（ログ・セッション等）はサーバーのものを残し、それ以外は入れ替える
for item in app bootstrap config database resources routes vendor artisan composer.json composer.lock .env.example; do
    rm -rf "${APP_DIR:?}/$item"
    cp -R "$RELEASE/mellow-app/$item" "$APP_DIR/$item"
done
# storage はフォルダ構成だけ用意する（既存のログ・セッション等には触れない）
(cd "$RELEASE/mellow-app" && find storage -type d) | while read -r dir; do mkdir -p "$APP_DIR/$dir"; done
chmod -R u+rwX,go+rX "$APP_DIR/storage" "$APP_DIR/bootstrap/cache"

echo "▶ 公開フォルダを配置: $PUBLIC_DIR"
mkdir -p "$PUBLIC_DIR"
rm -rf "${PUBLIC_DIR:?}/build"
cp -R "$RELEASE/public/." "$PUBLIC_DIR/"

if [ ! -f "$APP_DIR/.env" ]; then
    echo
    echo "※ $APP_DIR/.env がありません。手順書の「.env を作る」に従って作成し、もう一度実行してください。"
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

artisan up || true

echo
echo "✓ 配置が完了しました"
$FIRST && echo "  管理者アカウントを作成してください: cd $APP_DIR && $PHP_BIN artisan admin:create メールアドレス"
exit 0
