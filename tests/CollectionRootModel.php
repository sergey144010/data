<?php

namespace Units;

use Sergey144010\Data\Attribute\CollectionRoot;

class CollectionRootModel
{
    public function __construct(
        #[CollectionRoot(ConstructorPropertyModel::class)] readonly public array $collection,
    ) {
    }
}
