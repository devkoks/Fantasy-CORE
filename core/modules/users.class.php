<?php
namespace core\module;
class users
{
    const USERS_ID = 1;
    const USERS_LOGIN = 2;
    const USERS_GUID = 3;
	public function checkAuth()
	{
		global $MySQL;
		if(isset($_COOKIE['GUID']) and $_COOKIE['GUID'] !== '{NULL}'){
			$user = $this->getUser(USERS_GUID,$_COOKIE['GUID']);
			if(count($user) != 0){
				$return = true;
			}else{
				$return = false;
			}
		}else{
			$return = false;
		}
		return $return;
	}
	public function getUser($type,$data)
	{
		global $MySQL;
		$data = $MySQL->real_escape_string($data);
		switch($type){
			case USERS_ID:
				$WHERE = "`id`=$data";
			break;
			case USERS_LOGIN:
				$WHERE = "`login`='$data'";
			break;
			case USERS_GUID:
				$WHERE = "`GUID`='$data'";
			break;
			default:
				trigger_error("Unknown type \"$type\"",E_USER_WARNING);
				return false;
		}
		
		$userdata = $MySQL->query("SELECT * FROM `users` WHERE $WHERE");
		return $MySQL->fetch_assoc($userdata);
	}
	
	public function getThisUser()
	{
		if($this->checkAuth()){
			return $this->getUser(USERS_GUID,$_COOKIE['GUID']);
		}else{
			return false;
		}
	}
	
	public function setUser($id,$name,$val)
	{
		global $MySQL;
		$MySQL->query("UPDATE `users` SET `".$name."`='".$val."' WHERE `id`='".$id."'");
	}
    
    public function gender($gender)
	{
		switch($gender){
			case 0:
				return 'Не указан';
			break;
			case 1:
				return 'Мужской';
			break;
			case 2:
				return 'Женский';
			break;
			default:
				return 'Другой';
		}
	}
    
    public function is_online($type,$data)
    {
        $user = $this->getUser($type,$data);
        if($user != null){
            if($user['last-activity'] > (time()-600)){
                return true;
            }else{
                return false;
            }
        }else{
            return null;
        }
    }
}
