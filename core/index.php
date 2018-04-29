<?php
/*Версия ядра*/
$core = array(
	'version' => '1.6',
	'pack' => 'conf,includes,tpl'
);
/*--Версия ядра--*/
header("Koks-Core: ".$core['version']);
include 'conf.php';//Подключаем конфиг

if(!isset($CoreConf)){
	$FastSetting = $setting['fast-conf'];
}else{
	$FastSetting = $CoreConf;
}

if($setting['errors']['display']){
    ini_set('display_errors',"On");
}else{
    ini_set('display_errors',"Off");
}
error_reporting($setting['errors']['level']);

if($setting['DOCUMENT_ROOT']!=false)$_SERVER['DOCUMENT_ROOT'] = $setting['DOCUMENT_ROOT'];

if($setting['timezone']!=false)date_default_timezone_set($setting['timezone']);//Ставим стандартную временую зону

include $_SERVER['DOCUMENT_ROOT'].'/core/includes.php';//Подключаем файл поключаемых файлов :D

include $_SERVER['DOCUMENT_ROOT'].$setting['app'];//Запускаем приложение
$app = new app();
$appRequire = $app->getRequire();

/*Подключаем модули/функции*/
if($FastSetting['load-modules']){
    $loadedModules = array();
    foreach($appRequire['module'] as $moduleRequire){
        include $_SERVER['DOCUMENT_ROOT']."/core/modules/".$moduleRequire.".class.php";
        $loadedModules[] = $moduleRequire;
    }
    
    $loadedFunctions = array();
    foreach($appRequire['functions'] as $functionRequire){
        include $_SERVER['DOCUMENT_ROOT']."/core/functions/".$functionRequire.".php";
        $loadedFunctions[] = $functionRequire;
    }
}
/*--Подключаем модули/функции--*/

/*Константы*/
if(isset($constant)){
	foreach($constant as $name => $value){
		define($name,$value);
	}
	unset($constant);
}
/*--Константы--*/

if($FastSetting['load-tpl'] and $app->isCoreModuleLoaded('tpl')){
    $tpl = new core\module\tpl();//Создаём объект tpl
    $template = array();
    foreach($appRequire['tpl'] as $name){
        $template[$name] = $tpl->open($_SERVER['DOCUMENT_ROOT'].$setting['template'],$name);
    }
    unset($name);
}


if($FastSetting['start-app']){
    $app->init();
}