<?php
declare(strict_types=1);

namespace RefactoringRobot;

use PhpParser\ParserFactory;
use PhpParser\NodeDumper;
use PhpParser\NodeTraverser;
use RefactoringRobot\Visitor\TemporaryVariableRemover;
use PhpParser\PrettyPrinter;

class Refactorer
{
    public function refact(string $source) : string
    {
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $ast = $parser->parse($source);
        $traverser = new NodeTraverser;
        $remover = new TemporaryVariableRemover;
        $traverser->addVisitor($remover);
        $result = $traverser->traverse($ast);
        return (new PrettyPrinter\Standard)->prettyPrintFile($result);
    }
}