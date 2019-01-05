<?php
declare(strict_types=1);

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
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $ast = $parser->parse($source);
        $remover = new Remover;
        $result = $remover->refact($ast);
        return (new PrettyPrinter\Standard)->prettyPrintFile($result);
    }
}