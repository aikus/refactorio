<?php

declare (strict_types=1);
namespace Refactorio\TemporaryVariable\Model;

use PhpParser\Node;

class Closure extends NoopModel
{
    public function getSaveVariables() : array
    {
        $result = [];
        foreach($this->getNode()->uses as $use) {
            $result[] = $use->var->name;
        }
        return $result;
    }

    public function isParentFunction() : bool
    {
        return true;
    }
}