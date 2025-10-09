<?php

namespace Controllers;

use Models\StageModel;
use Models\TopicModel;
use Controllers\Controller;
use Models\ColumnEnum as Col;

class StageController extends Controller
{
    private $stage;

    public function __construct()
    {
        $this->stage = new StageModel();
    }

    public function GetTimeWithoutStudy(){
        $event_without = $this->stage->columns(
            self::colf('updated_at','max'),
            self::col('status'),
            )
            ->find();
        return $this->respond($event_without);
    }

}
