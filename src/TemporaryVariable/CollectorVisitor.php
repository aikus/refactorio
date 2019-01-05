<?php

declare (strict_types=1);
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
class CollectorVisitor extends NodeVisitorAbstract
{
    private $temporaryVariables = [];
    private $function = CollectorVisitor::NOT_FUNCTION;
    const NOT_FUNCTION = '--NOT-FUNCTION--';
    public function enterNode(Node $node)
    {
        if ($this->isFunction($node)) {
            $this->functionStart($node);
        }
    }
    public function leaveNode(Node $node)
    {
        if ($this->isFunction($node)) {
            return $this->functionEnd();
        } elseif ($this->isTemporaryAssign($node)) {
            return $this->variableAssign($node->expr);
        } elseif ($this->isMethodCall($node)) {
            $this->saveVariable($node->expr->var);
        }
    }
    public function getTemporaryVariables() : array
    {
        return $this->temporaryVariables;
    }
    private function isMethodCall(Node $node) : bool
    {
        return $node instanceof Expression && $node->expr instanceof MethodCall;
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
        $this->function = static::NOT_FUNCTION;
    }
    private function variableAssign(Assign $node)
    {
        $this->temporaryVariables[$this->getActualFunction()][$this->getVariableName($node)] = true;
    }
    private function isFunction(Node $node)
    {
        return $node instanceof ClassMethod || $node instanceof Function_;
    }
    private function saveVariable(Variable $variable)
    {
        $this->temporaryVariables[$this->getActualFunction()][$variable->name] = false;
    }
    private function isTemporaryAssign(Node $node)
    {
        return $node instanceof Expression && $node->expr instanceof Assign && $node->expr->var instanceof Variable && ($node->expr->expr instanceof FuncCall || $node->expr->expr instanceof MethodCall || $node->expr->expr instanceof StaticCall || $node->expr->expr instanceof ConstFetch || $node->expr->expr instanceof ClassConstFetch);
    }
}