<?php

declare (strict_types=1);
namespace Refactorio\TemporaryVariable;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Variable;

class CollectorVisitor extends TemporaryVariableVisitor
{
    private $temporaryVariables = [];
    
    public function leaveNode(Node $node)
    {
        if ($this->isMethodCall($node)) {
            return $this->saveVariable($node->expr->var);
        }
        return parent::leaveNode($node);
    }
    
    public function getTemporaryVariables() : array
    {
        return $this->temporaryVariables;
    }
    
    private function isMethodCall(Node $node) : bool
    {
        return $node->getType() == 'Stmt_Expression'
            && $node->expr->getType() == 'Expr_MethodCall';
    }

    protected function variableAssign(Assign $node)
    {
        $this->temporaryVariables[$this->getActualFunction()][$this->getVariableName($node)] = true;
    }
    
    private function saveVariable(Variable $variable)
    {
        $this->temporaryVariables[$this->getActualFunction()][$variable->name] = false;
    }
}