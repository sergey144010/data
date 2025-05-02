<?php

namespace Units;

use Sergey144010\Data\Attribute\Collection;

class ConstructorParameterAttributeModel
{
    public function __construct(
        readonly public ConstructorPropertyModel $one,
        #[Collection(ConstructorPropertyModel::class)] readonly public array $collection,
        readonly public string $two,
    ) {
    }
}
