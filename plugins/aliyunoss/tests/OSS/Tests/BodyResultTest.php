<?php
//SHOPROOO商城资源
namespace OSS\Tests;

class BodyResultTest extends \PHPUnit_Framework_TestCase
{
	public function testParseValid200()
	{
		$response = new \OSS\Http\ResponseCore(array(), 'hi', 200);
		$result = new \OSS\Result\BodyResult($response);
		$this->assertTrue($result->isOK());
		$this->assertEquals($result->getData(), 'hi');
	}

	public function testParseInvalid404()
	{
		$response = new \OSS\Http\ResponseCore(array(), null, 200);
		$result = new \OSS\Result\BodyResult($response);
		$this->assertTrue($result->isOK());
		$this->assertEquals($result->getData(), '');
	}
}

?>
