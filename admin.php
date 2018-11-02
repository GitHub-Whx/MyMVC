<?php
	//单一入口
	//链接形式：localhost/index.php?（admin.php?）controller=控制器名&method=方法名
	header("Content-type: text/html; charset=utf-8");
	require_once('framework/Common/MVC.php');
	MVC::run($config,$validate);
?>