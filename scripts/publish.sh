#!/usr/bin/env bash
#
# 【手元のMacで実行】修正を本番公開用に GitHub へ送る。
#
#   使い方: scripts/publish.sh
#
# 1. 記録していない変更が無いか確認（あれば止まる。先に git commit する）
# 2. 自動テストを実行
# 3. React・CSS を本番用にビルドし、ビルド結果（src/public/build）を記録
# 4. GitHub（origin の main）へ送る
#
# このあとサーバーで scripts/deploy.sh を実行すると本番に反映される（docs/deploy.md）。
#
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$ROOT"

BRANCH="$(git branch --show-current)"
if [ "$BRANCH" != "main" ]; then
    echo "エラー: main ブランチで実行してください（今は $BRANCH）。" >&2
    exit 1
fi

if [ -n "$(git status --porcelain)" ]; then
    echo "エラー: 記録していない変更があります。内容を確認して記録（git commit）してから実行してください。" >&2
    git status --short >&2
    exit 1
fi

echo "▶ 自動テスト"
docker compose exec -T app php artisan test --compact

echo "▶ 本番用にビルド"
# 開発サーバー（npm run dev）が動いていても本番用に作れるよう、目印ファイルを一時的に退避する
HOT="$ROOT/src/public/hot"
[ -f "$HOT" ] && mv "$HOT" "$HOT.publishing"
trap '[ -f "$HOT.publishing" ] && mv "$HOT.publishing" "$HOT"' EXIT
docker compose run --rm --no-deps node npm run build

git add -A src/public/build
if git diff --cached --quiet; then
    echo "  ビルド結果に変更はありません"
else
    git commit -q -m "本番用ビルドを更新"
    echo "  ビルド結果を記録しました"
fi

echo "▶ GitHub へ送信"
git push origin main

echo
echo "✓ 送信しました。サーバーで次を実行すると本番に反映されます:"
echo "    bash ~/web/mellow/scripts/deploy.sh"
