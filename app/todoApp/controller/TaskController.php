<?php
namespace App\Controller;

use App\Application;
use App\Model\Task;
use App\Model\User;

class TaskController extends Controller
{
    /**
     * @var object
     */
    public $model;

    /**
     * @param Application
     * @return void
     * @throws \Exception
     */
    public function __construct($application)
    {
        parent::__construct($application);
        $this->model = new Task();
    }

    /**
     * Show tasks
     *
     * @return void
     * @throws \Exception
     */
    public function showTasksAction()
    {
        $dbConnection = $this->container->get('dbConnection');
        try {
            $this->model->checkPageNumber();
            $list = $this->model->getTasks($dbConnection);
        } catch (\Exception $e) {
            $this->render('error', [
                'error' => $e->getMessage()
            ]);
        }

        $this->render('task:showTasks', [
            'tasks' => $list['tasks'],
            'pageCount' => $list['pageCount'],
            'page' => $list['page']
        ]);
    }

    /**
     * Edit status of task
     *
     * @return void
     */
    public function editStatusOfTaskAction()
    {
        try {
            if ((int)$_SESSION['roleId'] !== User::ROLE_ADMIN) {
                throw new \Exception("You do not have access");
            }
            $dbConnection = $this->container->get('dbConnection');
            $this->model->checkTaskById($dbConnection);
            $this->model->updateTaskStatus($dbConnection);
            header("Location: /show-tasks/");
        } catch (\Exception $e) {
            $this->render('error', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Edit task
     *
     * @return void
     */
    public function editTaskAction()
    {
        $dbConnection = $this->container->get('dbConnection');
        try {
            if ((int)$_SESSION['roleId'] !== User::ROLE_ADMIN) {
                throw new \Exception("You do not have access");
            }
            $this->model->checkTaskById($dbConnection);
            $arr = $this->model->updateTask($dbConnection);
            $task = $arr['task'];
            $message = $arr['message'];
        } catch (\Exception $e) {
            $this->render('error', [
                'error' => $e->getMessage()
            ]);
        }

        $this->render('task:editTask', [
            'task' => $task,
            'message' => $message
        ]);
    }

    /**
     * Create task
     *
     * @return void
     */
    public function createTaskAction()
    {
        $dbConnection = $this->container->get('dbConnection');
        $data = $this->model->createTask($dbConnection);

        $this->render('task:createTask', [
            'message' => $data['message']
        ]);
    }
}


		