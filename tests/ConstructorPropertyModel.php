<?php

namespace Units;

class ConstructorPropertyModel
{
    public function __construct(
        readonly public string $propertyOne,
        readonly public string $propertyTwo,
    ) {
    }
}
