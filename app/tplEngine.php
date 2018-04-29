<?php
$view = $this->core->modules["tpl"]->comments('{/*','*/}',$view);

$view = $this->core->modules["tpl"]->removeTag('active:module:'.$this->getModule(),$view);
$view = $this->core->modules["tpl"]->hidden('active:module:(.*?)',$view);

$view = $this->core->modules["tpl"]->hidden('unactive:module:'.$this->getModule(),$view);
$view = $this->core->modules["tpl"]->removeTag('unactive:module:(.*?)',$view);

$view = str_replace('{module}',$this->getModule(),$view);
