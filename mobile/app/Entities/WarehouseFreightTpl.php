<?php
//zend SHOPROOO在线更新版  禁止倒卖 一经发现停止任何服务
namespace App\Entities;

class WarehouseFreightTpl extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'warehouse_freight_tpl';
	public $timestamps = false;
	protected $fillable = array('tpl_name', 'user_id', 'warehouse_id', 'shipping_id', 'region_id', 'configure');
	protected $guarded = array();

	public function getTplName()
	{
		return $this->tpl_name;
	}

	public function getUserId()
	{
		return $this->user_id;
	}

	public function getWarehouseId()
	{
		return $this->warehouse_id;
	}

	public function getShippingId()
	{
		return $this->shipping_id;
	}

	public function getRegionId()
	{
		return $this->region_id;
	}

	public function getConfigure()
	{
		return $this->configure;
	}

	public function setTplName($value)
	{
		$this->tpl_name = $value;
		return $this;
	}

	public function setUserId($value)
	{
		$this->user_id = $value;
		return $this;
	}

	public function setWarehouseId($value)
	{
		$this->warehouse_id = $value;
		return $this;
	}

	public function setShippingId($value)
	{
		$this->shipping_id = $value;
		return $this;
	}

	public function setRegionId($value)
	{
		$this->region_id = $value;
		return $this;
	}

	public function setConfigure($value)
	{
		$this->configure = $value;
		return $this;
	}
}

?>
