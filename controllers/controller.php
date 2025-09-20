<?php

namespace Controllers;

require dirname(__DIR__) . '/config/response.php';

class Controller 
{
    use \Config\Response;

    // Common functionalities for all controllers can be added here
    protected function respond($data, $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function handleError($message, $status = 400)
    {
        $this->respond(['error' => $message], $status);
    }

    protected function getJsonInput()
    {
        $input = file_get_contents('php://input');
        return json_decode($input, true);
    }

    protected function validateInput($data, $requiredFields)
    {
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                throw new \Exception("Field '$field' is required.");
            }
        }
    }

    protected function sanitizeInput($data)
    {
        $sanitized = [];
        foreach ($data as $key => $value) {
            $sanitized[$key] = htmlspecialchars(strip_tags($value));
        }
        return $sanitized;
    }

    protected function logAction($action, $details = [])
    {
        // Implement logging logic here (e.g., write to a file or database)
        // Example: error_log("Action: $action, Details: " . json_encode($details));
    }

    protected function authorize($userRole, $requiredRole)
    {
        if ($userRole !== $requiredRole) {
            throw new \Exception("Unauthorized access.", 403);
        }
    }

    protected function paginate($data, $page = 1, $limit = 10)
    {
        $offset = ($page - 1) * $limit;
        return array_slice($data, $offset, $limit);
    }

    protected function filterData($data, $criteria)
    {
        return array_filter($data, function ($item) use ($criteria) {
            foreach ($criteria as $key => $value) {
                if (!isset($item[$key]) || $item[$key] != $value) {
                    return false;
                }
            }
            return true;
        });
    }
    
    protected function respondWithPagination($data, $page, $limit)
    {
        $totalItems = count($data);
        $totalPages = ceil($totalItems / $limit);
        $paginatedData = $this->paginate($data, $page, $limit);

        $response = [
            'data' => $paginatedData,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_items' => $totalItems,
                'items_per_page' => $limit
            ]
        ];

        $this->respond($response);
    }

    protected function handleRequest($method, $handlers)
    {
        if (isset($handlers[$method]) && is_callable($handlers[$method])) {
            try {
                $handlers[$method]();
            } catch (\Exception $e) {
                $this->handleError($e->getMessage(), $e->getCode() ?: 400);
            }
        } else {
            $this->handleError("Method not allowed", 405);
        }
    }


}