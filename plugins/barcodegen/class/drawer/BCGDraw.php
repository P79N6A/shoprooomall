<?php
//SHOPROOO商城资源
abstract class BCGDraw
{
	protected $im;
	protected $filename;

	protected function __construct($im)
	{
		$this->im = $im;
	}

	public function setFilename($filename)
	{
		$this->filename = $filename;
	}

	abstract public function draw();
}


?>
