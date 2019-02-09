<?php

$c = 'static string';
$v = call();
$a = getVariables();
$a[] = 'v';
print_r(compact($a));