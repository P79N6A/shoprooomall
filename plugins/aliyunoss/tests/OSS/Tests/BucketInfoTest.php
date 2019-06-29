<?php
//SHOPROOO商城资源
namespace OSS\Tests;

class BucketInfoTest extends \PHPUnit_Framework_TestCase
{
	public function testConstruct()
	{
		$bucketInfo = new \OSS\Model\BucketInfo('cn-beijing', 'name', 'today');
		$this->assertNotNull($bucketInfo);
		$this->assertEquals('cn-beijing', $bucketInfo->getLocation());
		$this->assertEquals('name', $bucketInfo->getName());
		$this->assertEquals('today', $bucketInfo->getCreateDate());
	}
}

?>
