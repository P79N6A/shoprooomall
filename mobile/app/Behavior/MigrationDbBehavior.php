<?php
//SHOPROOO商城资源
namespace app\behavior;

class MigrationDbBehavior
{
	private $model;
	private $fs;
	private $upgrade_file = 'storage/upgrade.php';
	private $migrate_path = 'database/migrations/';
	private $migrate_version = '.version';
	private $migration_files = array();

	public function run()
	{
		$this->upgrade_file = ROOT_PATH . $this->upgrade_file;

		if (is_file($this->upgrade_file)) {
			$this->model = new \app\classes\Mysql();
			$this->fs = new \Symfony\Component\Filesystem\Filesystem();
			$this->migration_files = glob(ROOT_PATH . $this->migrate_path . 'migrate-*.sql');

			foreach ($this->migration_files as $vo) {
				if (substr(basename($vo), 0, 12) == 'migrate-2016') {
					$this->fs->remove($vo);
				}
			}

			$migrate = $this->model->table('shop_config')->where(array('code' => 'migrate_version'))->find();

			if (substr($migrate['value'], 0, 4) == '2016') {
				$data['value'] = strtotime($migrate['value']);
				$this->model->table('shop_config')->where(array('code' => 'migrate_version'))->save($data);
			}

			$migration_hash = array();

			foreach ($this->migration_files as $vo) {
				$migration_hash[] = hash_file('md5', $vo);
			}

			$app_db_list = glob(BASE_PATH . 'http/*/database/*.sql');

			foreach ($app_db_list as $key => $file) {
				if (stripos($file, 'http/wechat/database/db.sql') !== false) {
					$wechat = $app_db_list[$key];
					unset($app_db_list[$key]);
					array_unshift($app_db_list, $wechat);
				}
			}

			foreach ($app_db_list as $key => $original) {
				$hash = hash_file('md5', $original);

				if (!in_array($hash, $migration_hash)) {
					$migration = ROOT_PATH . $this->migrate_path . 'migrate-' . time() . $key . '.sql';
					$migrate_path = dirname($migration);

					if (!is_dir($migrate_path)) {
						if (!mkdir($migrate_path, 511, true)) {
							throw new \Exception('Can not create dir \'' . $migrate_path . '\'', 500);
						}
					}

					if (!is_writable($migrate_path)) {
						chmod($migrate_path, 511);
					}

					if (is_file($original)) {
						$this->fs->copy($original, $migration);
					}
				}
			}

			$this->migrations();
			$this->fs->remove($this->upgrade_file);
		}
	}

	private function migrations()
	{
		$result = $this->model->table('shop_config')->where(array('code' => 'migrate_version'))->getField('value');

		if (is_null($result)) {
			$migration_version = ROOT_PATH . $this->migrate_path . $this->migrate_version;

			if (file_exists($migration_version)) {
				$version = floatval(file_get_contents($migration_version));
			}
			else {
				$version = 0;
			}

			$data = array('parent_id' => 9, 'code' => 'migrate_version', 'type' => 'hidden', 'value' => $version, 'sort_order' => 1);
			$result = $this->model->table('shop_config')->add($data);
			if ($result && file_exists($migration_version)) {
				$this->fs->remove($migration_version);
			}
		}
		else {
			$version = $result;
		}

		asort($this->migration_files);

		foreach ($this->migration_files as $file) {
			$current_version = $this->getVersionFromFile($file);

			if ($current_version <= $version) {
				continue;
			}

			$res = \ectouch\Install::mysql($file, '{pre}', config('DB_PREFIX'));

			if (is_array($res)) {
				foreach ($res as $sql) {
					$this->model->execute($sql);
				}
			}

			$data = array('value' => $current_version);
			$this->model->table('shop_config')->where(array('code' => 'migrate_version'))->save($data);
		}
	}

	private function getVersionFromFile($file)
	{
		$filename = basename($file, '.sql');
		return floatval(substr($filename, strlen('migrate-')));
	}
}


?>
