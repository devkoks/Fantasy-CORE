<?php
function GenerateGUID(){
	$charid = strtoupper(md5(uniqid(rand(), true)));
	$hyphen = '-';
	$uuid = substr($charid, 0, 8).$hyphen.substr($charid, 8, 4).$hyphen.substr($charid,12, 4).$hyphen.substr($charid,16, 4).$hyphen.substr($charid,20,12);
	return $uuid;
}
?>