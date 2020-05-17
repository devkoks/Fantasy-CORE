<?php
namespace app\module;
class notfound
{
    public static $requires = [
        "tpl"=>[],
        "module"=>[],
        "functions"=>[]
    ];

    public $view = "404";
    public function init($context){}
}
