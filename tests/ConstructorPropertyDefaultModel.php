<?php

namespace Units;

class ConstructorPropertyDefaultModel
{
    public function __construct(
        readonly public string $propertyOne = '111',
        readonly public string $propertyTwo = '222',
    ) {
    }
}
