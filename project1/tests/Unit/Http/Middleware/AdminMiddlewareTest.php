<?php

namespace Tests\Unit\Http\Middleware;

use App\Http\Middleware\AdminMiddleware;
use Tests\TestCase;

/**
 * Class AdminMiddlewareTest.
 *
 * @covers \App\Http\Middleware\AdminMiddleware
 */
final class AdminMiddlewareTest extends TestCase
{
    private AdminMiddleware $adminMiddleware;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        /** @todo Correctly instantiate tested object to use it. */
        $this->adminMiddleware = new AdminMiddleware();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->adminMiddleware);
    }

    public function testHandle(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }
}
