<?php
namespace \app\module\subLevel2\subLevel3;
class subLevel4
{
    const require = [
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
