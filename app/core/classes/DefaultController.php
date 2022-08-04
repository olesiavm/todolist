<?php

namespace App\Controller;

class DefaultController extends Controller
{
    public function notFoundAction($request)
    {
        $this->render('404');
    }
}
