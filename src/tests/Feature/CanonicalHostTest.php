<?php

namespace Tests\Feature;

use Tests\TestCase;

class CanonicalHostTest extends TestCase
{
    private function production(): void
    {
        $this->app->detectEnvironment(fn () => 'production');
        config(['app.url' => 'https://ps-mellow.jp']);
    }

    public function test_other_hosts_are_redirected_to_app_url_in_production(): void
    {
        $this->production();

        $this->get('http://xxxx.lolipop.jp/plans?from=test')
            ->assertStatus(301)
            ->assertRedirect('https://ps-mellow.jp/plans?from=test');

        $this->get('https://www.ps-mellow.jp/')
            ->assertStatus(301)
            ->assertRedirect('https://ps-mellow.jp');
    }

    public function test_app_url_itself_is_not_redirected(): void
    {
        $this->production();

        $this->get('https://ps-mellow.jp/plans')->assertOk();
    }

    public function test_nothing_is_redirected_outside_production(): void
    {
        $this->get('http://xxxx.lolipop.jp/plans')->assertOk();
    }
}
