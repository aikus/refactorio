<?php
declare(strict_types=1);

namespace RefactoringRobot\Visitor;

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

class TemporaryVariableRemover extends NodeVisitorAbstract
{
    private $variables = [];

    public function enterNode(Node $node)
    {
        if($this->isTemporaryAssign($node))
            {
                return NodeTraverser::DONT_TRAVERSE_CHILDREN;
            }
        elseif ($this->isFunctionStart($node))
            {
                $this->variables = [];
            }
        //else echo get_class($node), "\n";
    }
    
    public function leaveNode(Node $node)
    {
        if($this->isTemporaryAssign($node))
            {
                $this->variables[$node->expr->var->name] = $node->expr->expr;
                return NodeTraverser::REMOVE_NODE;
            }
        elseif($node instanceof Variable &&
        key_exists($node->name, $this->variables))
            {
                return $this->variables[$node->name];
            }
        //else echo get_class($node), "\n";
    }

    private function isFunctionStart(Node $node)
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