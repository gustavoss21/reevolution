<?php

namespace Controllers;

use Controllers\Controller;

class AppController extends Controller
{
    
    public function home($data)
    {
        return $this->view('home', ['message' => 'Welcome to the Theme Management System']);
    }
}
