<?php

declare (strict_types=1);
namespace Refactorio\TemporaryVariable;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Expr\FuncCall;

class CollectorVisitor extends TemporaryVariableVisitor
{
    private $temporaryVariables = [];
    
    public function leaveNode(Node $node)
    {
        if($this->isInclude($node)) {
            $this->temporaryVariables = [];
            return;
        }
        try {
            $this->saveVariables($this->getSaveVariables($node));
        } catch (CalculateValueException $exception) {
            $this->temporaryVariables = [];
            foreach($exception->getRemoveVariables() as $variable) {
                $this->temporaryVariables[$this->getActualFunction()][$variable] = true;
            }
        }

        return parent::leaveNode($node);
    }
    
    public function getTemporaryVariables() : array
    {
        return $this->temporaryVariables;
    }

    protected function variableAssign(Assign $node)
    {
        $this->temporaryVariables[$this->getActualFunction()][$this->getVariableName($node)] = true;
    }

    private function getSaveVariables(Node $node) : array
    {
        if($this->isMethodCall($node)) {
            return [$node->expr->var->name];
        }
        if($this->isArrayDimAsign($node)) {
            return [$node->expr->var->var->name];
        }
        if($this->isCompact($node)) {
            return $this->getCompactVariables($node);
        }
        return [];
    }

    private function isCompact(Node $node)
    {
        return $node->getType() == 'Expr_FuncCall' && $node->name == 'compact';
    }

    private function getCompactVariables(FuncCall $node)
    {
        return $this->getValuesFromArray($node->args);
    }

    private function getValuesFromArray(array $array) : array
    {
        $result = [];
        foreach($array as $val) {
            if($val->value->getType() == 'Scalar_String') {
                $result[] = $val->value->value;
            } elseif($val->value->getType() == 'Expr_Array') {
                $result = array_merge($result, $this->getValuesFromArray($val->value->items));
            } elseif($val->value->getType() == 'Expr_Variable') {
                throw new CalculateValueException([$val->value->name]);
            } elseif($this->isFunctionCall($val->value)) {
                throw new CalculateValueException([]);
            }
        }
        return $result;
    }

    private function isInclude(Node $node)
    {
        return $node->getType() == 'Stmt_Expression'
            && $node->expr->getType() == 'Expr_Include';
    }

    private function isArrayDimAsign(Node $node)
    {
        return $node->getType() == 'Stmt_Expression'
            && $node->expr->getType() == 'Expr_Assign'
            && $node->expr->var->getType() == 'Expr_ArrayDimFetch';
    }
    
    private function isMethodCall(Node $node) : bool
    {
        return $node->getType() == 'Stmt_Expression'
            && $node->expr->getType() == 'Expr_MethodCall';
    }
    
    private function saveVariables(array $variables)
    {
        foreach($variables as $variable) {
            $this->temporaryVariables[$this->getActualFunction()][$variable] = false;
        }
    }
}