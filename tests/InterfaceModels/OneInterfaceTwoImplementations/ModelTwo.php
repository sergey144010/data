<?php

namespace Units\InterfaceModels\OneInterfaceTwoImplementations;

class ModelTwo implements ModelInterfaceBase
{
    public function __construct(
        readonly public string $propertyTwo,
    ) {
    }
}
