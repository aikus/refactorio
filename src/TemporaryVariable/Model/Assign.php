<?php

declare (strict_types=1);
namespace Refactorio\TemporaryVariable\Model;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign as PhpAssign;

class Assign extends NoopModel
{
    public function getRemoveVariable() : string
    {
        return $this->isTemporaryVariableAssign($this->getNode())
            ? $this->getNode()->var->name : "";
    }

    public function getSaveVariables() : array
    {
//        if($this->getNode()->var->getType() == 'Expr_ArrayDimFetch') {
//            var_dump($this->getNode()->var);
//        }
        $array = $this->getLastArray($this->getNode()->var);
        return $array && $this->isVariable($array)
            ? [$array->var->name] : [];
    }

    private function getLastArray(Node $node): ?ArrayDimFetch
    {
        if($node->getType() != 'Expr_ArrayDimFetch') {
            return null;
        }
        return $node->var->getType() != 'Expr_ArrayDimFetch' ? $node : $this->getLastArray($node->var);
    }

    private function isVariable($node): bool
    {
        return !($node->var->getType() == 'Expr_PropertyFetch');
    }

    private function isTemporaryVariableAssign(PhpAssign $assign)
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