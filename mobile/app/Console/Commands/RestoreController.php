<?php
//SHOPROOO商城资源
namespace App\Console\Commands;

class RestoreController extends \Illuminate\Console\Command
{
	/**
     * The name and signature of the console command.
     *
     * @var string
     */
	protected $signature = 'app:rectl';
	/**
     * The console command description.
     *
     * @var string
     */
	protected $description = 'restore all controller';

	public function handle()
	{
		$path = base_path('app/Modules');
		$files = glob($path . '/*/Controllers/*');

		foreach ($files as $file) {
			$name = basename($file, '.php');
			$content = file_get_contents($file);
			$content = str_replace(' extends', 'Controller extends', $content);
			file_put_contents($file, $content);
			rename($file, dirname($file) . '/' . $name . 'Controller.php');
		}
	}
}

?>
