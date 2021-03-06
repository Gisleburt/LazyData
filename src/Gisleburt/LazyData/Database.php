<?php

	namespace Gisleburt\LazyData;

	/**
	 * A simple way to grab a database handler without caring if it exists.
	 * @author Daniel Mason
	 * @copyright DanielMason.com, 2012
	 * @version 1.0
	 * @package LazyData
	 */
	
	class Database {
		
		/**
		 * An array of PDO connections to mysql databases
		 * @var PDO[]
		 */
		protected static $mysqlConnections = array();
		
		/**
		 * Options for use in PDO
		 * @var array
		 */
		public static $options;
		
		public static function getMysqlConncetion($database, $username, $password, $schema = null) {
			
			// Create an key for use in the $connections array
			$dbKey = $database;
			if($schema)
				$dbKey.=".$schema";
			
			// If the key doesn't exist, or doesn't work, create it
			if(!isset(self::$mysqlConnections[$dbKey][$username]) || self::$mysqlConnections[$dbKey][$username]) {
				$dsn = "mysql:host=$database";
				if($schema)
					$dsn.=";dbname=$schema";
				self::$mysqlConnections[$database][$username] = new \PDO($dsn, $username, $password, self::$options);
			}
			return self::$mysqlConnections[$database][$username];
			
		}

		public static function mySqlTypeToPhpType($type) {
			$simpleType = self::simpleMySqlType($type);

			switch($simpleType) {

				case 'BIT':
				case 'INT':
				case 'TINYINT':
				case 'SMALLINT':
				case 'MEDIUMINT':
				case 'INTEGER':
					return 'integer';
					break;
				case 'BIGINT':
					return 'long';
					break;
				case 'DECIMAL':
				case 'DEC':
				case 'FLOAT':
					return 'float';
					break;
				case 'DOUBLE':
					return 'double';
					break;
			}

			return 'string';

		}

		public static function mySqlTypeToPdoType($type) {

			$simpleType = self::simpleMySqlType($type);

			switch($simpleType) {

				case 'BIT':
				case 'INT':
				case 'TINYINT':
				case 'SMALLINT':
				case 'MEDIUMINT':
				case 'INTEGER':
				case 'BIGINT':
					return \PDO::PARAM_INT;
					break;
				case 'TINYBLOB':
				case 'MEDIUMBLOB':
				case 'BLOB':
				case 'LONGBLOB':
				case 'TINYTEXT':
				case 'MEDIUMTEXT':
				case 'TEXT':
				case 'LONGTEXT':
					return \PDO::PARAM_LOB;
					break;
				case 'DECIMAL':
				case 'DEC':
				case 'FLOAT':
				case 'DOUBLE':
					return \PDO::PARAM_STR;
			}
			return \PDO::PARAM_STR;

		}

		public static function simpleMySqlType($type) {
			return strtoupper(preg_replace('/[\ \(\[].*/', '', $type));
		}
		
		/**
		 * This class can not be instantiated
		 */
		private function __construct() {}
		
	}