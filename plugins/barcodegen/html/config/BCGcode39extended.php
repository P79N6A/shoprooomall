<?php
//SHOPROOO商城资源
function customSetup($barcode, $get)
{
	if (isset($get['checksum'])) {
		$barcode->setChecksum($get['checksum'] === '1' ? true : false);
	}
}

$classFile = 'BCGcode39extended.barcode.php';
$className = 'BCGcode39extended';
$baseClassFile = 'BCGBarcode1D.php';
$codeVersion = '5.2.0';

?>
