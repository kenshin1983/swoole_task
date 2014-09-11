<?php 
define('DEBUG', 'on');
define('ROOT_PAHT', realpath(__DIR__ . '/../'));
define("TASK_PATH", ROOT_PAHT . '/tasks/');

require ROOT_PAHT . '/vendor/autoload.php';

// $config = require __DIR__.'/config.php';

$server = new \Sts\Server();
exit;
$webim->loadSetting(__DIR__."/swoole.ini"); //加载配置文件
$webim->setLogger(new Swoole\Log\FileLog($config['webim']['log_file']));   //Logger

/**
 * 使用文件或redis存储聊天信息
 */
$webim->setStore(new WebIM\Store\File($config['webim']['data_dir']));

/**
 * webim必须使用swoole扩展
 */
$server = new Swoole\Network\Server($config['server']['host'], $config['server']['port']);
$server->setProtocol($webim);
$server->run($config['swoole']);
