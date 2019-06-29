<?php
//SHOPROOO商城资源
namespace OSS\Result;

class GetWebsiteResult extends Result
{
	protected function parseDataFromResponse()
	{
		$content = $this->rawResponse->body;
		$config = new \OSS\Model\WebsiteConfig();
		$config->parseFromXml($content);
		return $config;
	}

	protected function isResponseOk()
	{
		$status = $this->rawResponse->status;
		if (((int) (intval($status) / 100) == 2) || ((int) intval($status) === 404)) {
			return true;
		}

		return false;
	}
}

?>
