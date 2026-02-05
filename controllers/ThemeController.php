<?php

namespace Controllers;

use Models\ThemeModel;
use Controllers\Controller;
use Services\ConsultService;
use Services\ChangeData;

class ThemeController extends Controller
{
    private $themaModel, $service, $serviceGenerate;

    public function __construct()
    {
        $this->themaModel = new ThemeModel();
        $this->service = new ConsultService();
        $this->serviceGenerate = new ChangeData;
    }

    public function create($data)
    {   
        $data_formated = $this->serviceGenerate->formatValueKey($data);
        $creationResult = $this->serviceGenerate->createTheme($data_formated);
        return $this->respond($creationResult);   
    }

    public function createEvent($data)
    {
        $creationResult = $this->serviceGenerate->createEvent($data);
        return $this->respond($creationResult);
    }

    public function getAllThemas()
    {
        return $this->respond($this->themaModel->all());
    }

    public function getThemaById($id)
    {
        $result = $this->themaModel->find(['id' => $id]);
        if (empty($result)) {
            throw new \Exception("Thema not found.");
        }
        return $this->respond($result[0]);
    }

    public function updateThema($id, $data)
    {
        // Fetch existing thema
        $existingThema = $this->getThemaById($id);

        // Update properties if provided
        if (!empty($data['name'])) {
            $this->themaModel->set('name', htmlspecialchars($data['name']));
        }
        if (!empty($data['description'])) {
            $this->themaModel->set('description', htmlspecialchars($data['description']));
        }

        // Save updated thema to database
        $this->themaModel->set('id', $id);
        return $this->themaModel->update();
    }

    public function deleteThema($id)
    {
        // Ensure thema exists before deletion
        $this->getThemaById($id);

        // Delete thema from database
        $this->themaModel->set('id', $id);
        return $this->themaModel->delete();
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
        $statusAverage = (new ConsultService)->getMatchEvent($event,'themes');
        return $this->respond($statusAverage);
    }

    public function matchTopic($topic)
    {
        $statusAverage = (new ConsultService)->getMatchEvent($topic,'topics');
        return $this->respond($statusAverage);
    }

    public function relationTableToCreatetheme($table){
        $this->service->getColData($table);
    }

    public function formEvent(){
        $topic = $this->service->getForm('topics');
        $data['topic'] = $topic;        
        $data['stage'] = $this->service->getForm('stages',['topic_id']);
        $data['theme_id_child'] = $this->service->getForm('themes');
        $data['tag_id_child'] = $this->service->getForm('tags');
        $data['topic_id_child'] = $topic;

        return $this->respond($data);
    }
    public function form($form)
    {   

        $form_data = $this->service->getFormRecursive($form['form']);

        return $this->respond($form_data);
    }
}
