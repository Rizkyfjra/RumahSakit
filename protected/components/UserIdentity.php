<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	private $_id;
	private $_xp;
	
	public function authenticate()
    {
    	$this->setState('__yiiTeacher',NULL);
    	$this->setState('__yiiKepsek',NULL);
    	$this->setState('__yiiWali',NULL);
    	$this->setState('__yiiAdmin',NULL);
    	$this->setState('__yiiStudent',NULL);
    	$this->setState('__yiiCNotif',NULL);
    	

	        //$record=Users::model()->findByAttributes(array('username'=>$this->username));
	        if(strpos($this->username, '@') !== false){
				$record=User::model()->find('LOWER(email)=?',array(strtolower($this->username)));
			}else{
			   //Otherwise we search using the username
				$record=User::model()->find('LOWER(username)=?',array(strtolower($this->username)));
			}
	        $ph=new PasswordHash(Yii::app()->params['phpass']['iteration_count_log2'], Yii::app()->params['phpass']['portable_hashes']);
	        if($record===null)
	            $this->errorCode=self::ERROR_USERNAME_INVALID;
	        else if(!$ph->CheckPassword($this->password, $record->encrypted_password))
	            $this->errorCode=self::ERROR_PASSWORD_INVALID;
	        else
	        {
	        	
	            $this->_id=$record->id;
	            $user_id = $record->id;
	            $this->setState('__xp',$record->encrypted_password);
	            $this->setState('__role', $record->role_id);
				$this->setState('__username',$record->username);
				$this->setState('__dname',$record->display_name);

				if ($record->role_id == 1){
					$this->setState('__yiiTeacher',1);
				} elseif ($record->role_id == 2) {
					$this->setState('__yiiStudent',1);
				} elseif ($record->role_id == 3) {
					$this->setState('__yiiKepsek',1);
				} elseif ($record->role_id == 4) {
					$this->setState('__yiiWali',1);		
				} elseif ($record->role_id == 99) {
					$this->setState('__yiiAdmin',1);
				}

				$modelNotif=Notification::model()->findAll(array("condition"=>"user_id_to = $user_id and read_at is null"));
				if (!empty($modelNotif)){
					$count = count($modelNotif);
					$this->setState('__yiiCNotif',$count);
				}


	            $this->errorCode=self::ERROR_NONE;
	        }
        return !$this->errorCode;
    }

    public function getId()
	{
		return $this->_id;
	}
}