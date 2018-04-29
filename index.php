<?php
$CoreConf = ['start-app'=>true,'load-tpl'=>true,'load-modules'=>true,'app-dir'=>'/./'];//Параметры для подключения ядра(FastConf)
include './core/index.php';//Подключаем ядро которое запустит приложение
new core($CoreConf);
