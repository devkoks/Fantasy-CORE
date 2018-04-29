<?php
if($this->application->isCoreModuleLoaded('MySQL')){
    $this->modules["MySQL"] = new core\module\MySQL(
        $appConf['MySQL']['driver'],
        $appConf['MySQL']['hostname'],
        $appConf['MySQL']['username'],
        $appConf['MySQL']['password'],
        $appConf['MySQL']['basename']
    );
    $this->modules["MySQL"]->query("SET NAMES 'utf8'");
}
if($this->application->isCoreModuleLoaded('SMTPmail')){
    $this->modules["SMTPmail"] = new core\module\SMTPmail(
        $appConf['SMTP']['host'],
        $appConf['SMTP']['port'],
        $appConf['SMTP']['user'],
        $appConf['SMTP']['pass'],
        $appConf['SMTP']['from'],
        $appConf['SMTP']['from-name']
    );
}

if($this->application->isCoreModuleLoaded('http'))
    $this->modules["http"] = new \core\module\http();
if($this->application->isCoreModuleLoaded('users'))
    $this->modules["user"] = new core\module\users($this,1);
if($this->application->isCoreModuleLoaded('tpl'))
    $this->modules["tpl"] = new core\module\tpl($this);
if($this->application->isCoreModuleLoaded('MultiLang'))
    $this->modules["MultiLang"] = new core\module\MultiLang($this);
if($this->application->isCoreModuleLoaded('key'))
    $this->modules["key"] = new core\module\key($this);
