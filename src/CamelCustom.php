<?php

namespace Sergey144010\Data;

class CamelCustom implements ToCamelInterface
{
    public function toCamel(string $string): string
    {
        $words = explode(' ', str_replace(['-', '_'], ' ', $string));
        $studlyWords = array_map(function ($word) {
            return mb_strtoupper(mb_substr($word, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($word, 1, null, 'UTF-8');
        }, $words);

        return lcfirst(implode($studlyWords));
    }
}
