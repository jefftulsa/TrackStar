<?php
class SysMessageTest extends CDbTestCase
{
	
	public $fixtures=array(
		'messages'=>'SysMessage', 
	);
	
	public function testGetLatest()
	{
		$message = SysMessage::getLatest();
		$this->assertTrue($message instanceof SysMessage); 
	}
}