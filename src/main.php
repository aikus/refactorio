<?php

declare (strict_types=1);
require_once __DIR__ . '/../vendor/autoload.php';
use RefactoringRobot\Refactorer;
$refactorer = new Refactorer();
file_put_contents($argv[1], $refactorer->refact(file_get_contents($argv[1])));