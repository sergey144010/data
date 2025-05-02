<?php

namespace Sergey144010\Data\Strategy;

class Constructor implements StrategyInterface
{
    public function handle(\ReflectionClass $reflection): array
    {
        $reflectionMethod = $reflection->getConstructor();
        if (is_null($reflectionMethod)) {
            throw new \RuntimeException('Constructor not found');
        }

        $parameters = $reflectionMethod->getParameters();
        if (empty($parameters)) {
            throw new \RuntimeException('Parameters not found');
        }

        return $parameters;
    }
}
