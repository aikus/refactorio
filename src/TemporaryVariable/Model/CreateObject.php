<?php


namespace Refactorio\TemporaryVariable\Model;


class CreateObject extends NoopModel
{
    public function getSaveVariables(): array
    {
        if($this->getNode()->class->getType() == 'Expr_Variable') {
            return [$this->getNode()->class->name];
        }
        return [];
    }
}