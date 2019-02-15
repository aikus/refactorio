<?php

$closure1 = function(SomeClass $var) {
    $a = $var->someMethod();
    $closure1 = function($a) {
        return $a * $a;
    };
    return $a + $closure1(3);
};