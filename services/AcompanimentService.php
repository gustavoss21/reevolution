<?php

namespace Services;

use Models\ThemeModel;
use Models\StageModel;
use Models\TopicModel;
use Models\TagModel;
use Models\GenerateColumn;


class AcompanimentService
{
    use GenerateColumn;

    public $stage = '';
    private $tables = [
        'themes' => ThemeModel::class,
        'stages' => StageModel::class,
        'topics' => TopicModel::class,
        'tags' => TagModel::class
    ];
}