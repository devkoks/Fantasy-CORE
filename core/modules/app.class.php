<?php
namespace core\module;
class app
{
    public static $requires = [

        'module'=>['MySQL','tpl'],
        'functions'=>[]
    ];
	private $MySQL;

	public function __construct()
	{
		global $MySQL;
		$this->MySQL = $MySQL;
	}
}
