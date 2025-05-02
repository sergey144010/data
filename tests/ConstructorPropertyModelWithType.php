<?php

namespace Units;

class ConstructorPropertyModelWithType
{
    public function __construct(
        readonly public ConstructorPropertyModel $one,
        readonly public ConstructorPropertyModel $two,
    ) {
    }
}
