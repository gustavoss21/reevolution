<?php

namespace Controllers;

use Models\StageModel;
use Models\TopicModel;
use Controllers\Controller;
use Models\ColumnEnum as Col;
use Services\ConsultService;

class StageController extends Controller
{
    private $stage, $service;

    public function __construct()
    {
        $this->stage = new StageModel();
        $this->service = new ConsultService();
    }

    public function timeWithoutStudy()
    {

        return $this->respond($this->service->getTimeWithoutStudy());
    }

    public function eventRecommendation()
    {
        return $this->respond($this->service->getEventRecommendation());
    }
}
