<?php
class core
{
	const CORE_VERSION = 2.0;

	public $setting;
	public $modules = array();
	public $loadedModules = array();
	public $loadedFunctions = array();
	public $tpl;

	protected $appRequire;

	public $application;

	public function __construct($FastSetting)
	{
		$this->setInfo($FastSetting);
		$this->initApplication();
		if($this->setting["load-modules"]){
			$this->loadModules();
		}
		if($this->setting["load-tpl"]){
			$this->loadTpl();
		}
		if($this->setting["start-app"]){
			$this->application->init();
		}
	}

	private function setInfo($FastSetting)
	{
		header("Fantasy-Core: ".self::CORE_VERSION);
		include "conf.php";//Подключаем конфиг

		$this->setting = array_merge($setting,$FastSetting);
		$this->setting['app-dir'] = $this->setting['app-dir'];
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
		include $this->setting['DOCUMENT_ROOT'].$this->setting['app-dir'].$this->setting['app'];//Запускаем приложение
		$this->application = new app($this);
		$this->appRequire = $this->application->getRequire();
	}

	private function loadModules()
	{
	    $modRequires = array();
		$this->appRequire["module"] = array_unique($this->appRequire["module"]);
	    foreach($this->appRequire["module"] as $moduleRequire){
	        include $this->setting["DOCUMENT_ROOT"]."/core/modules/".$moduleRequire.".class.php";
	        $module = '\\core\\module\\'.$moduleRequire;
	        foreach($module::require["module"] as $modRequire){
	        	$this->loadedModules[] = $modRequire;
	        }
	        foreach($module::require["functions"] as $funcRequire){
	        	$funcRequires[] = $funcRequire;
	        }
	        $this->loadedModules[] = $moduleRequire;
	    }
	    $this->loadedModules = array_unique($this->loadedModules);
	    unset($module);
	    foreach($this->appRequire["functions"] as $functionRequire){
	        include $this->setting["DOCUMENT_ROOT"]."/core/functions/".$functionRequire.".php";
	        $this->loadedFunctions[] = $functionRequire;
	    }
		$appConf = $this->application->getAppConf();
		include $this->setting["DOCUMENT_ROOT"].$this->setting["app-dir"].'/app/init.php';
	}

	private function loadTpl()
	{
		//var_dump($this->modules);
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
