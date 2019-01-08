<?php

$a = startValue();
while ($row = getRow()) {
    $a[] = $row;
}