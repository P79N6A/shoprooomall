<?php
//SHOPROOO商城资源
namespace app\models;

class Cron extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'crons';
	protected $primaryKey = 'cron_id';
	public $timestamps = false;
	protected $fillable = array('cron_code', 'cron_name', 'cron_desc', 'cron_order', 'cron_config', 'thistime', 'nextime', 'day', 'week', 'hour', 'minute', 'enable', 'run_once', 'allow_ip', 'alow_files');
	protected $guarded = array();
}

?>
