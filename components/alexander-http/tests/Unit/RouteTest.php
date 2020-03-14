<?php


use Alexander\Http\Route\Route;
use PHPUnit\Framework\TestCase;
use Tests\InitWordpress;

/**
 * Class RouteTests
 */
final class RouteTest extends TestCase
{
    use InitWordpress;

    /**
     * @test
     */
    function testCanAddGetEndpoint(): void {
        $path = "/path";
        Route::get($path, 'Example@method');
        $this->assertEquals($path, Route::endPointsArray()[0]['route']);
    }

    /**
     * @test
     */
    function testCanRegisterEndpoints(): void {
        $this->initWp();
        Route::get('/foo', 'Example@foo');
        Route::post('/bar', 'Example@bar');
        $register = Route::register();
        $this->assertTrue($register);
    }
}