<?php

	/**
	* VIEW 工厂类
	*/
	class VIEW
	{
		
		public static $view;

		/**
		 * [init 初始化smarty]
		 * @param  [type] $viewtype [description]
		 * @param  [type] $config   [description]
		 * @return [type]           [description]
		 */
		public static function init($config){
			self::$view = new $config['type']();
			/*
			$smarty = new Smarty();	
			$smarty->left_delimiter = $config['left_delimiter'];
			$smarty->right_delimiter = $config['right_delimiter'];
			$smarty->template_dir = $config['template_dir'];
			$smarty->compile_dir = $config['compile_dir'];
			$smarty->cache_dir = $config['cache_dir'];
			$smarty->caching = $config['caching'];
			*/
			foreach ($config as $key => $value) {
				if ($key != 'type') {		//忽略这个配置 type=Smarty 否则会出错
					self::$view->$key = $value;
				}
				
			}

		}

		/**
		 * [assign 优化smarty内置的assign函数，支持数组赋值]
		 * @param  [type] $data [description]
		 * @return [type]       [description]
		 */
		public static function assign($data){
			foreach ($data as $key => $value) {
				self::$view->assign($key,$value);
			}
		}


		/**
		 * [display 优化smarty内置的display函数]
		 * @param  [type] $template [description]
		 * @return [type]           [description]
		 */
		public static function display($template){
			self::$view->display($template);
		}

	}




?>