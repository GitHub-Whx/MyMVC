<?php
	
	/**
	 * 模块公共基类
	 *
	 */
	class basicClass
	{
		/**
		 * [loginInfo 登录信息]
		 * @return [type] [description]
		 */
		public function loginInfo($resultSet){
			//登录信息存储
			$_SESSION['isLogin']	= true;
			$_SESSION['accountId']	= $resultSet[0]['accountId'];
			$_SESSION['account']	= $resultSet[0]['account'];
			$_SESSION['loginNum']	= $resultSet[0]['loginNum']+1;
			//登录过期时间
			$_SESSION['expireTime'] = time()+10;
		}

		/**
		 * [checkLogin 检查是否已登录 登录是否已过期]
		 * @return [type] [description]
		 */
		public function checkLogin_null(){
			if (isset($_SESSION['isLogin'])){
				if ($_SESSION['expireTime'] < time()) {
					unset($_SESSION['isLogin']);
					unset($_SESSION['accountId']);
					unset($_SESSION['account']);
					unset($_SESSION['loginNum']);
					unset($_SESSION['expireTime']);
					//echo '<a href="admin.php?controller=login&method=logout" data-url="" class="signOut"></a>';
					header('Location:admin.php?controller=login&method=index');
					exit();
				}else{
					$_SESSION['expireTime'] = time() + 10; // 刷新时间戳
				}
			}else{
				header('Location:admin.php?controller=login&method=index');
			}
		}


		/**
		 * [checkLogin 检查是否已登录 登录是否已过期]
		 * @return [type] [description]
		 */
		public function checkLogin(){
			if (!isset($_SESSION['isLogin'])){
				header('Location:admin.php?controller=login&method=index');
			}
		}

	}
?>