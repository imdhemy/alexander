<?php


use Macedonia\Route\Route;

/**
 * Class RouteTests
 */
final class RouteTest extends WP_UnitTestCase
{
    /**
     * @test
     */
    function testCanAddGetEndpoint(): void
    {
        $path = "/path";
        Route::get($path, 'Example@method');
        $this->assertEquals($path, Route::endPointsArray()[0]['route']);
    }

    /**
     * @test
     */
    function testCanRegisterEndpoints(): void
    {
        Route::get('/foo', 'Example@foo');
        Route::post('/bar', 'Example@bar');
        $register = Route::register();
        $this->assertTrue($register);
    }
}