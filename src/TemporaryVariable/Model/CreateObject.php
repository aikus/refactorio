<?php


namespace Refactorio\TemporaryVariable\Model;


class CreateObject extends NoopModel
{
    public function getSaveVariables(): array
    {
        return $this->getNode()->class->getType() == 'Expr_Variable' ? [$this->getNode()->class->name] : [];
    }
}