<?php
class ProjectTest extends CDbTestCase
{
	public $fixtures=array(
		'projects'=>'Project', 
		'users'=>'User',
		'projUsrAssign'=>':tbl_project_user_assignment',
    );
	
	/*
	public function testCRUD() 
	{ 
		
		$prjs = $this->projects;
		
		
		//Create a new project 
		$newProject=new Project; 
		$newProjectName = 'Test Project 1'; 
		$newProject->setAttributes(
								array( 
		                        	'name' => $newProjectName, 
		                            'description' => 'Test project number one', 
		                            'create_time' => '2010-01-01 00:00:00', 
		                            'create_user_id' => 1, 
		                            'update_time' => '2010-01-01 00:00:00', 
		                            'update_user_id' => 1, 
		                            )
								); 
		$this->assertTrue($newProject->save(false)); 
		
		//READ back the newly created project 
		$retrievedProject=Project::model()->findByPk($newProject->id); 
		$this->assertTrue($retrievedProject instanceof Project); 
		$this->assertEquals($newProjectName,$retrievedProject->name); 
		
		//UPDATE the newly created project  
		$updatedProjectName = 'Updated Test Project 1'; 
		$newProject->name = $updatedProjectName; 
		$this->assertTrue($newProject->save(false)); 

		//read back the record again to ensure the update worked   
		$updatedProject=Project::model()->findByPk($newProject->id); 
		$this->assertTrue($updatedProject instanceof Project); 
		$this->assertEquals($updatedProjectName,$updatedProject->name); 

		//DELETE the project  
		$newProjectId = $newProject->id; 
		$this->assertTrue($newProject->delete()); 
		$deletedProject=Project::model()->findByPk($newProjectId); 
		$this->assertEquals(NULL,$deletedProject); 
		
		
		
	}
	*/
	public function testCreate() 
	{ 
		//CREATE a new Project 
	   	$newProject=new Project; 
	    $newProjectName = 'Test Project Creation'; 
	    $newProject->setAttributes(array( 
	    	'name' => $newProjectName, 
	        'description' => 'This is a test for new project creation', 
	    )); 
	
	    //set the application user id to the first user in our users fixture data
	    Yii::app()->user->setId($this->users('user1')->id);
	    //save the new project, triggering attribute validation
	    $this->assertTrue($newProject->save()); 

	    //READ back the newly created Project to ensure the creation worked 
	    $retrievedProject=Project::model()->findByPk($newProject->id); 
	    $this->assertTrue($retrievedProject instanceof Project); 
	    $this->assertEquals($newProjectName,$retrievedProject->name); 
        
        //ensure the user associated with creating the new project is the same as the applicaiton user we set
        //when saving the project
        $this->assertEquals(Yii::app()->user->id, $retrievedProject->create_user_id);
	} 

	              public function testRead() 
	              { 
	                  $retrievedProject = $this->projects('project1'); 
	                            $this->assertTrue($retrievedProject instanceof Project); 
	                            $this->assertEquals('Test Project 1',$retrievedProject->name); 
	              } 

	              public function testUpdate() 
	              { 
	                  $project = $this->projects('project2'); 
	                  $updatedProjectName = 'Updated Test Project 2'; 
	                            $project->name = $updatedProjectName; 
	                            $this->assertTrue($project->save(false));  

	                            //read back the record again to ensure the update worked   
	                            $updatedProject=Project::model()->findByPk($project->id); 
	                            $this->assertTrue($updatedProject instanceof Project); 
	                            $this->assertEquals($updatedProjectName,$updatedProject->name); 
	              }                         

	              public function testDelete() 
	              { 
	                  $project = $this->projects('project2'); 
	                  $savedProjectId = $project->id; 
	                            $this->assertTrue($project->delete()); 
	                            $deletedProject=Project::model()->findByPk($savedProjectId); 
	                            $this->assertEquals(NULL,$deletedProject); 
	              } 
	
				public function testGetUserOptions()
				{
					$project = $this->projects('project1');
					$options = $project->userOptions;  
					$this->assertTrue(is_array($options));  
					$this->assertTrue(count($options) > 0);
				}
	
	
	
	
} 




