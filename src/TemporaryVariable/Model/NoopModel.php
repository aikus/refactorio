<?php

declare (strict_types=1);
namespace Refactorio\TemporaryVariable\Model;

use PhpParser\Node;
use Refactorio\TemporaryVariable\Model;

class NoopModel implements Model
{
    private $node;

    public function __construct(Node $node)
    {
        $this->node = $node;
    }
    
    public function saveAllParameters() : bool
    {
        return false;
    }

    public function getSaveVariables() : array
    {
        return [];
    }

    public function getRemoveVariable() : string
    {
        return "";
    }

    public function getRemoveVariables() : array
    {
        return [];
    }

    protected function getNode() : Node
    {
        return $this->node;
    }
}