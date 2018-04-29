<?php
namespace core\module;
class mail
{
	const require = [
        'module'=>[],
        'functions'=>['SetLog']
    ];
	private $host;
	private $port;
	private $user;
	private $pass;
	private $from;
	private $from_name;
	public function __construct($host,$port,$user,$pass,$from,$from_name)
	{
		$this->host = $host;
		$this->port = $port;
		$this->user = $user;
		$this->pass = $pass;
		$this->from = $from;
		$this->from_name = $from_name;
	}
	
	public function send($to,$subject,$message,$ContentType,$filename,$filedata)
	{
		$this->from_name = base64_encode($this->from_name);
		$message = base64_encode($message);
		$headers = '';

		$headers .= 'MIME-Version: 1.0'.PHP_EOL;
		$headers .= 'Content-Type: multipart/mixed; charset="utf-8"; boundary="8on.ru-massager"'.PHP_EOL;  
		$headers .= 'Subject: '.$subject.''.PHP_EOL;
		$headers .= 'Date: '.date('r').''.PHP_EOL;
		$headers .= 'To: '.$to.PHP_EOL;

		$headers .= '--8on.ru-massager'.PHP_EOL;
		$headers .= 'Content-Type: '.$ContentType.'; charset="utf-8"'.PHP_EOL;
		$headers .= 'Content-Transfer-Encoding: base64'.PHP_EOL;
		$headers .= 'User-Agent: Koks SMTP Mail'.PHP_EOL;
		$headers .= PHP_EOL;
		$headers .= chunk_split($message).''.PHP_EOL;

		if($filedata != null and $filename != null){
			$headers .= '--8on.ru-massager'.PHP_EOL;
			$headers .= 'Content-Type: application/octet-stream; name="'.$filename.'"'.PHP_EOL;
			$headers .= 'Content-Transfer-Encoding: base64'.PHP_EOL;
			$headers .= 'Content-Disposition: attachment; filename="'.$filename.'"'.PHP_EOL;
			$headers .= PHP_EOL;
			$headers .= chunk_split(base64_encode($filedata)).''.PHP_EOL;
		}

		$headers .= '--8on.ru-massager--'.PHP_EOL;
		$message = $headers;



		$subject="=?utf-8?B?{$subject}?=";
		$from_name="=?utf-8?B?{$this->from_name}?=";
		$error = array();   
		$socket = fsockopen($this->host, $this->port, $errno, $errstr, 30);

		if(count($error) == 0){
			$this->smtp_read($socket);
			$this->smtp_write($socket,'EHLO 8on.ru');
			$this->smtp_read($socket);
			$this->smtp_write($socket,'AUTH LOGIN');
			$this->smtp_read($socket);
			$this->smtp_write($socket,base64_encode($this->user));
			$this->smtp_read($socket);
			$this->smtp_write($socket,base64_encode($this->pass));
			$this->smtp_read($socket);
			$this->smtp_write($socket,"MAIL FROM:<{$this->from}>");
			$this->smtp_read($socket);
			$this->smtp_write($socket,"RCPT TO:<{$to}>");
			$this->smtp_read($socket);
			$this->smtp_write($socket,'DATA');
			$this->smtp_read($socket);
			$this->smtp_write($socket, $message."\r\n.");
			$this->smtp_read($socket);
			$this->smtp_write($socket, 'QUIT');
			$this->smtp_read($socket);
			return true;
		}else{
			foreach($error as $text){
				 $errors .= $text;
			}
			return $errors;
		}
		if(isset($socket)){
			socket_close($socket);
		}
	}
	private function smtp_read($socket){
		$read = fgets($socket, 1024);
		if($read{0} != '2' && $read{0} != '3'){
			if(!empty($read)){
				var_dump('SMTP failed: '.$read."\n");
				return 'SMTP failed: '.$read."\n";
			}else{
				return 'Unknown error'."\n";
			}
		}
	}
	private function smtp_write($socket,$msg) {
		$msg = $msg."\r\n";
		fputs($socket, $msg);
	}
}