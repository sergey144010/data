<?php

namespace Sergey144010\Data\Attribute;

use Attribute;

#[Attribute]
class CollectionRoot
{
    public string $parameterClass;

    public function __construct(string $parameterClass)
    {
        $this->parameterClass = $parameterClass;
    }
}
