<?php

$a = array();
while ($row = getRow()) {
    $a[$row['a']][] = $row;
}