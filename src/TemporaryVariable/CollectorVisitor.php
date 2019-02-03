<?php

declare (strict_types=1);
namespace Refactorio\TemporaryVariable;

use PhpParser\Node;

class CollectorVisitor extends TemporaryVariableVisitor
{
    private $temporaryVariables = [];
    private $builder;
    
    public function leaveNode(Node $node)
    {
        $model = $this->getBuilder()->get($node);
        if($model->saveAllParameters()) {
            $this->temporaryVariables = [];//TODO: Надо очищать только в методе
        }
        if($model->getRemoveVariable()) {
            $this->temporaryVariables[$this->getActualFunction()][$model->getRemoveVariable()] = true;
        }
        foreach($model->getRemoveVariables() as $variable) {
            $this->temporaryVariables[$this->getActualFunction()][$variable] = true;
        }
        foreach($model->getSaveVariables() as $variable) {
            $this->temporaryVariables[$this->getActualFunction()][$variable] = false;
        }

        parent::leaveNode($node);
    }
    
    public function getTemporaryVariables() : array
    {
        return $this->temporaryVariables;
    }

    private function getBuilder() : ModelBuilder
    {
        if(!$this->builder) {
            $this->builder = new ModelBuilder;
        }
        return $this->builder;
    }
}