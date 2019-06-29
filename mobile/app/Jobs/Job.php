<?php
//SHOPROOO商城资源
namespace App\Jobs;

abstract class Job implements \Illuminate\Contracts\Queue\ShouldQueue
{
	use \Illuminate\Queue\InteractsWithQueue, \Illuminate\Bus\Queueable, \Illuminate\Queue\SerializesModels;
}

?>
