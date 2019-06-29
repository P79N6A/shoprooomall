<?php
//SHOPROOO 
function patch_list($patch_url, $current_version)
{
	$metadata_str = Http::doGet($patch_url);
	$metadata = json_decode($metadata_str, true);
	$patch = array();

	foreach ($metadata['version'] as $k => $v) {
		if (version_compare($v, $current_version, '>')) {
			$patch[] = $v;
		}
	}

	return $patch;
}

function upgrade($patch)
{
	global $patch_url;
	global $upgrade_path;
	global $fs;
	$upgradezip_url = dirname($patch_url) . '/' . substr($patch, 0, 2) . '/patch_' . $patch . '.zip?v=' . date('Ymd');
	$upgradezip_path = $upgrade_path . '/' . $patch . '.zip';
	$upgradezip_source_path = $upgrade_path . '/' . $patch;
	$fs->put($upgradezip_path, Http::doGet($upgradezip_url));
	$fs->move(ROOT_PATH . 'data/config.php', ROOT_PATH . 'data/config.bak.php');

	if (unzip($upgradezip_path, ROOT_PATH) === false) {
		exit('Error : unpack the failure.');
	}

	$fs->move(ROOT_PATH . 'data/config.bak.php', ROOT_PATH . 'data/config.php');
	$fs->delete($upgradezip_path);
	$fs->deleteDirectory($upgradezip_source_path);
	$migration = ROOT_PATH . 'data/migrations/' . $patch . '.php';

	if (file_exists($migration)) {
		require $migration;
	}

	$sql = 'UPDATE ' . $GLOBALS['ecs']->table('shop_config') . ' SET `value` = \'' . $patch . '\' WHERE `code` = \'dsc_version\'';
	$GLOBALS['db']->query($sql);
}

function unzip($src_file, $dest_dir = false, $create_zip_name_dir = true, $overwrite = true)
{
	global $fs;

	if ($zip = zip_open($src_file)) {
		if ($zip) {
			$splitter = $create_zip_name_dir === true ? '.' : '/';

			if ($dest_dir === false) {
				$dest_dir = substr($src_file, 0, strrpos($src_file, $splitter)) . '/';
			}

			if (!$fs->isDirectory($dest_dir)) {
				$fs->makeDirectory($dest_dir);
			}

			while ($zip_entry = zip_read($zip)) {
				$pos_last_slash = strrpos(zip_entry_name($zip_entry), '/');

				if ($pos_last_slash !== false) {
					$path = $dest_dir . substr(zip_entry_name($zip_entry), 0, $pos_last_slash + 1);

					if (!$fs->isDirectory($path)) {
						$fs->makeDirectory($path);
					}
				}

				if (zip_entry_open($zip, $zip_entry, 'r')) {
					$file_name = $dest_dir . zip_entry_name($zip_entry);
					if ($overwrite === true || $overwrite === false && !is_file($file_name)) {
						$fstream = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));

						if (!$fs->isDirectory($file_name)) {
							$fs->put($file_name, $fstream);
						}

						chmod($file_name, 493);
					}

					zip_entry_close($zip_entry);
				}
			}

			zip_close($zip);
		}
	}
	else {
		return false;
	}

	return true;
}

define('IN_ECS', true);
require __DIR__ . '/includes/init.php';
defined('DS') || define('DS', DIRECTORY_SEPARATOR);
defined('CACHE_PATH') || define('CACHE_PATH', ROOT_PATH . 'temp/upgrade/');
$sql = 'SELECT `value` FROM ' . $ecs->table('shop_config') . ' WHERE `code` = \'dsc_version\'';
$current_version = $db->getOne($sql, true);
$patch_url = 'http://download.shoprooo.com.au/metadata.json?v=' . date('YmdH');
$patch = patch_list($patch_url, $current_version);
$fs = new \Illuminate\Filesystem\Filesystem();

if ($_REQUEST['act'] == 'index') {
	check_authz_json('upgrade_manage');

	if (empty($patch)) {
		$last_version = $_LANG['already_new'];
	}
	else {
		$last_version = end($patch);
	}

	$smarty->assign('ur_here', $_LANG['list_link']);
	$smarty->assign('full_page', 1);
	$smarty->assign('ecs_version', $current_version);
	$smarty->assign('ecs_release', RELEASE);
	$smarty->assign('last_version', $last_version);
	$smarty->assign('is_writable', $fs->isWritable(ROOT_PATH));
	$smarty->assign('patch', $patch);
	$smarty->display('upgrade_index.dwt');
}

if ($_REQUEST['act'] == 'init') {
	check_authz_json('upgrade_manage');
	$cover = !empty($_REQUEST['cover']) ? intval($_REQUEST['cover']) : 0;

	if (empty($cover)) {
		sys_msg($_LANG['covertemplate'], 1);
	}

	if (empty($patch)) {
		sys_msg($_LANG['already_new'] . $msg, 2);
	}

	$upgrade_path = ROOT_PATH . 'temp/upgrade';

	if (!$fs->isDirectory($upgrade_path)) {
		$fs->makeDirectory($upgrade_path);
	}

	$message = upgrade($patch[0]);

	if (isset($patch[1])) {
		$url = 'upgrade.php?act=init&cover=' . $cover . '&t=' . time();
	}
	else {
		$url = 'upgrade.php?act=index';
		clear_all_files();
	}

	$links = array(
		array('text' => $patch[0] . $GLOBALS['_LANG']['upgrade_success'], 'href' => $url)
		);
	sys_msg($GLOBALS['_LANG']['upgradeing'], 2, $links);
}

?>
