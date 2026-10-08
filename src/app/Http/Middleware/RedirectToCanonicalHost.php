<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * 本番では、APP_URL と異なるURL（ロリポップの初期ドメイン経由など）でのアクセスを
 * APP_URL へ 301 転送する。同じ内容が別のURLで検索エンジンに登録されるのを防ぐ。
 */
class RedirectToCanonicalHost
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! app()->isProduction() || ! in_array($request->method(), ['GET', 'HEAD'], true)) {
            return $next($request);
        }

        $canonical = rtrim(config('app.url'), '/');

        if ($request->getSchemeAndHttpHost().$request->getBasePath() === $canonical) {
            return $next($request);
        }

        $path = ltrim($request->path(), '/');
        $query = $request->getQueryString();

        return redirect()->away($canonical.($path === '' ? '' : '/'.$path).($query ? '?'.$query : ''), 301);
    }
}
