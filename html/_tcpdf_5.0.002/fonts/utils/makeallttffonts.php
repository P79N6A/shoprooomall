<?php
//SHOPROOO商城资源
$handle = opendir('.');

while ($file = readdir($handle)) {
	$path_parts = pathinfo($file);
	$file_ext = strtolower($path_parts['extension']);

	if ($file_ext == 'ttf') {
		exec('./ttf2ufm -a -F ' . $path_parts['basename'] . '');
		exec('php -q makefont.php ' . $path_parts['basename'] . ' ' . $path_parts['filename'] . '.ufm');
	}
}

closedir($handle);

?>
