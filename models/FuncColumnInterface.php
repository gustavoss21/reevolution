<?php

namespace Models;

interface FuncColumnInterface{
    function MAX($columns, $as = null);
    function COUNT($data, $as = null);
    function AVERANGE($columns, $as);
    function MORE($columns, $as = null);
    function GENERIC($column);
}