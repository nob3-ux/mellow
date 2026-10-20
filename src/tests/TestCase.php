<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * テストでは CSS・JS（Vite）を読み込まない。
     * ビルド前（public/build が無い）や開発サーバーを起動していない状態でもテストできるようにする。
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }
}
