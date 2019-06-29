<?php
//SHOPROOO商城资源
namespace OSS\Model;

interface XmlConfig
{
	public function parseFromXml($strXml);

	public function serializeToXml();
}


?>
