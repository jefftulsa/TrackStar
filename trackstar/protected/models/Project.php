<?php

/**
 * This is the model class for table "tbl_project".
 */
class Project extends TrackStarActiveRecord
{
	/**
	 * The followings are the available columns in table 'tbl_project':
	 * @var integer $id
	 * @var string $name
	 * @var string $description
	 * @var string $create_time
	 * @var integer $create_user_id
	 * @var string $update_time
	 * @var integer $update_user_id
	 */

	/**
	 * Returns the static model of the specified AR class.
	 * @return Project the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_project';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, description', 'required'),
			array('create_user_id, update_user_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>128),
			array('create_time, update_time', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, description, create_time, create_user_id, update_time, update_user_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
	        // NOTE: you may need to adjust the relation name and the related
	        // class name for the relations automatically generated below.
	        return array(
	            'issues' => array(self::HAS_MANY, 'Issue', 'project_id'),
	            'users' => array(self::MANY_MANY, 'User', 'tbl_project_user_assignment(project_id, user_id)'),
	        );
	}
	

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'description' => 'Description',
			'create_time' => 'Create Time',
			'create_user_id' => 'Create User',
			'update_time' => 'Update Time',
			'update_user_id' => 'Update User',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);

		$criteria->compare('name',$this->name,true);

		$criteria->compare('description',$this->description,true);

		$criteria->compare('create_time',$this->create_time,true);

		$criteria->compare('create_user_id',$this->create_user_id);

		$criteria->compare('update_time',$this->update_time,true);

		$criteria->compare('update_user_id',$this->update_user_id);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * @return array of valid users for this project, indexed by user IDs
	 */ 
	public function getUserOptions()
	{
		$usersArray = CHtml::listData($this->users, 'id', 'username');
		return $usersArray;
	}
	
	/**
	 * creates an association between the project, the user and the user's role within the project
	 */
	public function associateUserToRole($role, $userId)
	{
		$sql = "INSERT INTO tbl_project_user_role (project_id, user_id, role) VALUES (:projectId, :userId, :role)";
		$command = Yii::app()->db->createCommand($sql);
		$command->bindValue(":projectId", $this->id, PDO::PARAM_INT);
		$command->bindValue(":userId", $userId, PDO::PARAM_INT);
		$command->bindValue(":role", $role, PDO::PARAM_STR);
		return $command->execute();
	}
	
	/**
	 * removes an association between the project, the user and the user's role within the project
	 */
	public function removeUserFromRole($role, $userId)
	{
		$sql = "DELETE FROM tbl_project_user_role WHERE project_id=:projectId AND user_id=:userId AND role=:role";
		$command = Yii::app()->db->createCommand($sql);
		$command->bindValue(":projectId", $this->id, PDO::PARAM_INT);
		$command->bindValue(":userId", $userId, PDO::PARAM_INT);
		$command->bindValue(":role", $role, PDO::PARAM_STR);
		return $command->execute(); 
	}
	
	/**
	 * @return boolean whether or not the current user is in the specified role within the context of this project
	 */
	public function isUserInRole($role)
	{
		$sql = "SELECT role FROM tbl_project_user_role WHERE project_id=:projectId AND user_id=:userId AND role=:role";
		$command = Yii::app()->db->createCommand($sql);
		$command->bindValue(":projectId", $this->id, PDO::PARAM_INT);
		$command->bindValue(":userId", Yii::app()->user->getId(), PDO::PARAM_INT);
		$command->bindValue(":role", $role, PDO::PARAM_STR);
		return $command->execute()==1 ? true : false;		
	}
	
	/**
	 * Returns an array of available roles in which a user can be placed when being added to a project
	 */
	public static function getUserRoleOptions()
	{
		return CHtml::listData(Yii::app()->authManager->getRoles(), 'name', 'name');
	} 
	
	/**
	 * Makes an association between a user and a the project
	 */
	public function associateUserToProject($user)
	{
		$sql = "INSERT INTO tbl_project_user_assignment (project_id, user_id) VALUES (:projectId, :userId)";
		$command = Yii::app()->db->createCommand($sql);
		$command->bindValue(":projectId", $this->id, PDO::PARAM_INT);
		$command->bindValue(":userId", $user->id, PDO::PARAM_INT);
		return $command->execute();    
	} 
	
	/* 
	 * Determines whether or not a user is already part of a project
	 */
	public function isUserInProject($user) 
	{
		$sql = "SELECT user_id FROM tbl_project_user_assignment WHERE project_id=:projectId AND user_id=:userId";
		$command = Yii::app()->db->createCommand($sql);
		$command->bindValue(":projectId", $this->id, PDO::PARAM_INT);
		$command->bindValue(":userId", $user->id, PDO::PARAM_INT);
		return $command->execute()==1 ? true : false;
	}
	
	
	 
	
	
	
	
	
	
	
}