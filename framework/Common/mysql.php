<?php

	/**
	
	* 单例模式数据库操作类
	
	+----------------------------------------------------------

	* 满足单例模式的必要条件----四私一公。
	
	* 1、私有的构造方法-为了防止在类外使用new关键字实例化对象。

	* 2、私有的成员属性-为了防止在类外引入这个存放对象的属性。

	* 3、私有的克隆方法-为了防止在类外通过clone成生另一个对象。

	* 4、私有的克隆方法-为了防止在类外通过clone成生另一个对象。

	* 5、公有的静态方法-为了让用户进行实例化对象的操作。
	
	+----------------------------------------------------------

	* 1、连接数据库
	* 2、增删改查操作
	* 3、报错处理
	*/

	class mysql
	{
		public $resultNum;
		private $debug;
		private $sql;
		static private $_con;
		static private $_instance;

		/**
		 * [__construct 私有构造方法，防止new对象]
		 */
		private function __construct(){

		}

		/**
		 * [__clone 私有的克隆方法]
		 * @return [type] [null]
		 */
		private function __clone() {

  		} 

  		/**
  		 * [__wakeup 私有魔术方法，防止反序列化]
  		 */
  		private function __wakeup(){

  		}

		/**
		 * [getInstance 获取单例模式对象]
		 * @return [object] [连接资源]
		 */
		static public function getInstance() {
			if (!self::$_instance instanceof self) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * 连接数据库
		 * 
		 * @param string $dbhost 主机名
		 * @param string $dbuser 用户名
		 * @param string $dbpsw  密码
		 * @param string $dbname 数据库名
		 * @param string $dbcharset 字符集/编码
		 * @return  连接   
		 **/
		public function connect($config){
			$this->debug = $config['db_debug'];
			extract($config);
			self::$_con = mysqli_connect($dbhost,$dbuser,$dbpsw);
			if(mysqli_connect_errno()){//mysqli_connect连接数据库函数
				$this->err(mysqli_connect_error(self::$_con));
				exit();
			}/*else{
				echo '数据库连接成功！';
			}*/
			if(!mysqli_select_db(self::$_con,$dbname)){//mysqli_select_db选择库的函数
				$this->err(mysqli_error(self::$_con));
				exit();
			}
			if(!mysqli_query(self::$_con,"set names ".$dbcharset)){//使用mysqli_query 设置编码  格式：mysqli_query($_con,"set names utf8")
				$this->err(mysqli_error(self::$_con));
				exit();
			}
			return self::$_con;

		}

		/**
		 * [err 报错函数]
		 * @param  [type] $errCode [description]
		 * @return [type]          [description]
		 */
		public function err($errCode){
			if ($this->debug) { //调试模式
				echo('Sorry! There is something wrong about Mysql query.<br>Your sql statement is: '.$this->sql.'.<br>ErrCode is: '.$errCode.'.');
			}else{
				//非调试模式下，将错误信息写入到错误日志中
				$this->log($errCode);
				echo('Sorry! Please try it later or contact us. Telephone Number: 134***4694!');
			}
		}

		/**
		 * [log 写入错误日志]
		 * @param  [type] $errCode [错误代码]
		 * @return [type]          [null]
		 */
		private function log($errCode){
			$dir = dirname(dirname(__FILE__)).'/logs/mysql/';//日志目录
			if (!is_dir($dir)) {		//目录是否存在
				mkdir($dir,0777);		//创建目录
			}
			$fileName = $dir.date('Y-m-d').'.log';	//日志文件名以天来区分
			$contents = date('Y-m-d H:i:s').' Mysql Error: '.$errCode.PHP_EOL;
			file_put_contents($fileName,$contents,FILE_APPEND);	//file_put_contents(文件名, 内容,写入模式)，如果文件不存在，则创建新文件
		}

		/**
		 * [close 关闭数据库连接]
		 * @return [type] [description]
		 */
		public function close(){
			mysqli_close(self::$_con);
		}

		/**
		 * [query 执行sql语句]
		 * @param  [type] $sql [sql语句]
		 * @return [type]      [返回一个 mysqli_result 对象]
		 */
		public function query($sql){
			$this->sql = $sql;
			 // echo $this->sql;
			 // exit();
			$query = mysqli_query(self::$_con,$sql);
			if(!$query){
				$this->err(mysqli_error(self::$_con));
				exit();
			}else{
				return $query;
			}
		}

		/**
		 * [findAll 返回所有结果]
		 * @param  [type] $query [description]
		 * @return [type]        [数组]
		 */
		public function findAll($query){
			$this->resultNum = mysqli_num_rows($query);	//保存结果集数量
			while($row = mysqli_fetch_assoc($query)){
				$list[] = $row;
			}
			return isset($list)?$list:'';
		}

		/**
		 * [findOne 返回单条记录]
		 * @param  [type] $query [description]
		 * @return [type]        [description]
		 */
		public function findOne($query){
			$row = mysqli_fetch_assoc($query);//mysqli_fetch_assoc() 函数从结果集中取得一行作为关联数组
			return $row;
		}

		/**
		 * [insert 添加记录]
		 * @param  [type] $table [description]
		 * @param  [type] $arr   [description]
		 * @return [type]        [插入的id号]
		 */
		public function insert($table,$arr){
			//$sql = "insert into 表名(多个字段) values(多个值)";
			foreach ($arr as $key => $value) {
				$value = mysqli_real_escape_string(self::$_con,$value);//转义在 SQL 语句中使用的字符串中的特殊字符
				$keyArr[] = '`'.$key.'`';	//键名添加``
				$valueArr[] = "'".$value."'";	//值添加''
			}
			$key = implode(',',$keyArr);	//implode函数是把数组组合成字符串 implode(分隔符，数组)
			$value = implode(',', $valueArr);
			$sql = 'INSERT INTO '.'`'.$table.'`'.'('.$key.') VALUES('.$value.')';
			$this->query($sql);
			return mysqli_insert_id(self::$_con);

		}

		/**
		 * [update 更新记录]
		 * @param  [type] $table [description]
		 * @param  [type] $arr   [description]
		 * @param  [type] $where [description]
		 * @return [type]        [返回影响条数]
		 */
		public function update($table,$arr,$where){
			//update 表名 set 字段=字段值 where ……
			foreach ($arr as $key => $value) {
				$value = mysqli_real_escape_string(self::$_con,$value);//转义在 SQL 语句中使用的字符串中的特殊字符
				$keyValue[] = '`'.$key.'`'.'='."'".$value."'";
			}
			$keyValue = implode(',', $keyValue);
			$sql = 'UPDATE '.'`'.$table.'`'.' SET '.$keyValue.' WHERE '.$where;
			$this->query($sql);
			return mysqli_affected_rows(self::$_con);
		}

		/**
		 * [delete 删除记录]
		 * @param  [type] $table [description]
		 * @param  [type] $where [description]
		 * @return [type]        [返回影响条数]
		 */
		public function delete($table,$where){
			//格式：delete from 表名 where 条件
			$sql = 'DELETE FROM '.'`'.$table.'`'.' WHERE '.$where;
			$this->query($sql);
			return mysqli_affected_rows(self::$_con);
		}

	}

?>