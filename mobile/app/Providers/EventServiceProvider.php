<?php
//SHOPROOO商城资源
namespace App\Providers;

class EventServiceProvider extends \Laravel\Lumen\Providers\EventServiceProvider
{
	/**
     * The event listener mappings for the application.
     *
     * @var array
     */
	protected $listen = array(
		'App\\Events\\ExampleEvent' => array('App\\Listeners\\EventListener')
		);
}

?>
