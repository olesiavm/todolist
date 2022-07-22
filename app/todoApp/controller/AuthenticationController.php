<?php
namespace App\Controller;

use App\Model\Authentication;

class AuthenticationController extends Controller
{
    public $model;

    public function __construct($application)
    {
        parent::__construct($application);
        $this->model = new Authentication();
    }


    /**
     * Authentication of user
     */
    public function loginAction()
    {
        if (isset($_SESSION['auth'])) {
            $this->render('index:index');
        }
        $dbConnection = $this->container->get('dbConnection');

        if (isset($_POST['login']) || isset($_POST['password'])) {
          $this->model->auth($dbConnection);
        } else {
            $this->render('authentication:login');
        }
    }


    /**
     * Exit from user profile
     */
    public function logoutAction()
    {
        $dbConnection = $this->container->get('dbConnection');
        $this->model->logout($dbConnection);
    }
}