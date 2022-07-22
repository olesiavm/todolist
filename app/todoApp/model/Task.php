<?php
namespace App\Model;

use App\Db\Connection;

class Task
{
    public const PAGE_COUNT = 3;

    public function getTasks(\PDO $dbConnection)
    {
        $countInPage = Task::PAGE_COUNT;
        $page = intval($this->getPageId());

        $postsCount = $this->getFullCount($dbConnection);
        $pageCount = ceil($postsCount / $countInPage);

        if (isset($_GET['page'])) {
            if ($_GET['page'] > $pageCount) {
                $page = 1;
            }
        }

        $start = $page * $countInPage - $countInPage;

        // Выбираем количество задач $countInPage начиная с номера $start
        if (isset($_GET['sort'])) {
            if ($_GET['sort'] == 'ByName') {
                $arrTasks = $this->getTasksByName($dbConnection, $start, $countInPage, 'ASC');
            } else if ($_GET['sort'] == '-ByName') {
                $arrTasks = $this->getTasksByName($dbConnection, $start, $countInPage, 'DESC');
            } else if ($_GET['sort'] == 'ByEmail') {
                $arrTasks = $this->getTasksByEmail($dbConnection, $start, $countInPage, 'ASC');
            } else if ($_GET['sort'] == '-ByEmail') {
                $arrTasks = $this->getTasksByEmail($dbConnection, $start, $countInPage, 'DESC');
            } else if ($_GET['sort'] == 'ByStatus') {
                $arrTasks = $this->getTasksByStatus($dbConnection, $start, $countInPage, 'ASC');
            } else if ($_GET['sort'] == '-ByStatus') {
                $arrTasks = $this->getTasksByStatus($dbConnection, $start, $countInPage, 'DESC');
            } else {
                $arrTasks = $this->showWithLimit($dbConnection, $start, $countInPage);
            }
        } else {
            $arrTasks = $this->showWithLimit($dbConnection, $start, $countInPage);
        }

        return [
            'tasks' => $arrTasks,
            'count' => $postsCount,
            'pageCount' => $pageCount,
            'page' => $page
        ];

    }

    public function getTask(\PDO $dbConnection, int $id)
    {
        $sql = "SELECT * FROM tasks WHERE id = :id";
        $prep = $dbConnection->prepare($sql);
        $prep->bindValue(':id', $id, \PDO::PARAM_INT);
        $prep->execute();
        return $prep->fetch(\PDO::FETCH_ASSOC);
    }


    public function createTask(\PDO $dbConnection)
    {
        if (isset($_POST['createTask']) && !empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['description'])) {
            $sql = "INSERT INTO tasks (name, email, description, status) VALUES (:name, :email, :description, 0)";
            $prep = $dbConnection->prepare($sql);
            $name = strip_tags($_POST['name']);
            $description = strip_tags($_POST['description']);
            $prep->bindValue(':name', $name, \PDO::PARAM_STR);
            $prep->bindValue(':email', $_POST['email'], \PDO::PARAM_STR);
            $prep->bindValue(':description', $description, \PDO::PARAM_STR);
            $prep->execute();
            return ['message' => 'Задача создана'];
        }
        if (isset($_POST['createTask']) && (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['description']))) {
            return ['message' => 'Заполните все поля'];
        }
        return ['message' => ''];
    }


    public function updateTask(\PDO $dbConnection)
    {
        $taskId = $this->getId();
        $task = $this->getTask($dbConnection, $taskId);
        if (isset($_POST['editTask']) && !empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['description']) && !empty($_POST['id'])) {
            $sql = "UPDATE tasks SET name = :name, email = :email, description = :description, status = :status WHERE id = :id";
            $prep = $dbConnection->prepare($sql);
            $prep->bindValue(':name', $_POST['name'], \PDO::PARAM_STR);
            $prep->bindValue(':email', $_POST['email'], \PDO::PARAM_STR);
            $prep->bindValue(':description', $_POST['description'], \PDO::PARAM_STR);
            $prep->bindValue(':status', $_POST['status'], \PDO::PARAM_INT);
            $prep->bindValue(':id', $_POST['id'], \PDO::PARAM_INT);
            $prep->execute();
            $task = $this->getTask($dbConnection, $taskId);
            return ['task' => $task, 'message' => 'Задача обновлена'];
        }
        if (isset($_POST['editTask']) && (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['description']))) {
            return ['task' => $task, 'message' => 'Заполните все поля'];
        }
        return ['task' => $task, 'message' => ''];
    }


    public function updateTaskStatus(\PDO $dbConnection)
    {
        $taskId = $this->getId();
        $task = $this->getTask($dbConnection, $taskId);
        $status = $task['status'];
        if ($status == 0) {
            $status = 1;
        } else {
            $status = 0;
        }

        $sql = "UPDATE tasks SET status = :status WHERE id = :id";
        $prep = $dbConnection->prepare($sql);
        $prep->bindValue(':status', $status, \PDO::PARAM_INT);
        $prep->bindValue(':id', $taskId, \PDO::PARAM_INT);
        return $prep->execute();
    }


    public function getFullCount(\PDO $dbConnection)
    {
        $sth = $dbConnection->query('SELECT COUNT(*) FROM tasks');
        return $sth->fetchColumn();
    }

    public function getTasksByName(\PDO $dbConnection, int $start, int $countInPage, string $order)
    {
        $sql = "SELECT * FROM tasks ORDER BY name $order LIMIT :countInPage OFFSET :start";
        $prep = $dbConnection->prepare($sql);
        $prep->bindValue(':countInPage', $countInPage, \PDO::PARAM_INT);
        $prep->bindValue(':start', $start, \PDO::PARAM_INT);
        $prep->execute();
        return $prep->fetchAll(\PDO::FETCH_ASSOC);
    }


    public function getTasksByEmail(\PDO $dbConnection, int $start, int $countInPage, string $order)
    {
        $sql = "SELECT * FROM tasks ORDER BY email $order LIMIT :countInPage OFFSET :start";
        $prep = $dbConnection->prepare($sql);
        $prep->bindValue(':countInPage', $countInPage, \PDO::PARAM_INT);
        $prep->bindValue(':start', $start, \PDO::PARAM_INT);
        $prep->execute();
        return $prep->fetchAll(\PDO::FETCH_ASSOC);
    }


    public function getTasksByStatus(\PDO $dbConnection, int $start, int $countInPage, string $order)
    {
        $sql = "SELECT * FROM tasks ORDER BY status $order LIMIT :countInPage OFFSET :start";
        $prep = $dbConnection->prepare($sql);
        $prep->bindValue(':countInPage', $countInPage, \PDO::PARAM_INT);
        $prep->bindValue(':start', $start, \PDO::PARAM_INT);
        $prep->execute();
        return $prep->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function showWithLimit(\PDO $dbConnection, int $start, int $countInPage)
    {
        $sql = "SELECT * FROM tasks LIMIT :countInPage OFFSET :start";
        $prep = $dbConnection->prepare($sql);
        $prep->bindValue(':countInPage', $countInPage, \PDO::PARAM_INT);
        $prep->bindValue(':start', $start, \PDO::PARAM_INT);
        $prep->execute();
        return $prep->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     *  Check existence of page number from url
     *
     * @param Connection
     * @return void
     * @throws \Exception
     */
    public function checkPageNumber()
    {
        $pageNum = $this->getPageId();
        if ($pageNum == null) {
            throw new \Exception("Page number does not exist");

        }
    }

    /**
     *  Get number of page
     *
     * @return mixed
     */
    public function getPageId()
    {
        $id = 1;
        if (isset($_GET['page']) && !empty($_GET['page'])) {
            $id = intval($_GET['page']);
            if (gettype($id) !== "integer") {
                $id = 1;
            }
        }
        return $id;
    }

    /**
     *  Check existence of id from url and existence of user with this id in database
     *
     * @param Connection
     * @return void
     * @throws \Exception
     */
    public function checkTaskById(\PDO $dbConnection)
    {
        $taskId = $this->getId();
        $task = $this->getTask($dbConnection, $taskId);
        if ($taskId == null || $task == null) {
            throw new \Exception("Task does not exist");
        }
        return $taskId;
    }


    /**
     *  Get id
     *
     * @return mixed
     * @throws \Exception
     */
    public function getId()
    {
        $routes = explode('/', $_SERVER['REQUEST_URI']);
        if (empty($routes[2])) {
            throw new \Exception("Task not exist");
        }
        $id = intval($routes[2]);
        if (gettype($id) !== "integer") {
            throw new \Exception("Task not exist");
        }
        return $id;
    }
}




