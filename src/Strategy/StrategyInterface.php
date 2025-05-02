<?php

namespace Sergey144010\Data\Strategy;

interface StrategyInterface
{
    public function handle(\ReflectionClass $reflection): array;
}
