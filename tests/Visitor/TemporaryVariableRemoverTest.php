<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node;
use RefactoringRobot\Visitor\TemporaryVariableRemover;
use PhpParser\NodeTraverser;

class TemporaryVariableRemoverTest extends TestCase
{
    /**
     * @dataProvider enterNodeData
     */
    public function testEnterNode(Node $node, $expected)
    {
        $visitor = new TemporaryVariableRemover;
        $this->assertEquals($expected, $visitor->enterNode($node));
    }

    public function enterNodeData()
    {
        $assign = new Assign(new Variable('a'), new FuncCall('b'));
        $expression = new Expression($assign);
        return [
            'not expresion -> null' => [$assign, null],
            'current expresion -> dont traverse children' =>
            [$expression, NodeTraverser::DONT_TRAVERSE_CHILDREN],
        ];
    }

    /**
     * @dataProvider leaveNodeData
     */
    public function testLeaveNode(Node $node, $expected)
    {
        $visitor = new TemporaryVariableRemover;
        $this->assertEquals($expected, $visitor->leaveNode($node));
    }

    public function leaveNodeData()
    {
        $assign = new Assign(new Variable('a'), new FuncCall('b'));
        $expression = new Expression($assign);
        return [
            'not expresion -> null' => [$assign, null],
            'current expresion -> remove node' =>
            [$expression, NodeTraverser::REMOVE_NODE],
        ];
    }

    public function testReplaceTemporaryVariable()
    {
        $visitor = new TemporaryVariableRemover;
        $func = new FuncCall('b');
        $variable = new Variable('a');
        $assign = new Assign($variable, $func);
        $expression = new Expression($assign);
        $visitor->leaveNode($expression);
        $this->assertEquals($func, $visitor->leaveNode($variable));
    }
}