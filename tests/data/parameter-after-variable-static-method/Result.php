<?php

class Test
{
    function methodWithVarible()
    {
        $this->methodWithParameter(funcTest());
    }
    static function methodWithParameter($var)
    {
        someProcedure($var);
    }
}