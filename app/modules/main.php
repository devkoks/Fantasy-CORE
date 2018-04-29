<?php
class main
{
    const require = [
        "tpl"=>["home"],
        "module"=>["tpl"],
        "functions"=>["generateGUID"]
    ];
    public $view = "";
    public function init()
    {
        global $template,$app;
        $this->view = $template["home"];
    }
}
