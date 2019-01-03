<?php

$db = new DB();
$db->save($db->getA('param'));
someFunction(7070 + $db->getA('param'));