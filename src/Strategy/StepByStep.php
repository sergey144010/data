<?php

namespace Sergey144010\Data\Strategy;

class StepByStep implements StrategyInterface
{
    public function handle(\ReflectionClass $reflection): array
    {
        $reflectionMethod = $reflection->getConstructor();
        if (is_null($reflectionMethod)) {
            return $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);
        }

        return $reflectionMethod->getParameters();
    }
}
