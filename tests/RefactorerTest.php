<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use RefactoringRobot\Refactorer;

class RefactorerTest extends TestCase
{
    /**
     * @dataProvider refactData
     */
    public function testRefact($directory)
    {
        $this->assertEquals(file_get_contents(__DIR__.
        "/data/$directory/Result.php"),
        (new Refactorer)->refact(file_get_contents(__DIR__.
        "/data/$directory/Source.php")));
    }

    public function refactData()
    {
        return [
            'psr-2 format' => ['non-refactoring'],
            'remove temporary variable (function)' => ['remove-temporary-variable'],
            'save object variable' => ['save-object-variable'],
            'save object variable (not new)' => ['save-class-variable'],
            'remove temporary variable (method)' => ['remove-method-assign'],
            'remove temporary variable (static method)' => ['remove-static-method-assign'],
            'remove temporary variable (constant)' => ['remove-variable-const'],
            'remove temporary variable (class constant)' => ['remove-variable-class-const'],
            'save parameter after temporary variable (method)' => ['parameter-after-variable'],
            'save parameter after temporary variable (static method)' => ['parameter-after-variable-static-method'],
            'save parameter after temporary variable (function)' => ['parameter-after-variable-function'],
        ];
    }
}