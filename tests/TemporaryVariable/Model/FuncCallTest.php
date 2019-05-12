<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Refactorio\TemporaryVariable\Model\FuncCall;
use \PhpParser\Node\Expr\FuncCall as PhpFuncCall;

/**
 * Description of FuncCallTest
 *
 * @author aikus
 */
class FuncCallTest extends TestCase
{
    /**
     * @expectedException \Exception
     */
    public function testGetLinkVariables()
    {
        new FuncCall(new PhpFuncCall(new PhpParser\Node\Name('array_pop'),
                [new \PhpParser\Node\Arg(new PhpFuncCall('test'))]));
    }
}
