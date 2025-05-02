<?php

namespace Units\InterfaceModels\OneInterfaceTwoImplementations;

class EntryModelTwo
{
    public function __construct(
        readonly public ModelInterfaceBase $one,
        readonly public ModelInterfaceBase $two,
    ) {
    }
}
