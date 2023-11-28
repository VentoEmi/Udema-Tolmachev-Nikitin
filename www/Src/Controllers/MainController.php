<?php

namespace Src\Controllers;
use MiladRahimi\PhpRouter\View\View;
use ORM;

class MainController
{
    public function mainPage(View $view)
    {
        return $view->make('udema.index');
    }
}