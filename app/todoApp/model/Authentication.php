<?php
namespace App\Model;

use App\Db\Connection;

class Authentication
{
    /**
     * Authentication of user
     *
     * @param Connection
     * @return void
     * @throws \Exception
     */
    public function auth($dbConnection)
    {
        try {
            if (empty($_POST['password']) || empty($_POST['login'])) {
                throw new \Exception('Запоните все поля!');
            }

            $resultLogin = HelperCheckForm::checkField($_POST['login']);
            $resultPassw = HelperCheckForm::checkField($_POST['password']);

            if (isset($resultLogin)) {
                throw new \Exception($resultLogin);
            }
            if (isset($resultPassw)) {
                throw new \Exception($resultPassw);
            }

            $user = new User();
            $rowUser = $user->getByLogin($dbConnection);

            if (!isset($rowUser['login'])) {
                throw new \Exception('Пользователь с таким логином не зарегистрирован!');
            }

            if ($rowUser['password'] !== $_POST['password']) {
                throw new \Exception('Не верно введен логин или пароль!');
            }

            // Write to the session information about authentication
            $_SESSION['auth'] = true;
            $_SESSION['userId'] = $rowUser['id'];
            $_SESSION['login'] = $rowUser['login'];
            $_SESSION['roleId'] = $rowUser['role_id'];

            $login = $rowUser['login'];
            $result = array(
                'status' => true,
                'msg' => "Hello, $login"
            );
        } catch (\Exception $ex) {
            $result = array(
                'status' => false,
                'error' => $ex->getMessage()
            );
        }
        echo json_encode($result);
    }


    /**
     * Exit from user profile
     * @param Connection
     * @return void
     */
    public function logout($dbConnection)
    {
        if (isset($_SESSION['auth'])) {
            // destroy of the session
            session_destroy();
        }
        header("Location: /");
    }
}


