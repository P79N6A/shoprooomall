<?php
//zend SHOPROOO在线更新版  禁止倒卖 一经发现停止任何服务
namespace App\Entities;

class CommentBaseline extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'comment_baseline';
	public $timestamps = false;
	protected $fillable = array('goods', 'service', 'shipping');
	protected $guarded = array();

	public function getGoods()
	{
		return $this->goods;
	}

	public function getService()
	{
		return $this->service;
	}

	public function getShipping()
	{
		return $this->shipping;
	}

	public function setGoods($value)
	{
		$this->goods = $value;
		return $this;
	}

	public function setService($value)
	{
		$this->service = $value;
		return $this;
	}

	public function setShipping($value)
	{
		$this->shipping = $value;
		return $this;
	}
}

?>
