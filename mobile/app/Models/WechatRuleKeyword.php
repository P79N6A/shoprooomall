<?php
//SHOPROOO商城资源
namespace app\models;

class WechatRuleKeyword extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'wechat_rule_keywords';
	public $timestamps = false;
	protected $fillable = array('rid', 'rule_keywords');
	protected $guarded = array();
}

?>
