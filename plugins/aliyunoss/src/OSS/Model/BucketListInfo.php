<?php
//SHOPROOO商城资源
namespace OSS\Model;

class BucketListInfo
{
	/**
     * BucketInfo信息列表
     *
     * @var array
     */
	private $bucketList = array();

	public function __construct(array $bucketList)
	{
		$this->bucketList = $bucketList;
	}

	public function getBucketList()
	{
		return $this->bucketList;
	}
}


?>
