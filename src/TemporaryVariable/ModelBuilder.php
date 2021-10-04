<?php

declare (strict_types=1);
namespace Refactorio\TemporaryVariable;

use PhpParser\Node;

class ModelBuilder
{
    const MODELS = [
        'Expr_Include' => '\Refactorio\TemporaryVariable\Model\IncludeModel',
        'Expr_Assign' => '\Refactorio\TemporaryVariable\Model\Assign',
        'Expr_MethodCall' => '\Refactorio\TemporaryVariable\Model\MethodCall',
        'Expr_FuncCall' => '\Refactorio\TemporaryVariable\Model\FuncCall',
        'Expr_Closure' => '\Refactorio\TemporaryVariable\Model\Closure',
        'Expr_New' => '\Refactorio\TemporaryVariable\Model\CreateObject',
    ];

    public function get(Node $node) : Model
    {
        $class = $this->getModelClass($node);
        return new $class($node);
    }

    private function getModelClass(Node $node) : string
    {
        return key_exists($node->getType(), self::MODELS)
            ? self::MODELS[$node->getType()]
            : '\Refactorio\TemporaryVariable\Model\NoopModel';
    }
}