<?php

namespace Units;

class BasicPropertyWithNullModel
{
    public function __construct(
        readonly public string $propertyOne,
        readonly public string|null $propertyTwo,
    ) {
    }
}
