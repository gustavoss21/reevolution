<?php

namespace Models;

use FFI\ParserException;
use Models\FuncColumnInterface;

trait GenerateColumn
{
    public const MAX = 'max';
    public const COUNT = 'count';
    public const AVERANGE = 'averange';
    public const MORE = 'more';
    public const GENERIC = 'generic';
    private $functions;

    public function __construct() {
        $this->functions = get_class_methods(FuncColumnInterface::class);
    }

    private function formateOptions($func_name)
    {
        $func_name_formated = ucfirst($func_name);

        foreach ($this->functions as $func) {
            if (str_contains($func, $func_name_formated)) {
                return $func;
            }
        }
    }

    public function col($column)
    {
        $fun = $this->formateOptions(self::GENERIC);

        return ['column'=>$column,'fun'=>$fun,'as'=>''];
    }

    public function colf($column, $fun = '', $as = '')
    {
        $fun = $this->formateOptions($fun);

        return ['column' => $column, 'fun' => $fun, 'as' => $as];
    }
}