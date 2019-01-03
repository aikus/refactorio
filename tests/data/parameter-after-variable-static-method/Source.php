<?php

class Test
{
    function methodWithVarible()
    {
        $var = funcTest();
        $this->methodWithParameter($var);
    }
    static function methodWithParameter($var)
    {
        someProcedure($var);
    }
}