<?php

class Test
{
    function methodWithVarible()
    {
        $var = funcTest();
        $this->methodWithParameter($var);
    }
    function methodWithParameter($var)
    {
        someProcedure($var);
    }
}