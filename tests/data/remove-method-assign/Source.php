<?php

$db = new DB();

$a = $db->getA('param');

$db->save($a);
someFunction(7070 + $a);