<?php

	/**
	* DB 工厂类
	* 封装数据库增删改查函数
	*/
	class DB
	{
		public static $db;
		public static $resultNum;//返回记录数量
		/**
		 * [init 初始化数据库操作类]
		 * @param  [type] $dbtype [description]
		 * @param  [type] $config [description]
		 * @param  [type] $debug  [description]
		 * @return [type]         [description]s
		 */
		public static function init($config) {
			self::$db = mysql::getInstance();
			self::$db->connect($config);
		}

		public static function close(){
			self::$db->close();
		}

		public static function query($sql){
			$query = self::$db->query($sql);
			return $query;
		}

		public static function findOne($sql){
			$query = self::$db->query($sql);
			return self::$db->findOne($query);
		}

		public static function findAll($sql){
			$query = self::$db->query($sql);
			$row = self::$db->findAll($query);			//结果集
			self::$resultNum = self::$db->resultNum;	//保存结果集数量
			return $row;
		}

		public static function insert($table,$arr){
			return self::$db->insert($table,$arr);
		}

		public static function update($table,$arr,$where){
			return self::$db->update($table,$arr,$where);
		}

		public static function delete($table,$where){
			return self::$db->delete($table,$where);
		}

		public static function buildSql($sql) {
			dump($sql);
			exit();
		}

	}



?>