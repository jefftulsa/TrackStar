<?php
/**
 * RecentComments is a Yii widget used to display a list of recent comments 
 */
class RecentComments extends CWidget
{
	private $_comments;  
	public $displayLimit = 5;
	public $projectId = null;
	
	public function init()
    {
        $this->_comments = Comment::model()->findRecentComments($this->displayLimit, $this->projectId);
    }  

    public function getRecentComments()
	{
		return $this->_comments;
	}

    public function run()
    {
        // this method is called by CController::endWidget()    
        $this->render('recentComments');
    }
}
