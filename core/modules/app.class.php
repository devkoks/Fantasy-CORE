<?php
namespace core\module;
class app
{
    const require = [

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
