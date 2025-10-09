<?php

namespace Models;

interface FuncColumnInterface{
    function formateColumnMax($columns, $as = null);
    function formateColumnCount($data, $as = null);
    function formateAverangeColumn($columns, $as);
    function formateColumnsMore($columns, $as = null);
    function fomatedColumnsGeneric($column);
}