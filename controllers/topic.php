<?php

namespace Controllers;

require dirname(__DIR__) . '/models/thema.php';

use Models\Topic;

class TopicController
{
    private $TopicModel;

    public function __construct()
    {
        $this->TopicModel = new Topic();
    }

    public function createThema($data)
    {
        // Validate and sanitize input data
        if (empty($data['name']) || empty($data['description'])) {
            throw new \Exception("Name and description are required.");
        }

        // Set thema properties
        $this->TopicModel->set('name', htmlspecialchars($data['name']));
        $this->TopicModel->set('description', htmlspecialchars($data['description']));

        // Save thema to database
        return $this->TopicModel->insert();
    }

    public function getAllThemas()
    {
        return $this->TopicModel->all();
    }

    public function getThemaById($id)
    {
        $result = $this->TopicModel->find(['id' => $id]);
        if (empty($result)) {
            throw new \Exception("Thema not found.");
        }
        return $result[0];
    }

    public function updateThema($id, $data)
    {
        // Fetch existing thema
        $existingThema = $this->getThemaById($id);

        // Update properties if provided
        if (!empty($data['name'])) {
            $this->TopicModel->set('name', htmlspecialchars($data['name']));
        }
        if (!empty($data['description'])) {
            $this->TopicModel->set('description', htmlspecialchars($data['description']));
        }

        // Save updated thema to database
        $this->TopicModel->set('id', $id);
        return $this->TopicModel->update();
    }

    public function deleteThema($id)
    {
        // Ensure thema exists before deletion
        $this->getThemaById($id);

        // Delete thema from database
        $this->TopicModel->set('id', $id);
        return $this->TopicModel->delete();
    }
}
