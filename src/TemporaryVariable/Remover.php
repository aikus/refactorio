<?php

declare (strict_types=1);
namespace Refactorio\TemporaryVariable;

use PhpParser\NodeTraverser;
class Remover
{
    public function refact(array $ast)
    {
        return $this->removeTemporaryVariable($ast, $this->getTemporaryVariable($ast));
    }
    private function getTemporaryVariable(array $ast) : array
    {
        $collector = new CollectorVisitor();
        $traverser = new NodeTraverser();
        $traverser->addVisitor($collector);
        $traverser->traverse($ast);
        return $collector->getTemporaryVariables();
    }
    private function removeTemporaryVariable(array $ast, array $variables) : array
    {
        $editor = new EditorVisitor($variables);
        $traverser = new NodeTraverser();
        $traverser->addVisitor($editor);
        return $traverser->traverse($ast);
    }
}