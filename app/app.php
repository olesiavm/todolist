<?php
use App\Application;
use App\Db\Connection;

require_once __DIR__ . '/bootstrap.php';

$pathToConfig = __DIR__ . '/config/config.php';

$application = new Application($baseApplicationDir);
$application->build($pathToConfig);

$container = $application->getContainer();
$config = $container->get('config');

$connection = new Connection($config->get('db'));
$dbConnection = $connection->createDbConnection();

$container->set('dbConnection', $dbConnection);

return $application;
