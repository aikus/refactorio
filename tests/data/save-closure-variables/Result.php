<?php

$a = someFunc();
$func = function ($c) use($a) {
    return [$c, $a];
};
print_r([foo(), $func(3)]);