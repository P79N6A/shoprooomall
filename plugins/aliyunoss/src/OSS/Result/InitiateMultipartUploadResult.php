<?php
//SHOPROOO商城资源
namespace OSS\Result;

class InitiateMultipartUploadResult extends Result
{
	protected function parseDataFromResponse()
	{
		$content = $this->rawResponse->body;
		$xml = simplexml_load_string($content);

		if (isset($xml->UploadId)) {
			return strval($xml->UploadId);
		}

		throw new \OSS\Core\OssException('cannot get UploadId');
	}
}

?>
