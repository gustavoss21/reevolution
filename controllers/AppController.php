<?php

namespace Controllers;

use Controllers\Controller;

use Models\ThemeModel;
use Services\ServiceTimeline;

class AppController extends Controller
{
    
    public function home($data)
    {
        return $this->view('home');
    }

    public function themeItem($data)
    {
        $tC = (new ThemeModel($data))->find($data);
        $theme = ServiceTimeline::getThemefullData($tC[0]);
        return $this->view('theme_item', ['theme'=> $theme]);
    }
}
