<?php

class app
{
    private $module;

    public function init()
    {
        global $tpl;
        $coreConf = $this->getCoreConf();
        $appConf = $this->getAppConf();
        include $_SERVER['DOCUMENT_ROOT'].'/app/init.php';

        $this->initAppModule();
        $view = $this->module->view;
        if($this->isCoreModuleLoaded('tpl')){
            include 'tplEngine.php';
        }
        print $view;
    }

    public function isCoreModuleLoaded(string $module)
    {
        global $loadedModules;

        foreach($loadedModules as $loadedModule){
            if($loadedModule == $module){
                return true;
            }
        }
        return false;
    }

    public function isCoreFunctionLoaded(string $function)
    {
        global $loadedFunctions;
        foreach($loadedFunctions as $loadedFunction){
            if($loadedFunction = $function){
                return true;
            }
        }
        return false;
    }

    public function getRequire()
    {
        $module = $this->getModule();
        $this->includeAppModule();
        $require = $module::require;
        return $require;
    }

    protected function getAppConf()
    {
        $data = core\module\files::read($_SERVER['DOCUMENT_ROOT'].'/app/conf.json');
        $conf = json_decode($data,true);
        unset($data);
        return $conf;
    }
    protected function getCoreConf()
    {
        global $setting;
        return $setting;
    }
    protected function getURL()
    {
        return explode('/',$_SERVER['REQUEST_URI']);
    }
    protected function getModule()
    {
        $URL = $this->getURL();
        if($URL[1] == ''){
            $URL[1] = 'main';
        }
        return $URL[1];
    }
    protected function getTemplate()
    {
        global $template;
        return $template;
    }
    private function includeAppModule()
    {
        if(!is_object($this->module)){
            $module = $this->getModule();

            if(file_exists($_SERVER['DOCUMENT_ROOT'].'/app/modules/'.$module.'.php')){
                include $_SERVER['DOCUMENT_ROOT'].'/app/modules/'.$module.'.php';
            }else{
                include $_SERVER['DOCUMENT_ROOT'].'/app/modules/notfound.php';
                exit();
            }

        }
    }
    private function initAppModule()
    {
        $module = $this->getModule();
        $this->module = new $module();
        $this->module->init();
    }
}
