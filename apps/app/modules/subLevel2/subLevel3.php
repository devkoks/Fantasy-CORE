<?php
namespace \app\module\subLevel2;
class subLevel3
{
    public static $requires = [
        "tpl"=>["home"],
        "module"=>["tpl"],
        "functions"=>["generateGUID"]
    ];
    public $view = "";
    public function init($context)
    {
        $this->view = $context->core->tpl["home"];
    }
}
