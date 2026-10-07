<?php

namespace Tests\Feature;

use Tests\TestCase;

class PageTest extends TestCase
{
    public function test_all_pages_are_displayed_with_seo_tags(): void
    {
        foreach (config('pages.pages') as $name => $page) {
            $response = $this->get(route($name));

            $response->assertOk();
            $response->assertSee('<link rel="canonical" href="'.route($name).'">', false);
            $response->assertSee('<meta name="description" content="'.e($page['description']).'">', false);
            $this->assertSame(1, substr_count($response->getContent(), '<h1'), "{$name} の h1 は1つにする");
        }
    }

    public function test_page_has_breadcrumb_structured_data(): void
    {
        $this->get(route('plans.shichigosan'))
            ->assertSee('"@type":"BreadcrumbList"', false)
            ->assertSee('"name":"撮影プラン・料金"', false);
    }

    public function test_old_wordpress_urls_redirect_permanently(): void
    {
        foreach (config('pages.redirects') as $old => $name) {
            $this->get('/'.trim("bb/{$old}", '/'))->assertRedirect(route($name))->assertStatus(301);
        }

        $this->get('/bb/753plan')->assertRedirect(route('plans.shichigosan'));
        $this->get('/bb/no-such-old-page')->assertNotFound();
    }

    public function test_sitemap_lists_all_pages(): void
    {
        $response = $this->get('/sitemap.xml')->assertOk()->assertHeader('Content-Type', 'application/xml; charset=UTF-8');

        foreach (array_keys(config('pages.pages')) as $name) {
            $response->assertSee('<loc>'.route($name).'</loc>', false);
        }
    }

    public function test_robots_blocks_crawlers_outside_production(): void
    {
        $this->get('/robots.txt')->assertOk()->assertSee('Disallow: /');
    }

    public function test_unknown_page_returns_404(): void
    {
        $this->get('/no-such-page')->assertNotFound();
    }
}
