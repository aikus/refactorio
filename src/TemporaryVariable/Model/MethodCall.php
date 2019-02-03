<?php

declare (strict_types=1);
namespace Refactorio\TemporaryVariable\Model;

class MethodCall extends NoopModel
{
    public function getSaveVariables() : array
    {
        return [$this->getNode()->var->name];
    }
}