<?php

declare(strict_types = 1);

namespace Tests\Unit;

use App\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    private Router $router;

    protected function setUp(): void
    {
        parent::setUp();
        $this->router = new Router();   
    }
    public function testShouldRegisterARoute(): void
    {
        $this->router->register('get', '/ping', ['Ping', 'ping']);

        $expectedRoute = [
            'get' => [
                '/ping' => ['Ping', 'ping']
            ]
        ];
        $this->assertEquals($expectedRoute, $this->router->routes());
    }

    public function testShouldRegisterGetRoute(): void
    {
        $this->router->get('/ping', ['Ping', 'ping']);

        $expectedRoute = [
            'get' => [
                '/ping' => ['Ping', 'ping']
            ]
        ];
        $this->assertEquals($expectedRoute, $this->router->routes());
    }

    public function testShouldRegisterPostRoute(): void
    {
        $this->router->post('/user', ['User', 'store']);

        $expectedRoute = [
            'post' => [
                '/user' => ['User', 'store']
            ]
        ];
        $this->assertEquals($expectedRoute, $this->router->routes());
    }

    public function testNoRoutesRegisterAtCreation(): void
    {
        $this->assertEmpty($this->router->routes());
    }
}
