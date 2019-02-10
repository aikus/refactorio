<?php

declare (strict_types=1);
namespace Refactorio\TemporaryVariable\Model;

use PhpParser\Node;

class ClosureUse extends NoopModel
{
    public function getSaveVariables() : array
    {
        return $this->getNode()->var->getType() == 'Expr_Variable'
            ? [$this->getNode()->var->name] : [];
    }
}