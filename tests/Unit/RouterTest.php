<?php

declare(strict_types = 1);

namespace Tests\Unit;

use App\Router;
use PHPUnit\Framework\TestCase;
use App\Exceptions\RouteNotFoundException;

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

    /**
     * @dataProvider methodRouteActionProvider
     */
    public function testExceptionIsThrownWhenRouteIsNotFound($method, $route): void
    {
        $this->expectException(RouteNotFoundException::class);
        $this->expectExceptionMessage('404 Not Found');
        
        $users = new class{
            public function delate(): void
            {}
        };
        $this->router->get('/users', ['Exception', 'index']);
        $this->router->post('/users', [$users::class, 'store']);

        $this->router->resolve($route, $method);
    }

    public function methodRouteActionProvider(): array
    {
        return [
            ['get', '/not-found'],
            ['post', '/invoices'],
            ['post', '/users'],
        ];
    }

    public function testProperlyCallACallableRegisteredRoute(): void
    {
        $this->router->get('/users', function () {
            return true;
        });
       
        $this->assertTrue($this->router->resolve('/users', 'get'));
    }

    public function testProperlyCallAClassRegisteredRoute(): void
    {
        $users = new class() {
            public function delete(): bool
            {
                return true;
            }
        };

        $this->router->get('/users', [$users::class, 'delete']);

        $this->assertTrue($this->router->resolve('/users', 'get'));
    }
}
