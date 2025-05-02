<?php

namespace Units;

class ConstructorPropertyDefaultNullModel
{
    public readonly ?string $propertyTwo;

    public function __construct(
        public readonly ?string $propertyOne = null,
        ?string $propertyTwo = null,
    ) {
    }
}
