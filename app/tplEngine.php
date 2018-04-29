<?php
$view = $tpl->comments('{/*','*/}',$view);

if($user->checkAuth()){
	$view = $tpl->array2tpl('userdata',$userdata,$view);
	$view = $tpl->hidden('non-auth',$view);
	$view = $tpl->removeTag('auth',$view);
}else{
	$view = $tpl->removeTag('non-auth',$view);
	$view = $tpl->hidden('auth',$view);
}

$view = $tpl->removeTag('active:module:'.$this->getModule(),$view);
$view = $tpl->hidden('active:module:(.*?)',$view);

$view = $tpl->hidden('unactive:module:'.$this->getModule(),$view);
$view = $tpl->removeTag('unactive:module:(.*?)',$view);

$view = str_replace('{module}',$this->getModule(),$view);
/*if(isset($mod->synonym)){
	$view = str_replace('{module:synonym}',$mod->synonym,$view);
}else{
	$view = str_replace('{module:synonym}',$module,$view);
}*/