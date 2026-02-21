<?php

namespace Controllers;

use Controllers\Controller;

use Models\ThemeModel;
use Services\AcompanimentService;

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


    public function accompaniment()
    {
        $acompanimentService = new AcompanimentService();
        // $themes = $consultService->getAllThemesWithTopics();
        return $this->view('accompaniment', ['themes' => []]);
    }
}
