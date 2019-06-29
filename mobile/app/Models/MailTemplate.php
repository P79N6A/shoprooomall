<?php
//SHOPROOO商城资源
namespace app\models;

class MailTemplate extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'mail_templates';
	protected $primaryKey = 'template_id';
	public $timestamps = false;
	protected $fillable = array('template_code', 'is_html', 'template_subject', 'template_content', 'last_modify', 'last_send', 'type');
	protected $guarded = array();
}

?>
