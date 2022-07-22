<?php

namespace Test\Unit;

use App\Config;
use App\Container;
use App\Exceptions\Container\NotFoundException;
use PHPUnit\Framework\TestCase;

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
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('Class NotFound not found');

        $this->container->get('NotFound');
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