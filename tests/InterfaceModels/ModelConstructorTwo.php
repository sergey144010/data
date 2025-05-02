<?php

namespace Units\InterfaceModels;

class ModelConstructorTwo
{
    public function __construct(
        readonly public ModelInterface $one,
        readonly public ModelInterfaceTwo $two,
    ) {
    }
}
