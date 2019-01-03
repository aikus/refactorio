<?php

class Test
{
    function methodWithVarible()
    {
        $this->methodWithParameter(funcTest());
    }
    function methodWithParameter($var)
    {
        someProcedure($var);
    }
}