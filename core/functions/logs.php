<?php
function SetLog($string)
{
	global $setting;
	if($setting['logs']['enable']){
		$time = time();
		$date = getdate($time);
		$string = date("[d/m/Y H:i:s]",$time).$string;
		$logfile = fopen($setting['DOCUMENT_ROOT'].$setting['logs']['dir'].$setting['logs']['prefix'].date("d-m-Y").'.log', 'a');
		fwrite($logfile,$string.PHP_EOL);
		fclose($logfile);
		return true;
	}
}
