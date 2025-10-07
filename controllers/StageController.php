<?php

namespace Controllers;

use Models\StageModel;
use Models\TopicModel;
use Controllers\Controller;

class StageController extends Controller
{
    private $stage;

    public function __construct()
    {
        $this->stage = new StageModel();
    }

    public function GetTimeWithoutStudy(){
        $this->stage->columns = ['updated_at'];
        return $this->respond($this->stage->find([],['max'=>'updated_at']));
    }

}
