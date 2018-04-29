<?php
$setting = array(
	'timezone' => 'Asia/Almaty',
	'DOCUMENT_ROOT' => dirname(dirname(__FILE__)),
	'logs' => array(
		'enable' => true,
		'dir' => '/logs/',
		'prefix' => 'log_'
	),
	'template' => '/tpl/default',
    'debug-view' => true,
	'cache' => true,
	'app' => '/app/index.php',
    'errors' => array(
        'display' => true,
        'level' => E_ALL
    ),
	'fast-conf' => array(
		'start-app' => false,
		'load-tpl' => true,
		'load-modules' => true
	)
);
