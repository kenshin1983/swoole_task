<?php 
define('DEBUG', 'on');
define('ROOT_PATH', realpath(__DIR__ . '/../'));
define("TASK_PATH", ROOT_PATH . '/tasks/');

require ROOT_PATH . '/vendor/autoload.php';
$config = require __DIR__.'/config.php';

$server = new \Sts\Server($config);
$app = new \Sts\App();
$server->setApp($app);
$server->run();
