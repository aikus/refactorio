<?php

$closure1 = function (SomeClass $var) {
    $closure1 = function ($a) {
        return $a * $a;
    };
    return $var->someMethod() + $closure1(3);
};