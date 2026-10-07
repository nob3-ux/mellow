<?php

namespace App\Http\Controllers;

use App\Support\Seo;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PageController extends Controller
{
    /**
     * config/pages.php に定義したページを表示する。
     */
    public function show(Request $request): View
    {
        $name = $request->route()->getName();
        $page = config('pages.pages')[$name];

        return view("pages.{$page['view']}", [
            'seo' => Seo::forPage($name),
        ]);
    }

    public function sitemap(): Response
    {
        $pages = collect(config('pages.pages'))
            ->map(fn (array $page, string $name) => [
                'url' => route($name),
                'priority' => $page['priority'] ?? '0.6',
            ])
            ->values();

        return response()
            ->view('sitemap', ['pages' => $pages])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function robots(): Response
    {
        $lines = app()->isProduction()
            ? ['User-agent: *', 'Allow: /', 'Disallow: /admin', '', 'Sitemap: '.route('sitemap')]
            : ['User-agent: *', 'Disallow: /'];

        return response(implode("\n", $lines)."\n")->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
