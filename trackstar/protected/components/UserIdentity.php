<?php
/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identify the user.
 */
class UserIdentity extends CUserIdentity
{
    private $_id;

    /**
	 * Authenticates a user using the User data model.
	 * @return boolean whether authentication succeeds.
	 */
    public function authenticate()
    {
        $user=User::model()->findByAttributes(array('username'=>$this->username));
        if($user===null)
        {
			$this->errorCode=self::ERROR_USERNAME_INVALID;
	    }
        else 
        {
	        if($user->password!==$user->encrypt($this->password))
	        {
				$this->errorCode=self::ERROR_PASSWORD_INVALID;
			}
        	else
        	{
            	$this->_id = $user->id;
            	if(null===$user->last_login_time)
            	{
					$lastLogin = time();
				}
	        	else
	        	{
					$lastLogin = strtotime($user->last_login_time);
				}
	        	$this->setState('lastLoginTime', $lastLogin); $this->errorCode=self::ERROR_NONE;
			}
        }
        return !$this->errorCode;
    }
 
    public function getId()
    {
        return $this->_id;
    }

}
