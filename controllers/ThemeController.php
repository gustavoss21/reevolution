<?php

namespace Controllers;

use Models\ThemeModel;
use Controllers\Controller;
use Services\ServiceTimeline;

class ThemeController extends Controller
{
    private $themaModel;

    public function __construct()
    {
        $this->themaModel = new ThemeModel();
    }

    public function createThema($data)
    {
        // Validate and sanitize input data
        if (empty($data['name']) || empty($data['description'])) {
            throw new \Exception("Name and description are required.");
        }

        // Set thema properties
        $this->themaModel->set('name', htmlspecialchars($data['name']));
        $this->themaModel->set('description', htmlspecialchars($data['description']));

        // Save thema to database
        return $this->themaModel->insert();
    }

    public function getAllThemas()
    {
        return $this->themaModel->all();
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

    public function timeline(){
        $service['timeline'] = ServiceTimeline::timeline();
        $service['averange_status'] = ServiceTimeline::getAverageStatus();
        return $this->respond($service);
    }

    public function statusAverage(){
        return $this->respond(ServiceTimeline::getAverageStatus());
    }
}
