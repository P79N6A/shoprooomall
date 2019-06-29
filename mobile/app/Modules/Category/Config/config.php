<?php
//SHOPROOO商城资源
return array(
	'HTML_CACHE_ON'    => true,
	'HTML_CACHE_TIME'  => 3600 * 24,
	'HTML_FILE_SUFFIX' => '.shtml',
	'HTML_CACHE_RULES' => array(
		'*' => array('{$_SERVER.REQUEST_URI|md5}')
		)
	);

?>
