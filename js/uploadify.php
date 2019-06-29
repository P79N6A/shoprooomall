<?php
//SHOPROOO商城资源
$path = 'uploads/';

if (!empty($_FILES)) {
	echo '<pre>';
	print_r($_FILES);
	echo '</pre>';
	exit();
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$fileTypes = array('jpg', 'jpeg', 'gif', 'png');
	$fileName = iconv('UTF-8', 'GB2312', $_FILES['Filedata']['name']);
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	$files = $_POST['typeCode'];

	if (!is_dir($path)) {
		mkdir($path);
	}

	if (move_uploaded_file($tempFile, $path . $fileName)) {
		echo $fileName;
	}
	else {
		echo $fileName . '上传失败！';
	}
}

?>
