<?php
class main
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
