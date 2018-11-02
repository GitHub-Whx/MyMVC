<?php

	/**
	 * DataBase 、Smarty config
	 */
	
	$config = array(
		'smartyConfig'	=>	array(
			'type'=>'Smarty','caching' => false, 'left_delimiter' => '{',  'right_delimiter' => '}',  'template_dir' => 'tpl',  'compile_dir' => 'tpl_c', 'cache_dir' => 'cache/cache_c'
		),
		'mysqlConfig'		=>	array(
			'type' => 'mysql','dbhost' => 'localhost', 'dbuser'=>'root', 'dbpsw' => 'whx201111', 'dbname' => 'blog', 'dbcharset' => 'utf8','db_debug'=>false
		)
	);

?>