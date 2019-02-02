<?php

declare (strict_types=1);
namespace Refactorio\TemporaryVariable;

use PhpParser\Node;
use Refactorio\TemporaryVariable\Model\IncludeModel;
use Refactorio\TemporaryVariable\Model\NoopModel;
use Refactorio\TemporaryVariable\Model\Assign;

class ModelBuilder
{
    public function get(Node $node) : Model
    {
        if($this->isInclude($node)) {
            return new IncludeModel($node);
        }
        if($this->isAssign($node)) {
            return new Assign($node);
        }
        return new NoopModel($node);
    }

    private function isInclude(Node $node)
    {
        return $node->getType() == 'Stmt_Expression'
            && $node->expr->getType() == 'Expr_Include';
    }

    private function isAssign(Node $node)
    {
        return $node->getType() == 'Expr_Assign';
    }
}