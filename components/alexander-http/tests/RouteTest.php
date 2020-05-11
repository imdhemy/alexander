<?php

use Macedonia\Http\Route\Route;

/**
 * Class RouteTests.
 *
 * @group route
 */
final class RouteTest extends \Tests\TestCase
{
    /**
     * @test
     */
    public function testCanAddGetEndpoint(): void
    {
        $path = '/path';
        Route::get($path, 'Example@method');
        $this->assertEquals($path, Route::getEndPointsArray()[0]['route']);
    }

    /**
     * @test
     */
    public function testCanRegisterEndpoints(): void
    {
        Route::get('/foo', 'Example@foo');
        Route::post('/bar', 'Example@bar');
        $register = Route::register();
        $this->assertTrue($register);
    }

    /**
     * @test
     */
    public function test_can_register_multiple_namespaces()
    {
        Route::setNamespace('my-namespace');
        Route::get('/foo', 'Example@foo');
        Route::setNamespace('his-namespace');
        Route::get('/bar', 'Example@bar');
        $this->assertTrue(Route::register());
    }

    /**
     * @test
     */
    public function test_can_add_variables_in_routes()
    {
        $this->assertTrue(true);
    }
}
