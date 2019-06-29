<?php
//SHOPROOO商城资源
namespace App\Api\Controllers\Brand;

class BrandController extends \App\Api\Controllers\Controller
{
	/** @var  $brand */
	protected $brand;
	/** @var $brandTransformer */
	protected $brandTransformer;

	public function __construct(\App\Repositories\Brand\BrandRepository $brand)
	{
		$this->brand = $brand;
	}

	public function index()
	{
		$data = $this->brand->getAllBrands();
		return $this->apiReturn($data);
	}

	public function get($id)
	{
		$data = $this->brand->getBrandDetail($id);
		return $this->apiReturn($data);
	}
}

?>
