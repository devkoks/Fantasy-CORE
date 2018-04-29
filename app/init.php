<?php
if($this->isCoreModuleLoaded('MySQL')){
    $MySQL = new core\module\MySQLdrivers(
        $appConf['MySQL']['driver'],
        $appConf['MySQL']['hostname'],
        $appConf['MySQL']['username'],
        $appConf['MySQL']['password'],
        $appConf['MySQL']['basename']
    );
    $MySQL->query("SET NAMES 'utf8'");
}

if($this->isCoreModuleLoaded('users'))
    $user = new core\module\users();
if($this->isCoreModuleLoaded('files'))
    $file = new core\module\files();
if($this->isCoreModuleLoaded('app'))
    $app = new core\module\app();

if($this->isCoreModuleLoaded('SMTPmail')){
    $mail = new core\module\SMTPmail(
        $appConf['SMTP']['host'],
        $appConf['SMTP']['port'],
        $appConf['SMTP']['user'],
        $appConf['SMTP']['pass'],
        $appConf['SMTP']['from'],
        $appConf['SMTP']['from-name']
    );
}