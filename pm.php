<?php
//SHOPROOO
define('IN_ECS', true);
require dirname(__FILE__) . '/includes/init.php';
if (empty($_SESSION['user_id']) || $_CFG['integrate_code'] == 'dscmall') {
	ecs_header('Location:./');
}

uc_call('uc_pm_location', array($_SESSION['user_id']));

?>
