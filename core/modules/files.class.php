<?php
namespace core\module;
class files
{
	public static function read($path)
	{
		if(file_exists($path)){
			$f=fopen($path,"r");
			$data=fread($f,filesize($path));
			fclose($f);
			return $data;
		}else{
			return false;
		}
	}
}
