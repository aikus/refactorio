<?php

declare (strict_types=1);
namespace Refactorio\TemporaryVariable\Model;

class FuncCall extends NoopModel
{
    private $removeVariables = [];
    private $saveVariables = [];
    private $saveAll = false;
    const FUNCTION_WITH_LINK = [
        'asort' => [0],
    ];

    public function __construct(\PhpParser\Node\Expr\FuncCall $node)
    {
        parent::__construct($node);
        if($node->name == 'compact') {
            $this->calcValuesFromArray($node->args);
        } else {
            $this->saveVariables = array_merge($this->saveVariables, $this->getLinkVariables());
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

    private function getLinkVariables() : array
    {
        $result = [];
        if($this->getNode()->name->getType() != 'Name'
        || !key_exists($this->getNode()->name->toString(), self::FUNCTION_WITH_LINK)) {
            return $result;
        }
        foreach(self::FUNCTION_WITH_LINK[$this->getNode()->name->toString()] as $position) {
            if(key_exists($position, $this->getNode()->args)
            && $this->getNode()->args[$position]->value->getType() == 'Expr_Variable') {
                $result[] = $this->getNode()->args[$position]->value->name;
            }
        }
        return $result;
    }
}