<?php

declare (strict_types=1);
namespace Refactorio\TemporaryVariable\Model;

use PhpParser\Node\Expr\Variable;
use PhpParser\Node;

class MethodCall extends NoopModel
{
    public function getSaveVariables() : array
    {
        return [$this->getVariable($this->getNode())->name];
    }

    private function getVariable(Node $node): Variable
    {
        if($node->getType() == 'Expr_Variable') {
            return $node;
        }
        return $this->getVariable($node->var);
    }
}