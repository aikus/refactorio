<?php


namespace TemporaryVariable\Model;


use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Expr\Assign as PhpAssign;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PHPUnit\Framework\TestCase;
use Refactorio\TemporaryVariable\Model\Assign;

class AssignTest extends TestCase
{

    /**
     * @test
     * @dataProvider getSaveVariablesCases
     * @param array $expected
     * @param PhpAssign $node
     */
    public function getSaveVariables(array $expected, PhpAssign $node)
    {
        $model = new Assign($node);

        $saveVariables = $model->getSaveVariables();

        $this->assertEquals($expected, $saveVariables);
    }

    public function getSaveVariablesCases(): array
    {
        $const = new ConstFetch(new Name("test"));
        return [
            'Array element assign - add variable for save' => [
                ["testVar"],
                new PhpAssign(new ArrayDimFetch(new Variable("testVar")), $const),
            ],
            'Variable assign - not save' => [
                [], new PhpAssign(new Variable("testVariable"), $const)
            ],
            'Assign property array - not save' => [
                [],
                new PhpAssign(
                    new ArrayDimFetch(new PropertyFetch(new Variable("this"), new Identifier("prop"))),
                    $const),
            ],
            'Assign multy array - save variable' => [
                ["testVar"],
                new PhpAssign(new ArrayDimFetch(new ArrayDimFetch(new Variable("testVar"))), $const),
            ]
        ];
    }
}