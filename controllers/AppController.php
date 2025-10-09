<?php

namespace Controllers;

use Controllers\Controller;

use Models\ThemeModel;
use Services\ServiceTimeline;

class AppController extends Controller
{
    
    public function home()
    {
        return $this->view('home');
    }

    public function themeItem()
    {
        return $this->view('theme_item');
    }
}
