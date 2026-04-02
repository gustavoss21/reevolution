<?php

namespace Controllers;

require dirname(__DIR__) . '/config/Response.php';

use Models\GenerateColumn;

class Controller 
{
    use \Config\Response;
    use GenerateColumn;


      // Common functionalities for all controllers can be added here
    protected function respond($data, $message='', $status = 200, $error = [])
    {
        http_response_code($status);
        $response = [
            'status'    => $status,
            'data'      => $data,
            'timestamp' => date('Y-m-d H:i:s'),
            'message'   => $message,
            'error'     => $error
        ];
        $this->json($response);
        exit;
    }

    protected function ResponseError($message, $errors = [], $status = 500)
    {
        $this->respond(null, $message, $status, $errors);
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
        $totalItems    = count($data);
        $totalPages    = ceil($totalItems / $limit);
        $paginatedData = $this->paginate($data, $page, $limit);

        $response = [
            'data'       => $paginatedData,
            'pagination' => [
                'current_page'   => $page,
                'total_pages'    => $totalPages,
                'total_items'    => $totalItems,
                'items_per_page' => $limit
            ]
        ];

        $this->respond($response);
    }

    protected function view()
    {
        include __DIR__ . '/../views/index.php';
    }

}