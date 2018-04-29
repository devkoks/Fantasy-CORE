<?php
namespace core\module;
class files
{
	const require = [
        'module'=>[],
        'functions'=>[]
    ];
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
	public static function write($path,$data)
	{
		if(file_exists($path)){
			$f=fopen($path,"w");
			$data=fwrite($f,$data);
			fclose($f);
			return true;
		}else{
			return false;
		}
	}
}
