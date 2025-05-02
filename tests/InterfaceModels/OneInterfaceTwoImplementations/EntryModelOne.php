<?php

namespace Units\InterfaceModels\OneInterfaceTwoImplementations;

class EntryModelOne
{
    public function __construct(
        readonly public ModelInterfaceBase $one,
    ) {
    }
}
