<?php

namespace Models;

enum ColumnEnum: int{
    case MAX = 0;
    case MORE = 1;
    case COUNT = 2;
    case AVERANGE = 3;
}

function test(ColumnEnum $v){
    print_r($v->value);
}

test(ColumnEnum::AVERANGE);