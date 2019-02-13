<?php

$a = someFunc();
$closure = function ($a) {
    return [rand(), $a];
};
print_r($closure($a + 10));