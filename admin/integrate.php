<?php
//SHOPROOO
function conflict_userlist()
{
	$filter['flag'] = empty($_REQUEST['flag']) ? 0 : intval($_REQUEST['flag']);
	$where = ' WHERE flag';

	if ($filter['flag']) {
		$where .= '=' . $filter['flag'];
	}
	else {
		$where .= '>' . 0;
	}

	$sql = 'SELECT COUNT(*) FROM ' . $GLOBALS['ecs']->table('users') . $where;
	$filter['record_count'] = $GLOBALS['db']->getOne($sql);
	$filter = page_and_size($filter);
	$sql = 'SELECT user_id, user_name, email, reg_time, flag, alias ' . ' FROM ' . $GLOBALS['ecs']->table('users') . $where . ' ORDER BY user_id ASC' . ' LIMIT ' . $filter['start'] . ',' . $filter['page_size'];
	$list = $GLOBALS['db']->getAll($sql);
	$list_count = count($list);

	for ($i = 0; $i < $list_count; $i++) {
		$list[$i]['reg_date'] = local_date($GLOBALS['_CFG']['date_format'], $list[$i]['reg_time']);
	}

	$arr = array('list' => $list, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
	return $arr;
}

function save_integrate_config($code, $cfg)
{
	$sql = 'SELECT COUNT(*) FROM ' . $GLOBALS['ecs']->table('shop_config') . ' WHERE code = \'integrate_code\'';

	if ($GLOBALS['db']->GetOne($sql) == 0) {
		$sql = 'INSERT INTO ' . $GLOBALS['ecs']->table('shop_config') . ' (code, value) ' . ('VALUES (\'integrate_code\', \'' . $code . '\')');
	}
	else {
		$sql = 'SELECT value FROM ' . $GLOBALS['ecs']->table('shop_config') . ' WHERE code = \'integrate_code\'';

		if ($code != $GLOBALS['db']->getOne($sql)) {
			$sql = 'UPDATE ' . $GLOBALS['ecs']->table('shop_config') . ' SET value = \'\' WHERE code = \'points_rule\'';
			$GLOBALS['db']->query($sql);
		}

		$sql = 'UPDATE ' . $GLOBALS['ecs']->table('shop_config') . (' SET value = \'' . $code . '\' WHERE code = \'integrate_code\'');
	}

	$GLOBALS['db']->query($sql);

	if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
		$cur_domain = $_SERVER['HTTP_X_FORWARDED_HOST'];
	}
	else if (isset($_SERVER['HTTP_HOST'])) {
		$cur_domain = $_SERVER['HTTP_HOST'];
	}
	else if (isset($_SERVER['SERVER_NAME'])) {
		$cur_domain = $_SERVER['SERVER_NAME'];
	}
	else if (isset($_SERVER['SERVER_ADDR'])) {
		$cur_domain = $_SERVER['SERVER_ADDR'];
	}

	$int_domain = str_replace(array('http://', 'https://'), array('', ''), $cfg['integrate_url']);

	if (strrpos($int_domain, '/')) {
		$int_domain = substr($int_domain, 0, strrpos($int_domain, '/'));
	}

	if ($cur_domain != $int_domain) {
		$same_domain = true;
		$domain = '';
		$cur_domain_arr = explode('.', $cur_domain);
		$int_domain_arr = explode('.', $int_domain);
		if (count($cur_domain_arr) != count($int_domain_arr) || $cur_domain_arr[0] == '' || $int_domain_arr[0] == '') {
			$same_domain = false;
		}
		else {
			$count = count($cur_domain_arr);

			for ($i = 1; $i < $count; $i++) {
				if ($cur_domain_arr[$i] != $int_domain_arr[$i]) {
					$domain = '';
					$same_domain = false;
					break;
				}
				else {
					$domain .= '.' . $cur_domain_arr[$i];
				}
			}
		}

		if ($same_domain == false) {
			$cfg['cookie_domain'] = '';
			$cfg['cookie_path'] = '/';
		}
		else {
			$cfg['cookie_domain'] = $domain;
			$cfg['cookie_path'] = '/';
		}
	}
	else {
		$cfg['cookie_domain'] = '';
		$cfg['cookie_path'] = '/';
	}

	$sql = 'SELECT COUNT(*) FROM ' . $GLOBALS['ecs']->table('shop_config') . ' WHERE code = \'integrate_config\'';

	if ($GLOBALS['db']->GetOne($sql) == 0) {
		$sql = 'INSERT INTO ' . $GLOBALS['ecs']->table('shop_config') . ' (code, value) ' . 'VALUES (\'integrate_config\', \'' . serialize($cfg) . '\')';
	}
	else {
		$sql = 'UPDATE ' . $GLOBALS['ecs']->table('shop_config') . ' SET value=\'' . serialize($cfg) . '\' ' . 'WHERE code=\'integrate_config\'';
	}

	$GLOBALS['db']->query($sql);
	clear_cache_files();
	return true;
}

define('IN_ECS', true);
require dirname(__FILE__) . '/includes/init.php';

if ($_REQUEST['act'] == 'list') {
	$modules = read_modules('../includes/modules/integrates');

	for ($i = 0; $i < count($modules); $i++) {
		$modules[$i]['installed'] = $modules[$i]['code'] == $_CFG['integrate_code'] ? 1 : 0;
	}

	$allow_set_points = 0;
	$smarty->assign('allow_set_points', $allow_set_points);
	$smarty->assign('ur_here', $_LANG['06_list_integrate']);
	$smarty->assign('modules', $modules);
	assign_query_info();
	$smarty->display('integrates_list.dwt');
}

if ($_REQUEST['act'] == 'install') {
	admin_priv('integrate_users', '');
	if ($_GET['code'] == 'ucenter' || $_GET['code'] == 'ecjia') {
		$uc_client_dir = file_mode_info(ROOT_PATH . 'uc_client/data');

		if ($uc_client_dir === false) {
			sys_msg($_LANG['uc_client_not_exists'], 0);
		}

		if ($uc_client_dir < 7) {
			sys_msg($_LANG['uc_client_not_write'], 0);
		}
	}

	if ($_GET['code'] == 'dscmall' || $_GET['code'] == 'ucenter' || $_GET['code'] == 'ecjia') {
		$sql = 'UPDATE ' . $ecs->table('shop_config') . (' SET value = \'' . $_GET['code'] . '\' WHERE code = \'integrate_code\'');
		$db->query($sql);
		$sql = 'UPDATE ' . $GLOBALS['ecs']->table('shop_config') . ' SET value = \'\' WHERE code = \'points_rule\'';
		$GLOBALS['db']->query($sql);
		clear_cache_files();
		$links[0]['text'] = $_LANG['go_back'];
		$links[0]['href'] = 'integrate.php?act=list';
		sys_msg($_LANG['update_success'], 0, $links);
	}
	else {
		$sql = 'UPDATE ' . $GLOBALS['ecs']->table('users') . ' SET flag = 0, alias=\'\'' . ' WHERE flag > 0';
		$db->query($sql);
		$set_modules = true;
		include_once ROOT_PATH . 'includes/modules/integrates/' . $_GET['code'] . '.php';
		$set_modules = false;
		$cfg = $modules[0]['default'];
		$cfg['integrate_url'] = 'http://';
		assign_query_info();
		$smarty->assign('cfg', $cfg);
		$smarty->assign('save', 0);
		$smarty->assign('set_list', get_charset_list());
		$smarty->assign('ur_here', $_LANG['integrate_setup']);
		$smarty->assign('code', $_GET['code']);
		$smarty->display('integrates_setup.dwt');
	}
}

if ($_REQUEST['act'] == 'setup') {
	admin_priv('integrate_users', '');

	if ($_GET['code'] == 'dscmall') {
		sys_msg($_LANG['need_not_setup']);
	}
	else {
		$cfg = unserialize($_CFG['integrate_config']);
		assign_query_info();
		$smarty->assign('save', 1);
		$smarty->assign('set_list', get_charset_list());
		$smarty->assign('ur_here', $_LANG['integrate_setup']);
		$smarty->assign('code', $_GET['code']);
		$smarty->assign('cfg', $cfg);
		$smarty->display('integrates_setup.dwt');
	}
}

if ($_REQUEST['act'] == 'save_uc_config') {
	$code = $_POST['code'];
	$cfg = unserialize($_CFG['integrate_config']);
	include_once ROOT_PATH . 'includes/modules/integrates/' . $code . '.php';
	$_POST['cfg']['uc_connect'] = 'post';
	$_POST['cfg']['quiet'] = 1;
	$cls_user = new $code($_POST['cfg']);
	if ($_POST['uc_connect'] == 'mysql' && $cls_user->error) {
		if ($cls_user->error == 1) {
			sys_msg($_LANG['error_db_msg']);
		}
		else if ($cls_user->error == 2) {
			sys_msg($_LANG['error_table_exist']);
		}
		else if ($cls_user->error == 1049) {
			sys_msg($_LANG['error_db_exist']);
		}
		else {
			sys_msg($cls_user->db->error());
		}
	}

	$cfg = array_merge($cfg, $_POST['cfg']);

	if (save_integrate_config($code, $cfg)) {
		sys_msg($_LANG['save_ok'], 0, array(
	array('text' => $_LANG['06_list_integrate'], 'href' => 'integrate.php?act=list')
	));
	}
	else {
		sys_msg($_LANG['save_error'], 0, array(
	array('text' => $_LANG['06_list_integrate'], 'href' => 'integrate.php?act=list')
	));
	}
}

if ($_REQUEST['act'] == 'save_uc_config_first') {
	$code = $_POST['code'];
	include_once ROOT_PATH . 'includes/modules/integrates/' . $code . '.php';
	$_POST['cfg']['quiet'] = 1;
	$cls_user = new $code($_POST['cfg']);

	if ($cls_user->error) {
		if ($cls_user->error == 1) {
			sys_msg($_LANG['error_db_msg']);
		}
		else if ($cls_user->error == 2) {
			sys_msg($_LANG['error_table_exist']);
		}
		else if ($cls_user->error == 1049) {
			sys_msg($_LANG['error_db_exist']);
		}
		else {
			sys_msg($cls_user->db->error());
		}
	}

	list($appauthkey, $appid, $ucdbhost, $ucdbname, $ucdbuser, $ucdbpw, $ucdbcharset, $uctablepre, $uccharset, $ucapi, $ucip) = explode('|', $_POST['ucconfig']);
	$uc_ip = !empty($ucip) ? $ucip : trim($_POST['uc_ip']);
	$uc_url = !empty($ucapi) ? $ucapi : trim($_POST['uc_url']);
	$cfg = array('uc_id' => $appid, 'uc_key' => $appauthkey, 'uc_url' => $uc_url, 'uc_ip' => $uc_ip, 'uc_connect' => 'mysql', 'uc_charset' => $uccharset, 'db_host' => $ucdbhost, 'db_user' => $ucdbuser, 'db_name' => $ucdbname, 'db_pass' => $ucdbpw, 'db_pre' => $uctablepre, 'db_charset' => $ucdbcharset);
	$cfg['uc_lang'] = $_LANG['uc_lang'];
	$_SESSION['cfg'] = $cfg;
	$_SESSION['code'] = $code;

	if (!empty($_POST['save'])) {
		if (save_integrate_config($code, $cfg)) {
			sys_msg($_LANG['save_ok'], 0, array(
	array('text' => $_LANG['06_list_integrate'], 'href' => 'integrate.php?act=list')
	));
		}
		else {
			sys_msg($_LANG['save_error'], 0, array(
	array('text' => $_LANG['06_list_integrate'], 'href' => 'integrate.php?act=list')
	));
		}
	}

	$query = $db->query('SHOW TABLE STATUS LIKE \'' . $GLOBALS['prefix'] . 'users' . '\'');
	$data = $db->fetch_array($query);

	if ($data['Auto_increment']) {
		$maxuid = $data['Auto_increment'] - 1;
	}
	else {
		$maxuid = 0;
	}

	save_integrate_config($code, $cfg);
	$smarty->assign('ur_here', $_LANG['ucenter_import_username']);
	$smarty->assign('user_startid_intro', sprintf($_LANG['user_startid_intro'], $maxuid, $maxuid));
	$smarty->display('integrates_uc_import.dwt');
}

if ($_REQUEST['act'] == 'setup_ucenter') {
	include_once ROOT_PATH . 'includes/cls_json.php';
	include_once ROOT_PATH . 'includes/cls_transport.php';
	$json = new JSON();
	$result = array('error' => 0, 'message' => '');
	$app_type = 'shoprooo';
	$app_name = $db->getOne('SELECT value FROM ' . $ecs->table('shop_config') . ' WHERE code = \'shop_name\'');
	$app_url = $GLOBALS['ecs']->url();
	$app_charset = EC_CHARSET;
	$app_dbcharset = strtolower(str_replace('-', '', EC_CHARSET));
	$ucapi = !empty($_POST['ucapi']) ? trim($_POST['ucapi']) : '';
	$ucip = !empty($_POST['ucip']) ? trim($_POST['ucip']) : '';
	$dns_error = false;

	if (!$ucip) {
		$temp = @parse_url($ucapi);
		$ucip = gethostbyname($temp['host']);
		if (ip2long($ucip) == -1 || ip2long($ucip) === false) {
			$ucip = '';
			$dns_error = true;
		}
	}

	if ($dns_error) {
		$result['error'] = 2;
		$result['message'] = '';
		exit($json->encode($result));
	}

	$ucfounderpw = trim($_POST['ucfounderpw']);
	$app_tagtemplates = 'apptagtemplates[template]=' . urlencode('<a href="{url}" target="_blank">{goods_name}</a>') . '&' . 'apptagtemplates[fields][goods_name]=' . urlencode($_LANG['tagtemplates_goodsname']) . '&' . 'apptagtemplates[fields][uid]=' . urlencode($_LANG['tagtemplates_uid']) . '&' . 'apptagtemplates[fields][username]=' . urlencode($_LANG['tagtemplates_username']) . '&' . 'apptagtemplates[fields][dateline]=' . urlencode($_LANG['tagtemplates_dateline']) . '&' . 'apptagtemplates[fields][url]=' . urlencode($_LANG['tagtemplates_url']) . '&' . 'apptagtemplates[fields][image]=' . urlencode($_LANG['tagtemplates_image']) . '&' . 'apptagtemplates[fields][goods_price]=' . urlencode($_LANG['tagtemplates_price']);
	$postdata = 'm=app&a=add&ucfounder=&ucfounderpw=' . urlencode($ucfounderpw) . '&apptype=' . urlencode($app_type) . '&appname=' . urlencode($app_name) . '&appurl=' . urlencode($app_url) . '&appip=&appcharset=' . $app_charset . '&appdbcharset=' . $app_dbcharset . '&apptagtemplates=' . $app_tagtemplates;
	$t = new transport();
	$ucconfig = $t->request($ucapi . '/index.php', $postdata);
	$ucconfig = $ucconfig['body'];

	if (empty($ucconfig)) {
		$result['error'] = 1;
		$result['message'] = $_LANG['uc_msg_verify_failur'];
	}
	else if ($ucconfig == '-1') {
		$result['error'] = 1;
		$result['message'] = $_LANG['uc_msg_password_wrong'];
	}
	else {
		list($appauthkey, $appid) = explode('|', $ucconfig);
		if (empty($appauthkey) || empty($appid)) {
			$result['error'] = 1;
			$result['message'] = $_LANG['uc_msg_data_error'];
		}
		else {
			$result['error'] = 0;
			$result['message'] = $ucconfig;
		}
	}

	exit($json->encode($result));
}

if ($_REQUEST['act'] == 'complete') {
	sys_msg($_LANG['sync_ok'], 0, array(
	array('text' => $_LANG['06_list_integrate'], 'href' => 'integrate.php?act=list')
	));
}

?>
