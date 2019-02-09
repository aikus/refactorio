<?php
declare (strict_types=1);
namespace Refactorio\TemporaryVariable;

use PhpParser\NodeVisitorAbstract;
use PhpParser\Node;
use PhpParser\Node\Expr\Assign;

abstract class TemporaryVariableVisitor extends NodeVisitorAbstract
{
    const NOT_FUNCTION = '--NOT-FUNCTION--';

    private $function = self::NOT_FUNCTION;

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
        }
    }

    protected function isFunction(Node $node)
    {
        return $node->getType() == 'Stmt_ClassMethod'
            || $node->getType() == 'Stmt_Function';
    }

    protected function functionStart(Node $node)
    {
        $this->function = $node->name->name;
    }

    protected function functionEnd()
    {
        $this->function = self::NOT_FUNCTION;
    }

    protected function getActualFunction() : string
    {
        return $this->function;
    }

    protected function getVariableName(Assign $assign) : string
    {
        return $assign->var->name;
    }

    protected function isFunctionCall(Node $node) : bool
    {
        return in_array(
            $node->getType(), [
            'Expr_FuncCall',
            'Expr_StaticCall',
            'Expr_MethodCall',
            ]
        );
    }
}