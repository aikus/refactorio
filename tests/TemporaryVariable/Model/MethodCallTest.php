<?php


namespace TemporaryVariable\Model;


use PhpParser\Node\Expr\MethodCall as PhpMethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PHPUnit\Framework\TestCase;
use Refactorio\TemporaryVariable\Model\MethodCall;

class MethodCallTest extends TestCase
{
    /**
     * @test
     * @dataProvider getSaveVariablesCases
     * @param array $expected
     * @param PhpMethodCall $node
     */
    public function getSaveVariables(array $expected, PhpMethodCall $node)
    {
        $model = new MethodCall($node);

        $actual = $model->getSaveVariables();

        $this->assertEquals($expected, $actual);
    }

    public function getSaveVariablesCases(): array
    {
        return [
            'object in variable - save variable' => [
                ['testVariable'],
                new PhpMethodCall(new Variable("testVariable"), "testMethod"),
            ],
            'object in property in variable - save' => [
                ['testVariable'],
                new PhpMethodCall(new PropertyFetch(new Variable("testVariable"), new Identifier("testProperty")), "testMethod"),
            ]
        ];
    }
}