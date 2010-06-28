<?php

class IssueController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to 'column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='column2';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;
	
	/**
	 * @var private property containing the associated Project model instance.
	 */
	private $_project = null; 
	

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'projectContext + create index admin', //perform a check to ensure valid project context 
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 */
	public function actionView()
	{
		$issue=$this->loadModel(true);
		$comment=$this->createComment($issue);

		$this->render('view',array(
					  'model'=>$issue,
				      'comment'=>$comment,
				      ));
	}
	
	/**
	 * Creates a new comment on an issue.
	 */
	protected function createComment($issue)
	{
		$comment=new Comment;  
		if(isset($_POST['Comment']))
		{
			$comment->attributes=$_POST['Comment'];
			if($issue->addComment($comment))
			{
				Yii::app()->user->setFlash('commentSubmitted',"Your comment has been added." );
				$this->refresh();
			}
		}
		return $comment;
	}
	

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Issue;
		
		$model->project_id = $this->_project->id;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Issue']))
		{
			$model->attributes=$_POST['Issue'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionUpdate()
	{
		$model=$this->loadModel();

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Issue']))
		{
			$model->attributes=$_POST['Issue'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 */
	public function actionDelete()
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel()->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(array('index'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		//$dataProvider=new CActiveDataProvider('Issue');
		$dataProvider=new CActiveDataProvider('Issue', array(
			'criteria'=>array(
		 		'condition'=>'project_id=:projectId',
		 		'params'=>array(':projectId'=>$this->_project->id),
		 	),
		));
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Issue('search');
		if(isset($_GET['Issue']))
			$model->attributes=$_GET['Issue'];

		$model->project_id = $this->_project->id;
		
		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 */
	public function loadModel($withComments=false)
	{
		if($this->_model===null)
		{
			if(isset($_GET['id']))
			{
			     if($withComments)
			     {
				      $this->_model=Issue::model()->with(array(
					                     'comments'=>array('with'=>'author')))->findbyPk($_GET['id']);
				 }
			     else
			     {
				      $this->_model=Issue::model()->findbyPk($_GET['id']);
			     }
			}
			if($this->_model===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='issue-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	
	/**
	 * In-class defined filter method, configured for use in the above filters() method
	 * It is called before the actionCreate() action method is run in order to ensure a proper project context
	 */
	public function filterProjectContext($filterChain)
	{   
		     //set the project identifier based on either the GET or POST input request variables, since we allow both types for our actions   
		     $projectId = null;
		     if(isset($_GET['pid'])) 
		          $projectId = $_GET['pid'];
		     else
		          if(isset($_POST['pid'])) 
				       $projectId = $_POST['pid'];

			 $this->loadProject($projectId);   

		     //complete the running of other filters and execute the requested action
		     $filterChain->run(); 
	} 
	 
	
	/**
	 * Protected method to load the associated Project model class
	 * @project_id the primary identifier of the associated Project
	 * @return object the Project data model based on the primary key 
	 */
	protected function loadProject($project_id)
	{
			 //if the project property is null, create it based on input id
			 if($this->_project===null)
			 {
				$this->_project=Project::model()->findbyPk($project_id);
				if($this->_project===null)
	            {
					throw new CHttpException(404,'The requested project does not exist.'); 
				}
			 }

			 return $this->_project; 
	} 
	
	/**
	 *	Returns the project model instance to which this issue belongs
	 */
	public function getProject()
	{
		return $this->_project;
	}
	
	
	
}
