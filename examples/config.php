<?php 
return array(
	'server'  => array(
		'name' => 'swoole_task',
		'host' => '0.0.0.0',
		'port' => '8888',
		'ssl'  => 0,
		'user' => 'apache'
	),
	'setting' => array(
		'worker_num'                 => 2,
		//'open_eof_check'           => true,
		//'package_eof'              => "\r\n",
		'task_worker_num'            => 1,
		'task_ipc_mode'              => 2,
		//'dispatch_mode'            => 2,
		'daemonize'                  => 0,
		'log_file'                   => '/tmp/swoole.log',
		//'heartbeat_idle_time'      => 5,
		//'heartbeat_check_interval' => 5,
	)
);