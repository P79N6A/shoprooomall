<?php
//SHOPROOO商城资源
namespace app\api;

class Transformer implements \app\contracts\transformer\TransformerInterface
{
	/**
     * @var array
     */
	protected $_hidden = array();
	/**
     * @var array
     */
	protected $_map = array();

	public function setHidden(array $hidden = array())
	{
		$this->_hidden = $hidden;
	}

	public function setMap(array $map = array())
	{
		$this->_map = $map;
	}

	public function transformer(array $data = array())
	{
		return $data;
	}
}
?>
