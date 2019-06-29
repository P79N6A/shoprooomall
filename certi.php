<?php
//SHOPROOO商城资源
define('IN_ECS', true);
require dirname(__FILE__) . '/includes/init.php';
$session_id = (empty($_POST['session_id']) ? '' : trim($_POST['session_id']));

if (!empty($session_id)) {
	$sql = 'SELECT sesskey FROM ' . $ecs->table('sessions') . ' WHERE sesskey = \'' . $session_id . '\' ';
	$sesskey = $db->getOne($sql);

	if ($sesskey != '') {
		exit('{"res":"succ","msg":"","info":""}');
	}
	else {
		exit('{"res":"fail","msg":"error:000002","info":""}');
	}
}
else {
	exit('{"res":"fail","msg":"error:000001","info":""}');
}

?>
