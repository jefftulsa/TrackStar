<?php
class RbacCommand extends CConsoleCommand
{
   
    private $_authManager;
 
    public function getHelp()
	{
		return <<<EOD
USAGE
  rbac

DESCRIPTION
  This command generates an initial RBAC authorization hierarchy.

EOD;
	}

	
	/**
	 * Execute the action.
	 * @param array command line parameters specific for this command
	 */
	public function run($args)
	{
		//ensure that an authManager is defined as this is mandatory for creating an auth heirarchy
		if(($this->_authManager=Yii::app()->authManager)===null)
		{
		    echo "Error: an authorization manager, named 'authManager' must be con-figured to use this command.\n";
			echo "If you already added 'authManager' component in application con-figuration,\n";
			echo "please quit and re-enter the yiic shell.\n";
			return;
		}  
		
		//provide the oportunity for the use to abort the request
		echo "This command will create three roles: Owner, Member, and Reader and the following premissions:\n";
		echo "create, read, update and delete user\n";
		echo "create, read, update and delete project\n";
		echo "create, read, update and delete issue\n";
		echo "Would you like to continue? [Yes|No] ";
	   
	    //check the input from the user and continue if they indicated yes to the above question
	    if(!strncasecmp(trim(fgets(STDIN)),'y',1)) 
		{
		     //first we need to remove all operations, roles, child relationship and as-signments
			 $this->_authManager->clearAll();

			 //create the lowest level operations for users
			 $this->_authManager->createOperation("createUser","create a new user"); 
			 $this->_authManager->createOperation("readUser","read user profile information"); 
			 $this->_authManager->createOperation("updateUser","update a users information"); 
			 $this->_authManager->createOperation("deleteUser","remove a user from a project"); 

			 //create the lowest level operations for projects
			 $this->_authManager->createOperation("createProject","create a new project"); 
			 $this->_authManager->createOperation("readProject","read project information"); 
	 		 $this->_authManager->createOperation("updateProject","update project information"); 
			 $this->_authManager->createOperation("deleteProject","delete a pro-ject"); 

			 //create the lowest level operations for issues
			 $this->_authManager->createOperation("createIssue","create a new issue"); 
			 $this->_authManager->createOperation("readIssue","read issue information"); 
			 $this->_authManager->createOperation("updateIssue","update issue information"); 
			 $this->_authManager->createOperation("deleteIssue","delete an issue from a project");     

			 //create the reader role and add the appropriate permissions as children to this role
			 $role=$this->_authManager->createRole("reader"); 
			 $role->addChild("readUser");
			 $role->addChild("readProject"); 
			 $role->addChild("readIssue"); 

			 //create the member role, and add the appropriate permissions, as well as the reader role itself, as children
			 $role=$this->_authManager->createRole("member"); 
			 $role->addChild("reader"); 
			 $role->addChild("createIssue"); 
			 $role->addChild("updateIssue"); 
			 $role->addChild("deleteIssue"); 

			 //create the owner role, and add the appropriate permissions, as well as both the reader and member roles as children
			 $role=$this->_authManager->createRole("owner"); 
			 $role->addChild("reader"); 
			 $role->addChild("member");    
			 $role->addChild("createUser"); 
			 $role->addChild("updateUser"); 
			 $role->addChild("deleteUser");  
			 $role->addChild("createProject"); 
			 $role->addChild("updateProject"); 
			 $role->addChild("deleteProject");	
			
			 //create a general task-level permission for admins
			 $this->_authManager->createTask("adminManagement", "access to the application administration functionality");   
			 //create the site admin role, and add the appropriate permissions	 
			 $role=$this->_authManager->createRole("admin"); 
			 $role->addChild('owner');
			 $role->addChild("reader"); 
			 $role->addChild("member");
			 $role->addChild("adminManagement");
			 //ensure we have one admin in the system (force it to be user id #1)
			 $this->_authManager->assign("admin",1);
			
		
		     //provide a message indicating success
		     echo "Authorization hierarchy successfully generated.";
        } 
    }
}
