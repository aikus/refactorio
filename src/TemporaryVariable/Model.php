<?php

namespace Refactorio\TemporaryVariable;

interface Model
{
    public function saveAllParameters() : bool;
    public function getSaveVariables() : array;
    public function getRemoveVariable() : string;
    public function getRemoveVariables() : array;
}