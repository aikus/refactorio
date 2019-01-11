<?php

$a = getA();
$b = MyClass::getB();
$saveVariable = getBoo();
$c = new MyClass();
print_r(compact('saveVariable', $c->getSaveVariable()));