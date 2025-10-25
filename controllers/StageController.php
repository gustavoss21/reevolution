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

    public function eventsWeekly()
    {
        return $this->respond($this->service->getEventsWeekly());
    }

    public function averangeTimeWithoutStudy(){
        return $this->respond($this->service->getAverangeTimeWithoutStudy());
    }

    public function moreTimeWithoutStudy(){
        return $this->respond($this->service->getMoreTimeWithoutStudy());
    }

    public function morePriorityEvents(){
        return $this->respond($this->service->getMorePriorityEvents());
    }
}
