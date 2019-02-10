<?php

$a = someFunc();
$b = foo();
$func = function($c) use ($a)
{
    return [$c, $a];
};
print_r([$b, $func(3)]);