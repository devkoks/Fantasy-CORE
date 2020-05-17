<?php
class core
{
	const CORE_VERSION = 2.1;

	const ERROR_MOD_NOT_LOADED = 1001;

	public $setting;
	public $modules = array();
	public $functions = array();
	public $tpl;

	protected $appRequire;

	public $application;

	public function __construct($FastSetting)
	{
		$this->setInfo($FastSetting);
		$this->initApplication();
		if($this->setting["load-modules"])
			$this->loadModules();

		if($this->setting["load-tpl"])
			$this->loadTpl();

		if($this->setting["start-app"])
			$this->application->init();
	}

	public function loadModule($name)
	{
		if(isset($this->modules[$name])) return true;
		include $this->setting["DOCUMENT_ROOT"]."/core/modules/".$name.".class.php";
		$module = '\\core\\module\\'.$moduleRequire;
		foreach($module::$requires["module"] as $modRequire)
			$this->loadModule($modRequire);
		foreach($module::$requires["functions"] as $functionRequire)
			$this->loadFunction($functionRequire);
	}

	public function getModule($name)
	{
		if(!isset($this->modules[$name]))
			throw new \Exception("Error: module not loaded", self::ERROR_MOD_NOT_LOADED);
		return $this->modules[$name];
	}

	public function loadFunction($name)
	{
		if(function_exists($name)) return true;
		include $this->setting["DOCUMENT_ROOT"]."/core/functions/".$name.".php";
		$this->functions[$name] = true;
	}

	private function setInfo($FastSetting)
	{
		header("Fantasy-Core: ".self::CORE_VERSION);
		include "conf.php";//Подключаем конфиг

		$setting = array_merge($setting,$setting["fast-conf"]);
		$this->setting = array_merge($setting,$FastSetting);
		if($this->setting['errors']['display']){
		    ini_set('display_errors',"On");
		}else{
		    ini_set('display_errors',"Off");
		}
		error_reporting($this->setting['errors']['level']);
		if($this->setting['DOCUMENT_ROOT']!=false)$_SERVER['DOCUMENT_ROOT'] = $this->setting['DOCUMENT_ROOT'];
		if($this->setting['timezone']!=false)date_default_timezone_set($this->setting['timezone']);//Ставим стандартную временую зону
	}

	private function initApplication()
	{
		include $this->setting['DOCUMENT_ROOT'].$this->setting['app']['dir'].$this->setting['app']['path'];//Запускаем приложение
		$this->application = new $this->setting['app']['name']($this);
		$this->appRequire = $this->application->getRequire();
	}

	private function loadModules()
	{
	    foreach($this->appRequire["module"] as $moduleRequire)
			$this->loadModule($moduleRequire);
	    foreach($this->appRequire["functions"] as $functionRequire)
	        $this->loadFunction($functionRequire);
		$appConf = $this->application->getAppConf();
		include $this->setting["DOCUMENT_ROOT"].$this->setting['app']['dir'].dirname($this->setting['app']['path']).'/init.php';
	}

	private function loadTpl()
	{
	    foreach($this->appRequire["tpl"] as $name){
			$fp = explode("/",$name);
			$dir = $this->setting["DOCUMENT_ROOT"].$this->setting["app-dir"].$this->setting["template"]."/";
			if(count($fp)>1){
				for($i=0;$i<count($fp)-1;$i++){
					$dir .= $fp[$i]."/";
				}
				$nameFile = $fp[count($fp)-1];
			}else{
				$nameFile = $fp[0];
			}
	        $this->tpl[$name] = $this->modules["tpl"]->open($dir,$nameFile);
	    }
	}

}
