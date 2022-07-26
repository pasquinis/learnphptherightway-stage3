<?php

declare(strict_types=1);

namespace App;

use App\Exceptions\Container\NotFoundException;
use App\Exceptions\Container\ContainerException;
use Exception;
use Psr\Container\ContainerInterface;
use ReflectionClass;

class Container implements ContainerInterface
{
    private array $entries;

    public function __construct()
    {
        $this->entries = [];
    }
    public function get(string $id)
    {
        if ($this->has($id)) {
            $entry = $this->entries[$id];

            return call_user_func($entry, $this);
        }

        $this->register($id);

        throw new NotFoundException('Class ' . $id . ' not binding');
    }

    public function register(string $id)
    {
        $reflactionClass = new ReflectionClass($id);

        if (! $reflactionClass->isInstantiable()) {
            throw new ContainerException('Class ' . $id . ' is not instantiable.');
        }
    }
    
    public function has(string $id): bool
    {
        return isset($this->entries[$id]);
    }

    public function set(string $id, callable $concrete): void
    {
        $this->entries[$id] = $concrete;
    }

    public function entries(): array
    {
        return $this->entries;
    }
}