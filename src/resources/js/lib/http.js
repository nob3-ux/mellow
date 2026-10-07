/**
 * Laravel へのJSONリクエスト（CSRFトークン付き）。失敗時は例外を投げる。
 */
export async function requestJson(url, { method = 'GET', body } = {}) {
    const response = await fetch(url, {
        method,
        headers: {
            Accept: 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: body === undefined ? undefined : JSON.stringify(body),
    });

    if (response.status === 401 || response.status === 419) {
        throw new Error('ログインの有効期限が切れました。ページを再読み込みして、もう一度ログインしてください。');
    }
    if (!response.ok) {
        throw new Error(`保存できませんでした（エラー ${response.status}）。時間をおいてもう一度お試しください。`);
    }

    return response.json();
}
