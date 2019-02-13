<?php

$closure = function ($a) {
    return [rand(), $a];
};
print_r($closure(someFunc() + 10));