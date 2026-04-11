<?php

namespace Controllers;

use Models\TopicModel;
use Controllers\Controller;
use Services\ConsultService;
use Services\ChangeData;

class TopicController extends Controller
{
    private $topic, $service, $serviceGenerate;

    public function __construct()
    {
        $this->topic           = new TopicModel();
        $this->service         = new ConsultService();
        $this->serviceGenerate = new ChangeData;
    }

    public function create($data)
    {
        $data_formated = $this->serviceGenerate->formatValueKey($data);
        return $this->respond($this->serviceGenerate->createEvent($data_formated));
    }



    public function getAll()
    {
        return $this->respond($this->topic->all());
    }

    public function getTopicforTheme($theme)
    {
        $table    = $this->topic->table;
        $theme_id = $theme['id'];

        $newServiceForTheme = new ConsultService('Models\TopicModel');
        $result             = $newServiceForTheme->searchForOther('theme', $theme_id)->find();

        if (empty($result)) {
            return $this->respond('data not found', 404);
        }
        return $this->respond($result);
    }

    public function get($id)
    {
        $result = $this->topic->find(['id' => $id]);
        if (empty($result)) {
            return $this->respond('data not found', 404);
        }
        return $this->respond($result[0]);
    }

    public function updateThema($id, $data)
    {
        // Fetch existing thema
        $existingThema = $this->get($id);

        // Update properties if provided
        if (!empty($data['name'])) {
            $this->topic->set('name', htmlspecialchars($data['name']));
        }
        if (!empty($data['description'])) {
            $this->topic->set('description', htmlspecialchars($data['description']));
        }

        // Save updated thema to database
        $this->topic->set('id', $id);
        return $this->topic->update();
    }

    public function deleteThema($id)
    {
        // Ensure thema exists before deletion
        $this->get($id);

        // Delete thema from database
        $this->topic->set('id', $id);
        return $this->topic->delete();
    }

    public function timeline()
    {
        return $this->respond((new ConsultService)->timeline());
    }

    public function statusAverage()
    {
        $statusAverage = (new ConsultService)->getAverageStatus();
        return $this->respond($statusAverage);
    }

    public function matchEvent($event)
    {
        $statusAverage = (new ConsultService)->getMatchEvent($event, 'topics');
        return $this->respond($statusAverage);
    }

    public function matchTopic($topic)
    {
        $statusAverage = (new ConsultService)->getMatchEvent($topic, 'topics');
        return $this->respond($statusAverage);
    }

    public function relationTableToCreatetopic($table)
    {
        $this->service->getColData($table);
    }

    public function form($form)
    {

        $form_data = $this->service->getFormRecursive($form['form']);

        return $this->respond($form_data);
    }
}
