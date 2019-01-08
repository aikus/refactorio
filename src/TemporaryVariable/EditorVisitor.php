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
        if ($node instanceof Variable) {
            return $this->variableExecute($node);
        }
        return parent::leaveNode($node);
    }

    protected function functionEnd()
    {
        $this->variables = [];
        parent::functionEnd();
    }
    private function variableExecute(Variable $node)
    {
        if (key_exists($node->name, $this->variables)) {
            return $this->variables[$node->name];
        }
    }
    protected function variableAssign(Assign $assign)
    {
        if ($this->isTemporaryVariable($this->getVariableName($assign))) {
            $this->variables[$this->getVariableName($assign)] = $assign->expr;
            return NodeTraverser::REMOVE_NODE;
        }
    }
    private function isTemporaryVariable(string $name) : bool
    {
        return key_exists($this->getActualFunction(), $this->temporaryVariables)
            && key_exists($name, $this->temporaryVariables[$this->getActualFunction()])
            && $this->temporaryVariables[$this->getActualFunction()][$name];
    }
}