<?php
 //SHOPROOO
if (!defined('IN_ECS')) {
	exit('Hacking attempt');
}

error_reporting(32767);

if (__FILE__ == '') {
	exit('Fatal error code: 0');
}

define('ROOT_PATH', str_replace('includes/init_api.php', '', str_replace('\\', '/', __FILE__)));
$GLOBALS['_beginTime'] = microtime(true);
define('MEMORY_LIMIT_ON', function_exists('memory_get_usage'));

if (MEMORY_LIMIT_ON) {
	$GLOBALS['_startUseMems'] = memory_get_usage();
}

@ini_set('memory_limit', '512M');
@ini_set('session.cache_expire', 180);
@ini_set('session.use_trans_sid', 0);
@ini_set('session.use_cookies', 1);
@ini_set('session.auto_start', 0);
@ini_set('display_errors', 1);

if (DIRECTORY_SEPARATOR == '\\') {
	@ini_set('include_path', '.;' . ROOT_PATH);
}
else {
	@ini_set('include_path', '.:' . ROOT_PATH);
}

require ROOT_PATH . 'data/config.php';

if (defined('DEBUG_MODE') == false) {
	define('DEBUG_MODE', 0);
}

if ('5.1' <= PHP_VERSION && !empty($timezone)) {
	date_default_timezone_set($timezone);
}

$php_self = isset($_SERVER['PHP_SELF']) && !empty($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];

if ('/' == substr($php_self, -1)) {
	$php_self .= 'index.php';
}

define('PHP_SELF', $php_self);
require ROOT_PATH . 'includes/Http.class.php';
require ROOT_PATH . 'includes/cls_pinyin.php';
require ROOT_PATH . 'includes/inc_constant.php';
require ROOT_PATH . 'includes/cls_ecshop.php';
require ROOT_PATH . 'includes/cls_error.php';
require ROOT_PATH . 'includes/lib_time.php';
require ROOT_PATH . 'includes/lib_base.php';
require ROOT_PATH . 'includes/lib_common.php';
require ROOT_PATH . 'includes/lib_main.php';
require ROOT_PATH . 'includes/lib_insert.php';
require ROOT_PATH . 'includes/lib_goods.php';
require ROOT_PATH . 'includes/lib_article.php';
require ROOT_PATH . '/includes/cls_captcha_verify.php';
require ROOT_PATH . 'includes/lib_ecmoban.php';
require ROOT_PATH . 'includes/lib_ecmobanFunc.php';
require ROOT_PATH . 'includes/lib_seller_store.php';

if (!get_magic_quotes_gpc()) {
	if (!empty($_GET)) {
		$_GET = addslashes_deep($_GET);
	}

	if (!empty($_POST)) {
		$_POST = addslashes_deep($_POST);
	}

	$_COOKIE = addslashes_deep($_COOKIE);
	$_REQUEST = addslashes_deep($_REQUEST);
}

$ecs = new ECS($db_name, $prefix);
define('DATA_DIR', $ecs->data_dir());
define('IMAGE_DIR', $ecs->image_dir());
require ROOT_PATH . 'includes/cls_mysql.php';
$db = new cls_mysql($db_host, $db_user, $db_pass, $db_name);
$db->set_disable_cache_tables(array($ecs->table('sessions'), $ecs->table('sessions_data'), $ecs->table('cart')));
$db_host = $db_user = $db_pass = $db_name = NULL;
$err = new ecs_error('message.dwt');
$_CFG = load_config();
require ROOT_PATH . 'data/sms_config.php';
require ROOT_PATH . 'languages/' . $_CFG['lang'] . '/common.php';
require ROOT_PATH . 'languages/' . $_CFG['lang'] . '/js_languages.php';

if (file_exists(ROOT_PATH . 'languages/' . $_CFG['lang'] . '/' . basename(PHP_SELF))) {
	include ROOT_PATH . 'languages/' . $_CFG['lang'] . '/' . basename(PHP_SELF);
}

if (is_spider()) {
	$_SESSION = array();
	$_SESSION['user_id'] = 0;
	$_SESSION['user_name'] = '';
	$_SESSION['email'] = '';
	$_SESSION['user_rank'] = 0;
	$_SESSION['discount'] = 1;
}

if (isset($_SERVER['PHP_SELF'])) {
	$_SERVER['PHP_SELF'] = htmlspecialchars($_SERVER['PHP_SELF']);
}

if (!defined('INIT_NO_SMARTY')) {
	header('Cache-control: private');
	header('Content-type: text/html; charset=' . EC_CHARSET);
	require ROOT_PATH . 'includes/cls_template.php';
	$smarty = new cls_template();
	$smarty->cache_lifetime = $_CFG['cache_time'];
	$smarty->template_dir = ROOT_PATH . 'themes/' . $_CFG['template'];
	$smarty->cache_dir = ROOT_PATH . 'temp/caches';
	$smarty->compile_dir = ROOT_PATH . 'temp/compiled';

	if ((DEBUG_MODE & 2) == 2) {
		$smarty->direct_output = true;
		$smarty->force_compile = true;
	}
	else {
		$smarty->direct_output = false;
		$smarty->force_compile = false;
	}

	$smarty->assign('lang', $_LANG);
	$smarty->assign('ecs_charset', EC_CHARSET);
}

if ((DEBUG_MODE & 1) == 1) {
	error_reporting(32767);
}
else {
	error_reporting(32767 ^ (8 | 2));
}

if ((DEBUG_MODE & 4) == 4) {
	include ROOT_PATH . 'includes/lib.debug.php';
}

?>
