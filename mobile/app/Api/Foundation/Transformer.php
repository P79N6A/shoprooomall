<?php
//SHOPROOO商城资源
namespace App\Api\Foundation;

abstract class Transformer
{
	public function transformCollection(array $map)
	{
		return array_map(array($this, 'transform'), $map);
	}

	abstract public function transform(array $map);
}


?>
