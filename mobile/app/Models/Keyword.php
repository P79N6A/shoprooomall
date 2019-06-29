<?php
//SHOPROOO商城资源
namespace app\models;

class Keyword extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'keywords';
	public $timestamps = false;
	protected $fillable = array('date', 'searchengine', 'keyword', 'count');
	protected $guarded = array();
}

?>
