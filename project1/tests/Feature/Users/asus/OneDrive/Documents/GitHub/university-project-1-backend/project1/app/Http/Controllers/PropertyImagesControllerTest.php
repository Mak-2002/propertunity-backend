<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\PropertyImagesController;
use Tests\TestCase;

/**
 * Class PropertyImagesControllerTest.
 *
 * @covers \App\Http\Controllers\PropertyImagesController
 */
final class PropertyImagesControllerTest extends TestCase
{
    private PropertyImagesController $propertyImagesController;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        /** @todo Correctly instantiate tested object to use it. */
        $this->propertyImagesController = new PropertyImagesController();
        $this->app->instance(PropertyImagesController::class, $this->propertyImagesController);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->propertyImagesController);
    }

    public function testIndex(): void
    {
        /** @todo This test is incomplete. */
        $this->get('/path')
            ->assertStatus(200);
    }

    public function testStore(): void
    {
        /** @todo This test is incomplete. */
        $this->post('/path', [ /* data */ ])
            ->assertStatus(200);
    }

    public function testShow(): void
    {
        /** @todo This test is incomplete. */
        $this->get('/path')
            ->assertStatus(200);
    }

    public function testUpdate(): void
    {
        /** @todo This test is incomplete. */
        $this->put('/path', [ /* data */ ])
            ->assertStatus(200);
    }

    public function testDestroy(): void
    {
        /** @todo This test is incomplete. */
        $this->delete('/path')
            ->assertStatus(200);
    }
}
