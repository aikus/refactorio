<?php

declare (strict_types=1);
namespace Refactorio;

use PhpParser\ParserFactory;
use PhpParser\NodeDumper;
use PhpParser\NodeTraverser;
use Refactorio\TemporaryVariable\Remover;
use PhpParser\PrettyPrinter;
class Refactorer
{
    public function refact(string $source) : string
    {
        $remover = new Remover();
        return (new PrettyPrinter\Standard())
            ->prettyPrintFile(
                $remover->refact(
                    (new ParserFactory())->create(ParserFactory::PREFER_PHP7)
                        ->parse($source)
                )
            );
    }
}