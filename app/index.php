<?php
class app
{
    private $module;
    private $mod;
    public $require = ["module"=>["files","tpl"]];

    public $core;
	public $coreConf;
	public $appConf;

    public function __construct($context)
    {
        $this->core = $context;
        $URL = $this->getURL();
        if(!isset($URL[1]))
            $URL[1] = "";
        if($URL[1] == ""){
            $URL[1] = 'main';
        }
        /*
         *TODO: fix!
         */
        $dmodule = $this->core->setting['DOCUMENT_ROOT'].$this->core->setting['app-dir']."/app/modules";
        $fmodule = "";
        $module  = "";
        unset($URL[0]);
        foreach($URL as $urls){
            $fmodule .= "/".$urls;
            if(file_exists($dmodule.$fmodule) or file_exists($dmodule.$fmodule.".php")){
                if(is_dir($dmodule.$fmodule)){
                    $module .= "/".$urls;
                }else{
                    $module .= ".php";
                }
            }
        }
        if(file_exists($dmodule.$fmodule.".php")){
            $fmodule .= ".php";
        }
       $this->setModule($URL[1]);
    }
    public function init()
    {
        $this->coreConf = $this->getCoreConf();
        $this->appConf = $this->getAppConf();
        $module = $this->getModule();
        $module = "app\\module\\".$module;
        $this->module = new $module();
		$this->module->init($this);
		$view = $this->module->view;
		if($this->isCoreModuleLoaded('tpl')){
			include 'tplEngine.php';
		}
        print $view;
    }

    public function isCoreModuleLoaded(String $module)
    {
        foreach($this->core->loadedModules as $loadedModule){
            if($loadedModule == $module){
                return true;
            }
        }
        return false;
    }

    public function isCoreFunctionLoaded(String $function)
    {
        foreach($this->core->loadedFunctions as $loadedFunction){
            if($loadedFunction = $function){
                return true;
            }
        }
        return false;
    }
    /* METHOD FOR CORE */
    public function getRequire()
    {
        $this->includeAppModule();
        $module = $this->getModule();
        $module = "app\\module\\".$module;
        $require = $module::require;
        $require = array_merge_recursive($require,$this->require);
        return $require;
    }
    /*--- METHOD FOR CORE ---*/
    public function getAppConf()
    {
        $data = \core\module\files::read($this->core->setting['DOCUMENT_ROOT'].$this->core->setting['app-dir'].'/app/conf.json');
        $conf = json_decode($data,true);
        unset($data);
        return $conf;
    }
    public function getCoreConf()
    {
        return $this->core->setting;
    }
    public function getURL()
    {
        if(!isset($_SERVER['REQUEST_URI']))
            $_SERVER['REQUEST_URI'] = "";

        return explode('/',$_SERVER['REQUEST_URI']);
    }
    protected function getModule()
    {
        return $this->mod;
    }
    protected function setModule($mod)
    {
        $this->mod = $mod;
    }
    public function getTemplate()
    {
        return $this->core->tpl;
    }
    private function includeAppModule()
    {
        if(!is_object($this->module)){
            $module = $this->getModule();
            if(file_exists($this->core->setting['DOCUMENT_ROOT'].$this->core->setting['app-dir'].'/app/modules/'.$module.'.php')){
                include $this->core->setting['DOCUMENT_ROOT'].$this->core->setting['app-dir'].'/app/modules/'.$module.'.php';
            }else{
                $this->setModule("notfound");
                include $this->core->setting['DOCUMENT_ROOT'].$this->core->setting['app-dir'].'/app/modules/notfound.php';
            }
        }
    }
}
