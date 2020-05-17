<?php
$view = $this->core->modules["tpl"]->comments('{/*','*/}',$view);

$view = str_replace('{module}',$this->getModule(),$view);
