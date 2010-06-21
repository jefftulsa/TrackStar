<?php
class CommentTest extends CDbTestCase
{
	
	public $fixtures=array(
		'comments'=>'Comment', 
	);
	
	public function testRecentComments()
	{
		$recentComments=Comment::findRecentComments();  
	    $this->assertTrue(is_array($recentComments));
	}
}