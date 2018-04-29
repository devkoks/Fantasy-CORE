<?php
namespace core\module;
class http
{
	const require = [
        'module'=>[],
        'functions'=>[]
    ];
	public function get()
	{

	}
	public function post($url,$data)
	{
		if(is_string($data)){
			$query = $data;
		}else{
			$query = http_build_query($data);
		}
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $query);
		$result = curl_exec($curl);
		curl_close($curl);
		return $result;
	}
}
