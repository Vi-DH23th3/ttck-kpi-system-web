<?php

namespace App\Services\Support;

class CompareService{
    public function compare($actual, $operator, $target)
    {
        return match ($operator) {
            '='  => $actual == $target,
            '>=' => $actual >= $target,
            '<=' => $actual <= $target,
            '>'  => $actual > $target,
            '<'  => $actual < $target,
            '!=' => $actual != $target,
            default => false,
        };
    }
}