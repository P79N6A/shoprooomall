<?php
//SHOPROOO商城资源
namespace App\Http\Controllers;

class ExampleController extends Controller
{
	public function __construct()
	{
	}

	public function index()
	{
		return array('key' => 'example api.');
	}
}

?>
