<?php

declare (strict_types=1);
namespace Refactorio\TemporaryVariable;

class CalculateValueException extends \Exception
{
    private $removeVariables = [];

    public function __construct(array $removeVariables)
    {
        $this->removeVariables = $removeVariables;
    }

    public function getRemoveVariables() : array
    {
        return $this->removeVariables;
    }
}