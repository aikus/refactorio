<?php

declare (strict_types=1);
namespace Refactorio\TemporaryVariable;

use PhpParser\Node;

class CollectorVisitor extends TemporaryVariableVisitor
{
    private $temporaryVariables = [];
    private $saveVariables = [];
    private $builder;
    
    public function leaveNode(Node $node)
    {
        $model = $this->getBuilder()->get($node);
        if($model->saveAllParameters()) {
            $this->saveAllParameters();
        }
        if($model->getRemoveVariable()) {
            $this->temporaryVariables[$this->funcName($model)][$model->getRemoveVariable()] = true;
        }
        $this->removeVariables($model);
        $this->saveVariables($model);
    }
    
    public function getTemporaryVariables() : array
    {
        return $this->temporaryVariables;
    }

    private function removeVariables(Model $model)
    {
        foreach($model->getRemoveVariables() as $variable) {
            if(!key_exists($this->funcName($model), $this->saveVariables)
            || !in_array($variable, $this->saveVariables[$this->funcName($model)])
            ) {
                $this->temporaryVariables[$this->funcName($model)][$variable] = true;
            }
        }
    }

    private function funcName(Model $model) : string
    {
        return $model->isParentFunction()
            ? $this->getParentFunction()
            : $this->getActualFunction();
    }

    private function saveAllParameters()
    {
        $this->temporaryVariables[$this->getActualFunction()] = [];
    }

    private function saveVariables(Model $model)
    {
        foreach($model->getSaveVariables() as $variable) {
            $this->saveVariables[$this->funcName($model)][] = $variable;
            $this->temporaryVariables[$this->funcName($model)][$variable] = false;
        }
    }

    private function getBuilder() : ModelBuilder
    {
        if(!$this->builder) {
            $this->builder = new ModelBuilder;
        }
        return $this->builder;
    }
}