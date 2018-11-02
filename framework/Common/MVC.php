<?php
	
	/**
	 * 框架启动引擎类
	 * 功能介绍
	 *1、必要文件包含
	 *2、必要功能函数初始化
	 *3、加载对应的控制器和方法
	 *
	 */
	include_once('config/configlist.php');
	foreach ($paths as $path) {
		include_once($path);
	}

	/**
	* 
	*/
	class MVC
	{
		public static $app;
		public static $validate;
		public static $controller;
		public static $method;
		private static $config;

		/**
		 * [init_app 获取模块名app]
		 * 通过获取当前url中的文件名来判断app
		 * @return [type] [description]
		 */
		private static function init_app(){
			$temp = explode('/', $_SERVER['PHP_SELF']);
			$filename = end($temp);					//得到类似：index.php
			$temp = explode('.', $filename);		//temp为数组
			self::$app = $temp[0];					//得到index
		}

		/**
		 * [init_app_common_basicClass 加载模块公共基类]
		 * @return [type] [description]
		 */
		private static function init_app_common_basicClass(){
			require_once('app/'.self::$app.'/Common/basicClass.inc.php');
		}

		/**
		 * [init_session session 初始化]
		 * @return [type] [description]
		 */
		private static function init_session(){
			session_start();
		}

		/**
		 * [redirect 访问不存在的控制器，跳转到首页/404页面]
		 * @param  [type] $url [description]
		 * @return [type]      [description]
		 */
		private static function redirect(){
			//header('Location:'.self::$app.'.php?');
			VIEW::display('tpl/'.self::$app.'/404.html');
			exit();
		}

		/**
		 * [init_view Smarty模板引擎初始化]
		 * @return [type] [description]
		 */
		private static function init_view(){
			VIEW::init(self::$config['smartyConfig']);
		}


		/**
		 * [init_db 数据库初始化]
		 * @return [type] [description]
		 */
		private static function init_db(){
			DB::init(self::$config['mysqlConfig']);
		}


		/**
		 * [init_controller 控制器初始化]
		 * @return [type] [description]
		 */
		private static function init_controller(){
			if (isset($_GET['controller'])) {
				if (in_array(trim($_GET['controller']), self::$validate[self::$app])) {	//检查该controller是否是可以访问的
					self::$controller = trim($_GET['controller']);
				}else{	
					self::redirect();//访问非法的控制器，跳转到首页/404页面
				}
			}else{
				self::$controller = 'index';
			}
		}


		/**
		 * [init_controller 方法初始化]
		 * @return [type] [description]
		 */
		private static function init_method(){
			self::$method = isset($_GET['method'])?trim($_GET['method']):'index';
		}


		/**
		 * [run 运行框架]
		 * @param  [type] $config [description]
		 * @return [type]         [description]
		 */
		public static function run($config,$validate){
			self::$config = $config;
			self::$validate = $validate;
			self::init_app();
			self::init_app_common_basicClass();
			self::init_session();
			self::init_db();
			self::init_view();
			self::init_controller();
			self::init_method();
			C(self::$app,self::$controller,self::$method);
			DB::close();	//关闭数据库链接
		}


	}


?>