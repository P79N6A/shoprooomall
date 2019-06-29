<?php
//SHOPROOO商城资源
class BCGDrawException extends Exception
{
	public function __construct($message)
	{
		parent::__construct($message, 30000);
	}
}

?>
