<?php

namespace Units;

use PHPUnit\Framework\TestCase;
use Sergey144010\Data\Data;
use Sergey144010\Data\CamelCustom;
use Sergey144010\Data\Strategy\AddProperties;
use Units\InterfaceModels\ModelClass;
use Units\InterfaceModels\ModelClassTwo;
use Units\InterfaceModels\ModelConstructor;
use Units\InterfaceModels\ModelConstructorTwo;
use Units\InterfaceModels\ModelInterface;
use Units\InterfaceModels\ModelInterfaceTwo;
use Units\InterfaceModels\ModelPropertyOne;
use Units\InterfaceModels\ModelPropertyTwo;
use Units\InterfaceModels\OneInterfaceTwoImplementations\EntryModelTwo;
use Units\InterfaceModels\OneInterfaceTwoImplementations\ModelInterfaceBase;
use Units\InterfaceModels\OneInterfaceTwoImplementations\ModelOne;
use Units\InterfaceModels\OneInterfaceTwoImplementations\ModelTwo;

class DataTest extends TestCase
{
    private readonly Data $data;

    public function setUp(): void
    {
        parent::setUp();
        $this->data = new Data(new CamelCustom());
    }

    public function testEmptyModel(): void
    {
        $data = [
            'property-one' => 'one',
            'property_two' => 'two',
        ];

        $model = $this->data->toObject($data, EmptyModel::class);

        self::assertEquals('one', $model->propertyOne);
        self::assertEquals('two', $model->propertyTwo);
    }

    public function testBasicProperty(): void
    {
        $data = [
            'property-one' => 'one',
            'property_two' => 'two',
        ];

        $model = $this->data->toObject($data, BasicPropertyModel::class);

        self::assertEquals('one', $model->propertyOne);
        self::assertEquals('two', $model->propertyTwo);
    }

    public function testConstructorProperty(): void
    {
        $data = [
            'property-one' => 'one',
            'property_two' => 'two',
            'property three' => 'three',
        ];

        $model = $this->data->toObject($data, ConstructorPropertyModel::class);

        self::assertEquals('one', $model->propertyOne);
        self::assertEquals('two', $model->propertyTwo);
        self::assertObjectNotHasProperty('propertyThree', $model);
    }

    public function testDataConstructorPropertyBasic(): void
    {
        $data = [
            'propertyOne' => 'one',
            'propertyTwo' => 'two',
        ];

        $model = $this->data->toObject($data, ConstructorPropertyModel::class);

        self::assertEquals('one', $model->propertyOne);
        self::assertEquals('two', $model->propertyTwo);
    }

    public function testDataConstructorPropertyWithType(): void
    {
        $data = [
            'one' => [
                'propertyOne' => 'one1',
                'propertyTwo' => 'two1',
            ],
            'two' => [
                'propertyOne' => 'one2',
                'propertyTwo' => 'two2',
            ],
        ];

        $model = $this->data->toObject($data, ConstructorPropertyModelWithType::class);

        self::assertIsObject($model->one);
        self::assertInstanceOf(ConstructorPropertyModel::class, $model->one);
        self::assertEquals('one1', $model->one->propertyOne);
        self::assertEquals('two1', $model->one->propertyTwo);

        self::assertIsObject($model->two);
        self::assertInstanceOf(ConstructorPropertyModel::class, $model->two);
        self::assertEquals('one2', $model->two->propertyOne);
        self::assertEquals('two2', $model->two->propertyTwo);
    }
    public function testDataConstructorPropertyWithTypeCollection(): void
    {
        $data = [
            'one' => [
                'propertyOne' => 'one',
                'propertyTwo' => 'two',
            ],
            'collection' => [
                'c1' => [
                    'propertyOne' => 'one1',
                    'propertyTwo' => 'two1',
                ],
                'c2' => [
                    'propertyOne' => 'one2',
                    'propertyTwo' => 'two2',
                ],
            ],
            'two' => '123',
        ];

        $model = $this->data->toObject($data, ConstructorParameterAttributeModel::class);

        self::assertIsObject($model->one);
        self::assertInstanceOf(ConstructorPropertyModel::class, $model->one);
        self::assertEquals('one', $model->one->propertyOne);
        self::assertEquals('two', $model->one->propertyTwo);

        self::assertIsArray($model->collection);
        self::assertCount(2, $model->collection);

        self::assertInstanceOf(ConstructorPropertyModel::class, $model->collection[0]);
        self::assertEquals('one1', $model->collection[0]->propertyOne);
        self::assertEquals('two1', $model->collection[0]->propertyTwo);

        self::assertInstanceOf(ConstructorPropertyModel::class, $model->collection[1]);
        self::assertEquals('one2', $model->collection[1]->propertyOne);
        self::assertEquals('two2', $model->collection[1]->propertyTwo);

        self::assertEquals('123', $model->two);
    }

    public function testOnNull(): void
    {
        $data = [
            'Property One' => 'string123',
            'Property_Two' => null,
        ];

        $model = $this->data->toObject($data, BasicPropertyWithNullModel::class);
        self::assertEquals('string123', $model->propertyOne);
        self::assertEquals(null, $model->propertyTwo);
    }

    public function testTwoNull(): void
    {
        $data = [
            'Property One' => 'string123',
        ];

        $model = $this->data->toObject($data, BasicPropertyWithNullModel::class);
        self::assertEquals('string123', $model->propertyOne);
        self::assertEquals(null, $model->propertyTwo);
    }

    public function testOneNothingFromResponseException(): void
    {
        $this->expectException(\Throwable::class);

        $data = [
            'Property_Two' => 'asd',
        ];

        $this->data->toObject($data, BasicPropertyWithNullModel::class);
    }

    public function testBasicPropertyByUniversal(): void
    {
        $data = [
            'property-one' => 'one',
            'property_two' => 'two',
        ];

        $model = $this->data->toObject($data, BasicPropertyModel::class);

        self::assertEquals('one', $model->propertyOne);
        self::assertEquals('two', $model->propertyTwo);
    }

    public function testMixedBasicPropertyByUniversal(): void
    {
        #$this->expectException(\ErrorException::class);
        #self::assertEquals('three', $model->propertyThree);

        $data = [
            'property-one' => 'one',
            'property_two' => 'two',
            'property three' => 'three',
        ];

        $model = $this->data->toObject($data, BasicPropertyModel::class);

        self::assertEquals('one', $model->propertyOne);
        self::assertEquals('two', $model->propertyTwo);
        self::assertObjectNotHasProperty('propertyThree', $model);
    }

    public function testEmptyModelByUniversal(): void
    {
        $data = [
            'property-one' => 'one',
            'property_two' => 'two',
        ];

        $model = $this->data->toObject($data, EmptyModel::class);

        self::assertEquals('one', $model->propertyOne);
        self::assertEquals('two', $model->propertyTwo);
    }

    public function testConstructorDefaultValue(): void
    {
        $data = [];

        $model = $this->data->toObject($data, ConstructorPropertyDefaultModel::class);

        self::assertEquals('111', $model->propertyOne);
        self::assertEquals('222', $model->propertyTwo);
    }

    public function testPropertyDefaultValue(): void
    {
        $data = [];

        $model = $this->data->toObject($data, PropertyDefaultModel::class);

        self::assertEquals('one123', $model->propertyOne);
        self::assertEquals('two321', $model->propertyTwo);
    }

    public function testPropertyDefaultNull()
    {
        $data = [];

        $model = $this->data->toObject($data, ConstructorPropertyDefaultNullModel::class);

        self::assertEquals(null, $model->propertyOne);
        self::assertEquals(null, $model->propertyTwo);
    }

    public function testInterfaceOne()
    {
        $data = [
            'one' => [
                'property-one' => 'one',
            ],
            'two' => [
                'property-one' => 'one',
            ],
            'three' => [
                'property-one' => 'one',
            ],
        ];

        $model = $this->data->toObject(
            data: $data,
            class: ModelConstructor::class,
            injectionMap: [ModelInterface::class => ModelClass::class]
        );

        self::assertEquals('one', $model->one->propertyOne);
        self::assertEquals('one', $model->two->propertyOne);
        self::assertEquals('one', $model->three->propertyOne);
    }

    public function testInterfaceTwo()
    {
        $data = [
            'one' => [
                'property-one' => 'one',
            ],
            'two' => [
                'property-two' => 'two',
            ],
        ];

        $model = $this->data->toObject(
            data: $data,
            class: ModelConstructorTwo::class,
            injectionMap: [
                ModelInterface::class => ModelClass::class,
                ModelInterfaceTwo::class => ModelClassTwo::class,
            ]
        );

        self::assertEquals('one', $model->one->propertyOne);
        self::assertEquals('two', $model->two->propertyTwo);
    }

    public function testInterfacePropertyOne()
    {
        $data = [
            'one' => [
                'property-one' => 'one',
            ],
            'two' => [
                'property-one' => 'one',
            ],
            'three' => [
                'property-one' => 'one',
            ],
        ];

        $model = $this->data->toObject(
            data: $data,
            class: ModelPropertyOne::class,
            injectionMap: [ModelInterface::class => ModelClass::class]
        );

        self::assertEquals('one', $model->one->propertyOne);
        self::assertEquals('one', $model->two->propertyOne);
        self::assertEquals('one', $model->three->propertyOne);
    }

    public function testInterfacePropertyTwo()
    {
        $data = [
            'one' => [
                'property-one' => 'one',
            ],
            'two' => [
                'property-two' => 'two',
            ],
        ];

        $model = $this->data->toObject(
            data: $data,
            class: ModelPropertyTwo::class,
            injectionMap: [
                ModelInterface::class => ModelClass::class,
                ModelInterfaceTwo::class => ModelClassTwo::class,
            ]
        );

        self::assertEquals('one', $model->one->propertyOne);
        self::assertEquals('two', $model->two->propertyTwo);
    }

    public function testInterfaceTwoOther()
    {
        $data = [
            'one' => [
                'property-one' => 'one',
            ],
            'two' => [
                'property-two' => 'two',
            ],
        ];

        $model = $this->data->toObject(
            data: $data,
            class: EntryModelTwo::class,
            injectionMap: [
                EntryModelTwo::class => [
                    'one' => [
                        ModelInterfaceBase::class => ModelOne::class,
                    ],
                    'two' => [
                        ModelInterfaceBase::class => ModelTwo::class,
                    ],
                ]
            ]
        );

        self::assertEquals('one', $model->one->propertyOne);
        self::assertEquals('two', $model->two->propertyTwo);
    }

    public function testInterfaceTwoOtherString()
    {
        $data = [
            'one' => [
                'property-one' => 'one',
            ],
            'two' => [
                'property-two' => 'two',
            ],
        ];

        $model = $this->data->toObject(
            data: $data,
            class: EntryModelTwo::class,
            injectionMap: [
                EntryModelTwo::class => [
                    'one' => ModelOne::class,
                    'two' => ModelTwo::class,
                ]
            ]
        );

        self::assertEquals('one', $model->one->propertyOne);
        self::assertEquals('two', $model->two->propertyTwo);
    }

    /*
     * JsonWrapperTest
     *
    public function testInterfaceCallableOne()
    {
        $data = [
            'one' => [
                'property-one' => 'one',
            ],
        ];

        $model = $this->data->toObject(
            data: $data,
            class: EntryModelOne::class,
            injectionMap: function (array $request) {
                if (isset($request['one']['property-one'])) {
                    return [ModelInterfaceBase::class => ModelOne::class];
                }
                if (isset($request['one']['property-two'])) {
                    return [ModelInterfaceBase::class => ModelTwo::class];
                }

                return [];
            }
        );

        self::assertEquals('one', $model->one->propertyOne);
    }

    public function testInterfaceCallableTwo()
    {
        $data = [
            'one' => [
                'property-two' => 'two',
            ],
        ];

        $model = $this->data->toObject(
            data: $data,
            class: EntryModelOne::class,
            injectionMap: function (array $request) {
                if (isset($request['one']['property-one'])) {
                    return [ModelInterfaceBase::class => ModelOne::class];
                }
                if (isset($request['one']['property-two'])) {
                    return [ModelInterfaceBase::class => ModelTwo::class];
                }

                return [];
            }
        );

        self::assertEquals('two', $model->one->propertyTwo);
    }
    */

    public function testArrayInModel(): void
    {
        $data = [
            'propertyOne' => [359418786]
        ];

        $model = $this->data->toObject($data, ConstructorPropertyArrayModel::class);
        self::assertEquals('359418786', $model->propertyOne[0]);
    }

    public function testRootCollection(): void
    {
        $data = [
            [
                'propertyOne' => 11,
                'propertyTwo' => 12,
            ],
            [
                'propertyOne' => 21,
                'propertyTwo' => 22,
            ],
        ];

        $model = $this->data->toObject($data, CollectionRootModel::class);

        self::assertEquals(11, $model->collection[0]->propertyOne);
        self::assertEquals(12, $model->collection[0]->propertyTwo);
        self::assertEquals(21, $model->collection[1]->propertyOne);
        self::assertEquals(22, $model->collection[1]->propertyTwo);
    }

    public function testSimpleAddProperties(): void
    {
        $data = [
            'one' => 1,
            'two' => 2,
        ];

        $model = $this->data->toObject(
            data: $data,
            class: EmptyModel::class,
            strategy: new AddProperties(),
        );

        self::assertEquals(1, $model->one);
        self::assertEquals(2, $model->two);
    }
}
