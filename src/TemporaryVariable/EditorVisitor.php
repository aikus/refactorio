<?php
declare(strict_types=1);

namespace Refactorio\TemporaryVariable;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use PhpParser\Node\Expr\Assign;
use phpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeTraverser;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\ClassConstFetch;

class EditorVisitor extends NodeVisitorAbstract
{
    private $temporaryVariables = [];
    private $function = CollectorVisitor::NOT_FUNCTION;
    private $variables = [];

    const NOT_FUNCTION = '--NOT-FUNCTION--';

    public function __construct(array $temporaryVariables)
    {
        $this->temporaryVariables = $temporaryVariables;
    }
    
    public function enterNode(Node $node)
    {
        if ($this->isFunction($node))
            {
                $this->functionStart($node);
            }
    }
    
    public function leaveNode(Node $node)
    {
        if($this->isFunction($node))
            {
                return $this->functionEnd();
            }
        elseif($this->isTemporaryAssign($node))
            {
                return $this->variableAssign($node->expr);
            }
        elseif($node instanceof Variable)
            {
                return $this->variableExecute($node);
            }
    }

    public function getTemporaryVariables() : array
    {
        return $this->temporaryVariables;
    }

    private function getActualFunction() : string
    {
        return $this->function;
    }
    
    private function getVariableName(Assign $assign) : string
    {
        return $assign->var->name;
    }

    private function functionStart(Node $node)
    {
        $this->function = $node->name->name;
    }

    private function functionEnd()
    {
        $this->variables = [];
        $this->function = static::NOT_FUNCTION;
    }

    private function variableExecute(Variable $node)
    {
        if(key_exists($node->name, $this->variables))
            {
                return $this->variables[$node->name];
            }
    }

    private function variableAssign(Assign $assign)
    {
        if($this->isTemporaryVariable($this->getVariableName($assign)))
            {
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

    private function isFunction(Node $node)
    {
        return $node instanceof ClassMethod || $node instanceof Function_;
    }
    
    private function isTemporaryAssign(Node $node)
    {
        return $node instanceof Expression &&
            $node->expr instanceof Assign &&
            $node->expr->var instanceof Variable &&
            ($node->expr->expr instanceof FuncCall ||
            $node->expr->expr instanceof MethodCall ||
            $node->expr->expr instanceof StaticCall ||
            $node->expr->expr instanceof ConstFetch ||
            $node->expr->expr instanceof ClassConstFetch);
    }
}