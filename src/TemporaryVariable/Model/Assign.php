<?php

declare (strict_types=1);
namespace Refactorio\TemporaryVariable\Model;

use PhpParser\Node;

class Assign extends NoopModel
{
    public function getRemoveVariable() : string
    {
        return $this->isTemporaryVariableAssign($this->getNode())
            ? $this->getNode()->var->name : "";
    }

    public function getSaveVariables() : array
    {
        return $this->getNode()->var->getType() == 'Expr_ArrayDimFetch'
            ? [$this->getNode()->var->var->name] : [];
    }

    private function isTemporaryVariableAssign(\PhpParser\Node\Expr\Assign $assign)
    {
        return $assign->var->getType() == 'Expr_Variable'
            && (in_array(
                $assign->expr->getType(), [
                'Expr_ConstFetch',
                'Expr_ClassConstFetch',
                ]
            ) || $this->isFunctionCall($assign->expr));
    }
 
    private function isFunctionCall(Node $node) : bool
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