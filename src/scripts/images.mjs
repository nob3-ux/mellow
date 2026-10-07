/*
 * 写真をサイト用の WebP（幅 640 / 1280 / 1920px）に変換し、resources/data/images.json を更新する。
 *
 *   元の写真: resources/images/<フォルダ>/<名前>.(jpg|jpeg|png|webp)
 *   出力:     public/images/<フォルダ>/<名前>-<幅>.webp
 *   画像キー: <フォルダ>/<名前>   … Blade で <x-picture src="<フォルダ>/<名前>"> と書く
 *
 *   使い方:   docker compose run --rm --no-deps node npm run images
 *             （変換済みで元の写真が更新されていないものは飛ばす。--force ですべて変換し直す）
 *
 * 既存の画像キーと同じ名前で置くと、その写真を差し替えられる。
 */
import { existsSync, mkdirSync, readdirSync, readFileSync, rmSync, statSync, writeFileSync } from 'node:fs';
import { dirname, extname, join, relative } from 'node:path';
import sharp from 'sharp';

const SOURCE_DIR = 'resources/images';
const OUTPUT_DIR = 'public/images';
const MANIFEST = 'resources/data/images.json';
const WIDTHS = [640, 1280, 1920];
const EXTENSIONS = ['.jpg', '.jpeg', '.png', '.webp'];
const force = process.argv.includes('--force');

function sourceFiles(dir) {
    if (!existsSync(dir)) return [];
    return readdirSync(dir, { withFileTypes: true }).flatMap((entry) => {
        const path = join(dir, entry.name);
        if (entry.isDirectory()) return sourceFiles(path);
        return EXTENSIONS.includes(extname(entry.name).toLowerCase()) ? [path] : [];
    });
}

const manifest = JSON.parse(readFileSync(MANIFEST, 'utf8'));
const files = sourceFiles(SOURCE_DIR);
let converted = 0;

for (const file of files) {
    const key = relative(SOURCE_DIR, file).slice(0, -extname(file).length).split('\\').join('/');

    if (!/^[a-z0-9][a-z0-9_-]*(\/[a-z0-9][a-z0-9_-]*)*$/.test(key)) {
        console.error(`✗ ${file}: フォルダ名・ファイル名は半角英小文字・数字・-・_ にしてください`);
        process.exitCode = 1;
        continue;
    }

    const largest = join(OUTPUT_DIR, `${key}-${manifest[key]?.widths?.at(-1)}.webp`);
    if (!force && manifest[key] && existsSync(largest) && statSync(largest).mtimeMs >= statSync(file).mtimeMs) {
        continue;
    }

    // 回転情報（スマホ写真の縦横）を反映してから縮小する
    const image = sharp(file).rotate();
    const { width, height } = await image.metadata().then((m) => (m.orientation >= 5 ? { width: m.height, height: m.width } : m));
    const widths = [...new Set([...WIDTHS.filter((w) => w < width), Math.min(width, WIDTHS.at(-1))])].sort((a, b) => a - b);

    // 差し替えの場合、古いサイズのファイルを消してから書き出す
    for (const w of manifest[key]?.widths ?? []) {
        rmSync(join(OUTPUT_DIR, `${key}-${w}.webp`), { force: true });
    }
    mkdirSync(dirname(join(OUTPUT_DIR, key)), { recursive: true });
    for (const w of widths) {
        await sharp(file).rotate().resize({ width: w }).webp({ quality: 80 }).toFile(join(OUTPUT_DIR, `${key}-${w}.webp`));
    }

    const top = widths.at(-1);
    manifest[key] = { width: top, height: Math.round((height * top) / width), widths };
    console.log(`✓ ${key}（${widths.join(' / ')}px）`);
    converted++;
}

const sorted = Object.fromEntries(Object.entries(manifest).sort(([a], [b]) => a.localeCompare(b)));
writeFileSync(MANIFEST, JSON.stringify(sorted, null, 1) + '\n');
console.log(converted ? `${converted}枚を変換しました。` : '変換が必要な写真はありませんでした。');
