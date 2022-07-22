<?php
namespace App\Model;

class User {

    public const ROLE_ADMIN = 1;

    public function getByLogin($dbConnection)
    {
        $sql = "SELECT * FROM users WHERE login = :login";
        $prep = $dbConnection->prepare($sql);
        $prep->bindParam(':login', $_POST['login'], \PDO::PARAM_STR);
        $prep->execute();
        return $prep->fetch(\PDO::FETCH_ASSOC);
    }
}