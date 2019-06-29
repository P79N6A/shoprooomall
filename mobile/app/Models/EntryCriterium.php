<?php
//SHOPROOO商城资源
namespace app\models;

class EntryCriterium extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'entry_criteria';
	public $timestamps = false;
	protected $fillable = array('parent_id', 'criteria_name', 'charge', 'standard_name', 'type', 'is_mandatory', 'option_value');
	protected $guarded = array();
}

?>
