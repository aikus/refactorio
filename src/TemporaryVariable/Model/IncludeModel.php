<?php

declare (strict_types=1);
namespace Refactorio\TemporaryVariable\Model;

use PhpParser\Node;
use Refactorio\TemporaryVariable\Model\NoopModel;

class IncludeModel extends NoopModel
{
    public function saveAllParameters() : bool
    {
        return true;
    }
}