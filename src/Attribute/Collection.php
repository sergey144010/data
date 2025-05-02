<?php

namespace Sergey144010\Data\Attribute;

use Attribute;

#[Attribute]
class Collection
{
    public string $parameterClass;

    public function __construct(string $parameterClass)
    {
        $this->parameterClass = $parameterClass;
    }
}
