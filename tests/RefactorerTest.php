<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Refactorio\Refactorer;

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
            'format' => ['non-refactoring'],
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
            'save array variable' => ['save-array-variable'],
            'save array variable (from function)' => ['save-array-variable-from-function'],
            'save association array variable' => ['save-assoc-array-variable'],
            'save variable before include' => ['save-variable-before-include'],
            'save variable before require' => ['save-variable-before-require'],
            'save variable before include_once' => ['save-variable-before-include-once'],
            'save variable before compact' => ['save-variable-before-compact'],
            'save variable before compact (array input)' => ['save-variable-before-compact-input-array'],
            'save variables before calculate compact (variable)' => ['save-variables-before-calculate-compact'],
            'save variables before calculate compact (function)' => ['save-variables-before-calculate-compact-function'],
            'save variables before calculate compact (method)' => ['save-variable-before-compact-method'],
            'save variable before use as link (asort)' => ['save-variable-use-as-link-asort'],
            'save variable before calculate compact (save array)' => ['save-variables-before-comapare-array'],
            'save variable using for closure' => ['save-closure-variables'],
            'closure is function!' => ['closure-is-function'],
            'multy closure' => ['multy-closure'],
            'closure in closure' => ['closure-in-closure'],
        ];
    }
}