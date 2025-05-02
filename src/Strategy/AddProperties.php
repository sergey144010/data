<?php

namespace Sergey144010\Data\Strategy;

class AddProperties implements StrategyInterface
{
    public function handle(\ReflectionClass $reflection): array
    {
        return [];
    }
}
