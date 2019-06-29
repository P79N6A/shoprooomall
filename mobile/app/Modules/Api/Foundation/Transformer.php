<?php
//zend by SHOPROOO商城资源
namespace App\Modules\Api\Foundation;

abstract class Transformer
{
	public function transformCollection(array $map)
	{
		return array_map(array($this, 'transform'), $map);
	}

	abstract public function transform(array $map);
}


?>
