<?php
//SHOPROOO商城资源
require 'include.php';
$mq = $c->notify();

while (1) {
	$data = $mq->get();
	echo $data;
	echo "\n";
	$data->ack();
}

?>
