<?php
namespace core\module;
class SMTPmail
{
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
	
	public function send($to,$subject,$message)
	{
		$this->from_name = base64_encode($this->from_name);
		$subject = base64_encode($subject);
		$message = base64_encode($message);
		$message = "Content-Type: text/html; charset=\"utf-8\"\r\nContent-Transfer-Encoding: base64\r\nUser-Agent: Koks SMTP Mail\r\nMIME-Version: 1.0\r\n\r\n".$message;
		$subject="=?utf-8?B?{$subject}?=";
		$this->from_name="=?utf-8?B?{$this->from_name}?=";
		$error = array();   
		$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		if ($socket < 0) {
			$error[] = 'socket_create() failed: '.socket_strerror(socket_last_error())."\n";
		}

		$result = socket_connect($socket, $this->host, $this->port);
		if ($result === false) {
		   $error[] = 'socket_connect() failed: '.socket_strerror(socket_last_error())."\n";
		} 
		if(count($error) == 0){
			$message = "FROM:{$this->from_name}<{$this->from}>\r\n".$message;
			$message = "To: $to\r\n".$message;
			$message = "Subject: $subject\r\n".$message;
			$utc = date('r');
			$message = "Date: {$utc}\r\n".$message;
			$cmd = array();
			$cmd[] = 'EHLO '.$this->user;
			$cmd[] = 'AUTH LOGIN';
			$cmd[] = base64_encode($this->user);
			$cmd[] = base64_encode($this->pass);
			$cmd[] = "MAIL FROM:<{$this->from}>";
			$cmd[] = "RCPT TO:<{$to}>";
			$cmd[] = 'DATA';
			$cmd[] = $message."\r\n.";
			$cmd[] = 'QUIT';
			$this->read($socket);
			foreach($cmd as $ex){
				$this->write($socket,$ex);
				$this->read($socket);
			}
			return true;
		}else{
			foreach($error as $text){
				print $text;
			}
			return false;
		}
		if(isset($socket)){
			socket_close($socket);
		}
	}
	private function read($socket)
	{
		$read = socket_read($socket, 1024);
		if($read{0} != '2' && $read{0} != '3'){
			if(!empty($read)){
				return 'SMTP failed: '.$read."\n";
			}else{
				return 'Unknown error'."\n";
			}
		}
	}
	private function write($socket,$msg)
	{
		$msg = $msg."\r\n";
		socket_write($socket, $msg, strlen($msg));
	}
}
