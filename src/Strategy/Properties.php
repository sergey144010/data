<?php

namespace Sergey144010\Data\Strategy;

class Properties implements StrategyInterface
{
    public function handle(\ReflectionClass $reflection): array
    {
        $properties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);
        if (empty($properties)) {
            throw new \RuntimeException('Properties not found');
        }

        return $properties;
    }
}
