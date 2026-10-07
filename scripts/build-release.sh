#!/usr/bin/env bash
#
# レンタルサーバー（ロリポップ）へアップロードする公開用ファイル一式を作る。
#
#   使い方: scripts/build-release.sh
#
# 出力（release/ 以下。毎回作り直す）:
#   release/mellow-release.tar.gz … これをサーバーへアップロードする
#     mellow-release/mellow-app/  … アプリ本体（公開されない ~/mellow-app へ）
#     mellow-release/public/      … 公開フォルダ（~/web/mellow へ）
#     mellow-release/deploy.sh    … サーバー上で実行する配置スクリプト
#
# Node.js・Composer はサーバーに不要。フロントのビルドと本番用の vendor をここで作る。
#
set -euo pipefail

# 公開フォルダ（~/web/mellow）から見たアプリ本体（~/mellow-app）の場所
APP_FROM_PUBLIC="../../mellow-app"

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
SRC="$ROOT/src"
OUT="$ROOT/release/mellow-release"

cd "$ROOT"

echo "▶ フロントエンドをビルド"
docker compose run --rm --no-deps node npm run build

echo "▶ アプリ本体をコピー（開発用のファイルは除く）"
rm -rf "$ROOT/release"
mkdir -p "$OUT/mellow-app" "$OUT/public"
rsync -a "$SRC/" "$OUT/mellow-app/" \
    --exclude='/public' \
    --exclude='/node_modules' \
    --exclude='/vendor' \
    --exclude='/tests' \
    --exclude='/.env' \
    --exclude='/.env.*' \
    --exclude='/.ai' --exclude='/.agents' --exclude='/.claude' --exclude='/.mcp.json' \
    --exclude='/AGENTS.md' --exclude='/CLAUDE.md' --exclude='/boost.json' \
    --exclude='/phpunit.xml' --exclude='/.phpunit.result.cache' \
    --exclude='/package.json' --exclude='/package-lock.json' --exclude='/vite.config.js' \
    --exclude='/resources/css' --exclude='/resources/js' --exclude='/resources/images' --exclude='/scripts' \
    --exclude='/database/database.sqlite' \
    --exclude='/storage/logs/*.log' \
    --exclude='/storage/framework/cache/data/*' \
    --exclude='/storage/framework/sessions/*' \
    --exclude='/storage/framework/views/*' \
    --exclude='/bootstrap/cache/*.php' \
    --exclude='.DS_Store'
cp "$SRC/.env.example" "$OUT/mellow-app/.env.example"

echo "▶ 公開フォルダの中身をコピー"
rsync -a "$SRC/public/" "$OUT/public/" --exclude='/hot' --exclude='.DS_Store'

# 本番では http でのアクセスを https へ転送する（開発環境の .htaccess には入れない）
python3 - "$OUT/public/.htaccess" <<'PY'
import sys
path = sys.argv[1]
s = open(path, encoding='utf-8').read()
rule = """    RewriteEngine On

    # HTTPS へ統一する（本番用。build-release.sh が追加）
    RewriteCond %{HTTPS} !=on
    RewriteCond %{HTTP:X-Forwarded-Proto} !=https
    RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
"""
assert '    RewriteEngine On\n' in s
open(path, 'w', encoding='utf-8').write(s.replace('    RewriteEngine On\n', rule, 1))
PY

# 公開フォルダとアプリ本体を別の場所に置くため、index.php の参照先を書き換える
cat > "$OUT/public/index.php" <<PHP
<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// アプリ本体の場所（この index.php から見た相対パス）
\$appPath = __DIR__.'/${APP_FROM_PUBLIC}';

// Determine if the application is in maintenance mode...
if (file_exists(\$maintenance = \$appPath.'/storage/framework/maintenance.php')) {
    require \$maintenance;
}

// Register the Composer autoloader...
require \$appPath.'/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application \$app */
\$app = require_once \$appPath.'/bootstrap/app.php';

// 画像やビルド済みファイル（build/manifest.json）はこの公開フォルダにある
\$app->usePublicPath(__DIR__);

\$app->handleRequest(Request::capture());
PHP

echo "▶ 本番用のライブラリ（vendor）をインストール"
docker compose run --rm --no-deps -v "$OUT/mellow-app:/release" -w /release app \
    composer install --no-dev --optimize-autoloader --no-interaction --no-progress --quiet

cp "$ROOT/scripts/server-deploy.sh" "$OUT/deploy.sh"

echo "▶ 圧縮ファイルを作成"
COPYFILE_DISABLE=1 tar -czf "$ROOT/release/mellow-release.tar.gz" -C "$ROOT/release" mellow-release

echo
echo "✓ 完了: release/mellow-release.tar.gz ($(du -h "$ROOT/release/mellow-release.tar.gz" | cut -f1))"
echo "  アップロードと配置の手順は docs/deploy.md を参照してください。"
