<?php
declare (strict_types=1);
namespace Refactorio\TemporaryVariable;

use PhpParser\NodeVisitorAbstract;
use PhpParser\Node;
use PhpParser\Node\Expr\Assign;

abstract class TemporaryVariableVisitor extends NodeVisitorAbstract
{
    const NOT_FUNCTION = '--NOT-FUNCTION--';
    const CLOSURE = '--CLOSURE--';

    private $functions = [self::NOT_FUNCTION];
    private $closureIndex = 0;

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

    protected function functionEnd()
    {
        array_pop($this->functions);
    }

    protected function getActualFunction() : string
    {
        return $this->functions[count($this->functions) - 1];
    }

    protected function getParentFunction() : string
    {
        return $this->functions[count($this->functions) - 2];
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

    private function functionStart(Node $node)
    {
        $this->functions[] = $node->getType() == 'Expr_Closure'
            ? self::CLOSURE.($this->closureIndex++)
            : $node->name->name;
    }

    private function isFunction(Node $node)
    {
        return $node->getType() == 'Stmt_ClassMethod'
            || $node->getType() == 'Stmt_Function'
            || $node->getType() == 'Expr_Closure';
    }
}