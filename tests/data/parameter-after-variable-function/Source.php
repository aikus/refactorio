<?php
function methodWithVarible()
{
    $var = funcTest();
    funcWithParameter($var);
}
function funcWithParameter($var)
{
    someProcedure($var);
}