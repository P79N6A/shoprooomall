<?php
//SHOPROOO商城资源
namespace App\Api\Controllers;

class AddressController
{
	public function index()
	{
		return \App\Models\UserAddress::all();
	}

	public function create()
	{
	}

	public function store()
	{
	}

	public function show($id)
	{
		return \App\Models\UserAddress::first();
	}

	public function edit()
	{
	}

	public function update()
	{
	}

	public function destroy()
	{
	}
}


?>
