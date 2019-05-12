<?php

declare (strict_types=1);
namespace Refactorio\TemporaryVariable;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use phpParser\Node\Expr\Variable;
use PhpParser\NodeTraverser;

class EditorVisitor extends TemporaryVariableVisitor
{
    private $temporaryVariables = [];
    private $variables = [];
    public function __construct(array $temporaryVariables)
    {
        $this->temporaryVariables = $temporaryVariables;
    }
    
    public function leaveNode(Node $node)
    {
        if ($this->needVariableExecute($node)) {
            return $this->variableExecute($node);
        }
        if ($this->isTemporaryVariableExpression($node)
        && $this->isTemporaryVariable($this->getVariableName($node->expr))) {
            return $this->variableAssign($node->expr);
        }
        return parent::leaveNode($node);
    }

    protected function functionEnd()
    {
        $this->variables[$this->getActualFunction()] = [];
        parent::functionEnd();
    }

    private function variableAssign(Assign $assign)
    {
        $this->variables[$this->getActualFunction()][$this->getVariableName($assign)] = $assign->expr;
        return NodeTraverser::REMOVE_NODE;
    }

    private function needVariableExecute(Node $node)
    {
        return $node->getType() == 'Expr_Variable'
            && key_exists($this->getActualFunction(), $this->variables)
            && key_exists($node->name, $this->variables[$this->getActualFunction()]);        
    }

    private function variableExecute(Variable $node)
    {
        return $this->variables[$this->getActualFunction()][$node->name];
    }

    private function isTemporaryVariable(string $name) : bool
    {
        return key_exists($this->getActualFunction(), $this->temporaryVariables)
            && key_exists($name, $this->temporaryVariables[$this->getActualFunction()])
            && $this->temporaryVariables[$this->getActualFunction()][$name];
    }

    
    private function isTemporaryVariableExpression(Node $node)
    {
        return $node->getType() == 'Stmt_Expression'
            && $node->expr->getType() == 'Expr_Assign'
            && $this->isTemporaryVariableAssign($node->expr);
    }

    private function isTemporaryVariableAssign(Assign $assign)
    {
        return $assign->var->getType() == 'Expr_Variable'
            && (in_array(
                $assign->expr->getType(), [
                'Expr_ConstFetch',
                'Expr_ClassConstFetch',
                ]
            ) || $this->isFunctionCall($assign->expr));
    }
}