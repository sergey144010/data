<?php

namespace Units\InterfaceModels\OneInterfaceTwoImplementations;

class ModelOne implements ModelInterfaceBase
{
    public function __construct(
        readonly public string $propertyOne,
    ) {
    }
}
