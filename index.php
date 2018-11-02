<?php
	//单一入口
	header("Content-type: text/html; charset=utf-8");
	require_once('framework/Common/MVC.php');
	MVC::run($config,$validate);

?>