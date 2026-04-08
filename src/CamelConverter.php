<?php

namespace Sergey144010\Data;

class CamelConverter implements ToCamelInterface
{
    private \Closure $converter;

    public function __construct(callable $converter)
    {
        $this->converter = \Closure::fromCallable($converter);
    }

    public function toCamel(string $string): string
    {
        return ($this->converter)($string);
    }
}