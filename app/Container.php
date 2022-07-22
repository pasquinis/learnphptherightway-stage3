<?php

declare(strict_types=1);

namespace App;

use App\Exceptions\Container\NotFoundException;
use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    private array $entries;

    public function __construct()
    {
        $this->entries = [];
    }
    public function get(string $id)
    {
        if (! $this->has($id)) {
           throw new NotFoundException("Class " . $id . " not found");
        }

        $entry = $this->entries[$id];

        return call_user_func($entry, $this);
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