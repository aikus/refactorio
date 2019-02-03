<?php

declare (strict_types=1);
namespace Refactorio\TemporaryVariable\Model;

class FuncCall extends NoopModel
{
    private $removeVariables = [];
    private $saveVariables = [];
    private $saveAll = false;

    public function __construct(\PhpParser\Node\Expr\FuncCall $node)
    {
        parent::__construct($node);
        if($node->name == 'compact') {
            $this->calcValuesFromArray($node->args);
        } elseif($node->name == 'asort') {
            $this->saveVariables[] = $node->args[0]->value->name;
        }
    }

    public function saveAllParameters() : bool
    {
        return $this->saveAll;
    }

    public function getSaveVariables() : array
    {
        return $this->saveVariables;
    }

    public function getRemoveVariables() : array
    {
        return $this->removeVariables;
    }

    private function calcValuesFromArray(array $array)
    {
        foreach($array as $val) {
            if($val->value->getType() == 'Scalar_String') {
                $this->saveVariables[] = $val->value->value;
            } elseif($val->value->getType() == 'Expr_Array') {
                $this->calcValuesFromArray($val->value->items);
            } elseif($val->value->getType() == 'Expr_Variable') {
                $this->saveAll = true;
                $this->removeVariables[] = $val->value->name;//TODO: Возможно переменную нельзя удалять.
            } elseif(in_array($val->value->getType(), ['Expr_MethodCall', 'Expr_FuncCall'])) {
                $this->saveAll = true;
            }
        }
    } 
}