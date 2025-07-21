<?php

namespace Sergey144010\Data;

use Sergey144010\Data\Attribute\Collection;
use Sergey144010\Data\Attribute\CollectionRoot;
use Sergey144010\Data\Strategy\StepByStep;
use Sergey144010\Data\Strategy\StrategyInterface;

final class Data
{
    public function __construct(private ToCamelInterface $camel)
    {
    }

    /**
     * @param array $data
     * @param class-string $class
     * @param array $injectionMap
     * @param class-string|null $rootClass
     * @param string|null $currentProperty
     * @return mixed
     * @throws \ReflectionException
     */
    public function toObject(
        array $data,
        string $class,
        StrategyInterface $strategy = new StepByStep(),
        array $injectionMap = [],
        string $rootClass = null,
        string $currentProperty = null,
    ): mixed {
        if (! isset($rootClass)) {
            $rootClass = $class;
        }

        $preparedData = $this->preparedData($data);

        $reflection = new \ReflectionClass($class);
        $parameters = $strategy->handle($reflection);

        if (isset($injectionMap[$class]) && is_callable($injectionMap[$class])) {
            $injectionMap[$class]
                = $injectionMap[$class]($data);
        }

        if ($reflection->isInterface()) {
            if (array_key_exists($rootClass, $injectionMap)) {
                if (isset($injectionMap[$rootClass][$currentProperty][$class])) {
                    return $this->toObject(
                        data: $data,
                        class: $injectionMap[$rootClass][$currentProperty][$class],
                        injectionMap: $injectionMap,
                        rootClass: $class
                    );
                }
                if (
                    isset($injectionMap[$rootClass][$currentProperty]) &&
                    is_string($injectionMap[$rootClass][$currentProperty])
                ) {
                    /** @var class-string $injectionClass */
                    $injectionClass = $injectionMap[$rootClass][$currentProperty];

                    return $this->toObject(
                        data: $data,
                        class: $injectionClass,
                        injectionMap: $injectionMap,
                        rootClass: $class
                    );
                }
            }

            if (array_key_exists($class, $injectionMap)) {
                return $this->toObject(
                    data: $data,
                    class: $injectionMap[$class],
                    injectionMap: $injectionMap,
                    rootClass: $class
                );
            }
        }

        if (empty($parameters)) {
            $object = $reflection->newInstance();
            foreach ($preparedData as $key => $value) {
                if (is_string($key)) {
                    $object->$key = $value;
                }
            }

            return $object;
        }

        $object = $reflection->newInstanceWithoutConstructor();
        foreach ($parameters as $parameter) {
            if (! $parameter->hasType()) {
                $reflection->getProperty($parameter->getName())
                    ->setValue($object, $preparedData[$parameter->getName()]);

                continue;
            }
            /** @var \ReflectionNamedType  $parameterType */
            $parameterType = $parameter->getType();
            if ($this->isArrayType($parameterType)) {
                if (count($parameter->getAttributes()) === 0) {
                    $reflection->getProperty($parameter->getName())
                        ->setValue($object, $preparedData[$parameter->getName()]);
                    continue;
                }

                foreach ($parameter->getAttributes() as $attribute) {
                    if (
                        $attribute->getName() === Collection::class ||
                        $attribute->getName() === CollectionRoot::class
                    ) {
                        $collection = [];

                        $inList = [];
                        if (
                            $attribute->getName() === Collection::class
                        ) {
                            $inList = $preparedData[$parameter->getName()];
                        }
                        if (
                            $attribute->getName() === CollectionRoot::class
                        ) {
                            $inList = $preparedData;
                        }

                        foreach ($inList as $inData) {
                            $collection[] = $this->toObject(
                                data: $inData,
                                class: $attribute->getArguments()[0],
                                injectionMap: $injectionMap,
                                currentProperty: $parameter->getName(),
                                rootClass: $class
                            );
                        }
                        $reflection->getProperty($parameter->getName())
                            ->setValue(
                                $object,
                                $collection
                            );
                    }
                }
                continue;
            }

            if ($this->isBasicType($parameterType)) {
                if (! isset($preparedData[$parameter->getName()])) {
                    /**
                     * Constructor
                     * @see https://www.php.net/manual/ru/class.reflectionparameter.php
                     */
                    if ($parameter instanceof \ReflectionParameter) {
                        if ($parameter->isDefaultValueAvailable()) {
                            $reflection->getProperty($parameter->getName())
                                ->setValue($object, $parameter->getDefaultValue());
                            continue;
                        }
                    }

                    /**
                     * Property
                     * @see https://www.php.net/manual/en/class.reflectionproperty.php
                     */
                    if ($parameter instanceof \ReflectionProperty) {
                        if ($parameter->hasDefaultValue()) {
                            $reflection->getProperty($parameter->getName())
                                ->setValue($object, $parameter->getDefaultValue());
                            continue;
                        }
                    }

                    $reflection->getProperty($parameter->getName())
                        ->setValue($object, null);
                    continue;
                }

                $reflection->getProperty($parameter->getName())
                    ->setValue($object, $preparedData[$parameter->getName()]);
                continue;
            }

            /** Custom object */
            /** @var class-string $className */
            $className = $parameterType->getName();
            $reflection->getProperty($parameter->getName())
                ->setValue(
                    $object,
                    $preparedData[$parameter->getName()] === null
                        ? null
                        : $this->toObject(
                            data: $preparedData[$parameter->getName()],
                            class: $className,
                            injectionMap: $injectionMap,
                            currentProperty: $parameter->getName(),
                            rootClass: $class
                        )
                );
        }

        return $object;
    }

    private function preparedData(array $data): array
    {
        $preparedData = [];

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $preparedData[$this->camel->toCamel($key)] = $this->preparedData($value);
            } else {
                $preparedData[$this->camel->toCamel($key)] = $value;
            }
        }

        return $preparedData;
    }

    private function isBasicType(\ReflectionNamedType $type): bool
    {
        return $type->isBuiltin();
    }

    private function isArrayType(\ReflectionNamedType $type): bool
    {
        return $type->getName() === 'array';
    }
}
