<?php

namespace Units\InterfaceModels;

class ModelConstructor
{
    public function __construct(
        readonly public ModelInterface $one,
        readonly public ModelInterface $two,
        readonly public ModelInterface $three,
    ) {
    }
}
