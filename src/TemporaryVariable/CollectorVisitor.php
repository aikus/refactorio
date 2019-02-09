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
            $this->temporaryVariables[$this->getActualFunction()][$model->getRemoveVariable()] = true;
        }
        $this->removeVariables($model->getRemoveVariables());
        $this->saveVariables($model->getSaveVariables());

        parent::leaveNode($node);
    }
    
    public function getTemporaryVariables() : array
    {
        return $this->temporaryVariables;
    }

    private function removeVariables(array $variables)
    {
        foreach($variables as $variable) {
            if(!key_exists($this->getActualFunction(), $this->saveVariables)
                || !key_exists($variable, $this->saveVariables[$this->getActualFunction()])
            ) {
                $this->temporaryVariables[$this->getActualFunction()][$variable] = true;
            }
        }
    }

    private function saveAllParameters()
    {
        $this->temporaryVariables[$this->getActualFunction()] = [];
    }

    private function saveVariables(array $variables)
    {
        foreach($variables as $variable) {
            $this->saveVariables[$this->getActualFunction()][$variable] = true;
            $this->temporaryVariables[$this->getActualFunction()][$variable] = false;
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