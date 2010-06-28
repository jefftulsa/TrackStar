<?php

/**
 * This is the model class for table "tbl_comment".
 */
class Comment extends TrackStarActiveRecord
{
	/**
	 * The followings are the available columns in table 'tbl_comment':
	 * @var integer $id
	 * @var integer $content
	 * @var integer $issue_id
	 * @var string $create_time
	 * @var integer $create_user_id
	 * @var string $update_time
	 * @var integer $update_user_id
	 */

	/**
	 * Returns the static model of the specified AR class.
	 * @return Comment the static model class
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
		return 'tbl_comment';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('content', 'required'),
			array('issue_id, create_user_id, update_user_id', 'numerical', 'integerOnly'=>true),
			array('create_time, update_time', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, content, issue_id, create_time, create_user_id, update_time, update_user_id', 'safe', 'on'=>'search'),
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
			'author' => array(self::BELONGS_TO, 'User', 'create_user_id'),
			'issue' => array(self::BELONGS_TO, 'Issue', 'issue_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'content' => 'Content',
			'issue_id' => 'Issue',
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

		$criteria->compare('content',$this->content);

		$criteria->compare('issue_id',$this->issue_id);

		$criteria->compare('create_time',$this->create_time,true);

		$criteria->compare('create_user_id',$this->create_user_id);

		$criteria->compare('update_time',$this->update_time,true);

		$criteria->compare('update_user_id',$this->update_user_id);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	public static function findRecentComments($limit=10, $projectId=null)
	{

	     if($projectId != null)
		 {
		      return self::model()->with(array(
					'issue'=>array('condition'=>'project_id='.$projectId)))->findAll(array(
					'order'=>'t.create_time DESC',
					'limit'=>$limit,
			  ));

		 }
		 else
		 {
			  //get all comments across all projects
			  return Comment::model()->with('issue')->findAll(array(
					'order'=>'t.create_time DESC',
					'limit'=>$limit,
			  ));

		 }
	}
	
}