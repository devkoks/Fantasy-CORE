<?php
namespace core\module;
class app
{
	private $MySQL;
	public function __construct()
	{
		global $MySQL;
		$this->MySQL = $MySQL;
	}
}
