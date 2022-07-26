<?php

namespace Test\Unit;

use App\Config;
use App\Container;
use App\Exceptions\Container\ContainerException;
use App\Exceptions\Container\NotFoundException;
use App\Services\InvoiceService;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;

class ContainerTest extends TestCase
{
    private Container $container;

    protected function setUp(): void
    {
        $this->container = new Container();
    }

    public function testShouldCreateContainer(): void
    {

        $this->assertInstanceOf(Container::class, $this->container);
    }

    public function testShouldRiseExceptionIfClassIsNotBinding(): void
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('Class Psr\Container\ContainerExceptionInterface is not instantiable.');
        
        $this->container->get(ContainerExceptionInterface::class);
    }

    public function testShouldAbleToSetAClass(): void
    {
        $this->container->set('Demo', function () {
            return true;
        });

        $this->assertEquals([
            'Demo' => function () {},
        ], $this->container->entries());
    }

    public function testShouldAbleToGetAClass(): void
    {
        $this->container->set('Demo', function (Container $c) {

            if ($c instanceof Container) {
                return true;
            }

            return true;
        });

        $this->assertTrue($this->container->get('Demo'));
    }
}