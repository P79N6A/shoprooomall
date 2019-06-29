<?php
//SHOPROOO商城资源
namespace App\Console\Commands;

class LiteProject extends \Illuminate\Console\Command
{
	/**
     * The name and signature of the console command.
     *
     * @var string
     */
	protected $signature = 'app:lite';
	/**
     * The console command description.
     *
     * @var string
     */
	protected $description = 'release project';
	/**
     * root path.
     *
     * @var string
     */
	private $base_path = '';

	public function handle()
	{
		$this->base_path = base_path();
		$del_file = array('app/Console/Commands/CustomerService.php', 'app/Http/Chat/Controllers/Admin.php', 'app/Http/Chat/Controllers/Index.php', 'app/Http/Chat/Controllers/Login.php', 'app/Http/Chat/Models/Kefu.php', 'app/Http/Chat/Views/*', 'app/Http/Drp/*', 'app/Http/Team/*', 'app/Http/Wechat/*', 'app/Http/Touchim/*', 'app/Modules/connect/facebook.php', 'app/Modules/payment/wxpay.php', 'app/Modules/payment/paypal.php', 'app/Modules/payment/omipay.php', 'app/Extensions/WorkerEvent.php', 'app/Extensions/WxHongbao.php', 'app/Extensions/Wxapp.php', 'database/*', 'resources/electron/*', 'resources/program/*', 'resources/vuejs/*', 'resources/views/touchim/*', 'public/css/console_team.css', 'public/css/console_wechat.css', 'public/css/console_wechat_seller.css', 'public/assets/wechat/*', 'public/fonts/wechat/*', 'public/css/wechat/*', 'public/css/team.css', 'public/css/team.min.css', 'public/css/wechat.css', 'public/css/wechat.min.css', 'tests/*', '.bowerrc', '.gitattributes', '.gitignore', 'artisan', 'bower.json', 'CHANGELOG.md', 'composer.json', 'package.json', 'README.md', 'webpack.mix.js');
		$docs_file = glob($this->base_path . '/app/Http/*/Docs');

		foreach ($docs_file as $vo) {
			$this->del_dir($vo);
		}

		foreach ($del_file as $vo) {
			$this->delete($vo);
		}
	}

	private function delete($file = '')
	{
		$suffix = substr($file, -2);

		if ($suffix == '/*') {
			$this->del_dir($this->base_path . '/' . substr($file, 0, -1));
		}
		else if ($suffix == '_*') {
			$this->del_pre($this->base_path . '/' . substr($file, 0, -1));
		}
		else {
			@unlink($this->base_path . '/' . $file);
		}
	}

	private function del_dir($dir)
	{
		if (!is_dir($dir)) {
			return false;
		}

		$handle = opendir($dir);

		while (($file = readdir($handle)) !== false) {
			if (($file != '.') && ($file != '..')) {
				is_dir($dir . '/' . $file) ? $this->del_dir($dir . '/' . $file) : @unlink($dir . '/' . $file);
			}
		}

		if (readdir($handle) == false) {
			closedir($handle);
			@rmdir($dir);
		}
	}

	private function del_pre($files)
	{
		$dir = dirname($files);
		$handle = opendir($dir);

		while (($file = readdir($handle)) !== false) {
			if (($file != '.') && ($file != '..')) {
				$prefix = basename($files);
				$FP = stripos($file, $prefix);

				if ($FP === 0) {
					@unlink($dir . '/' . $file);
				}
			}
		}

		closedir($handle);
	}
}

?>
