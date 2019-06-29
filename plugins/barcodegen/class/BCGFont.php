<?php
//SHOPROOO商城资源
interface BCGFont
{
	public function getText();

	public function setText($text);

	public function getRotationAngle();

	public function setRotationAngle($rotationDegree);

	public function getBackgroundColor();

	public function setBackgroundColor($backgroundColor);

	public function getForegroundColor();

	public function setForegroundColor($foregroundColor);

	public function getDimension();

	public function draw($im, $x, $y);
}


?>
