<?php
$setting = array(
	'timezone' => 'Asia/Almaty',
	'DOCUMENT_ROOT' => dirname(dirname(__FILE__)),
	'logs' => array(
		'enable' => true,
		'dir' => '/logs/',
		'prefix' => 'log_'
	),
	'upload' => array(
		'dir'=> '/./uploads',
		'url'=>'/uploads',
		'access-files' => array(
			'image/jpeg',
			'image/png',
			'image/gif'
		)
	),
	'template' => '/tpl',
    'debug-view' => true,
	'cache' => true,
	'app' => '/app/index.php',
    'errors' => array(
        'display' => true,
        'level' => E_ALL
    ),
	'fast-conf' => array(
		'start-app' => false,
		'load-tpl' => false,
		'load-modules' => true,
		'app-dir'=> '/./'
	)
);
