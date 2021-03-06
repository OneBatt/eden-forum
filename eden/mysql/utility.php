<?php //-->
/*
 * This file is part of the Eden package.
 * (c) 2011-2012 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

/**
 * Generates utility query strings
 *
 * @package    Eden
 * @category   sql
 * @author     Christian Blanquera cblanquera@openovate.com
 */
class Eden_Mysql_Utility extends Eden_Sql_Query
{
	/* Constants
	-------------------------------*/
	/* Public Properties
	-------------------------------*/
	/* Protected Properties
	-------------------------------*/
	protected $_query = NULL;
	
	/* Private Properties
	-------------------------------*/
	/* Get
	-------------------------------*/
	/* Magic
	-------------------------------*/
	public static function i() {
		return self::_getMultiple(__CLASS__);
	}
	
	/* Public Methods
	-------------------------------*/
	/**
	 * Query for dropping a table
	 *
	 * @param string the name of the table
	 * @return this
	 */
	public function dropTable($table) {
		//Argument 1 must be a string
		Eden_Mysql_Error::i()->argument(1, 'string');
		
		$this->_query = 'DROP TABLE `' . $table .'`';
		return $this;
	}
	
	/**
	 * Returns the string version of the query 
	 *
	 * @return string
	 */
	public function getQuery() {
		return $this->_query.';';
	}
	
	/**
	 * Query for renaming a table
	 *
	 * @param string the name of the table
	 * @param string the new name of the table
	 * @return this
	 */
	public function renameTable($table, $name) {
		//Argument 1 must be a string, 2 must be string
		Eden_Mysql_Error::i()->argument(1, 'string')->argument(2, 'string');
		
		$this->_query = 'RENAME TABLE `' . $table . '` TO `' . $name . '`';
		return $this;
	}
	
	/**
	 * Query for showing all columns of a table
	 *
	 * @param string the name of the table
	 * @return this
	 */
	public function showColumns($table, $where = NULL) {
		//Argument 1 must be a string, 2 must be string null
		Eden_Mysql_Error::i()->argument(1, 'string')->argument(2, 'string', 'null');
		
		$where = $where ? ' WHERE '.$where : NULL;
		$this->_query = 'SHOW FULL COLUMNS FROM `' . $table .'`' . $where;
		return $this;
	}
	
	/**
	 * Query for showing all tables
	 *
	 * @param string like
	 * @return this
	 */
	public function showTables($like = NULL) {
		Eden_Mysql_Error::i()->argument(1, 'string', 'null');
		
		$like = $like ? ' LIKE '.$like : NULL;
		$this->_query = 'SHOW TABLES'.$like;
		return $this;
	}
	
	/**
	 * Query for truncating a table
	 *
	 * @param string the name of the table
	 * @return this
	 */
	public function truncate($table) {
		//Argument 1 must be a string
		Eden_Mysql_Error::i()->argument(1, 'string');
		
		$this->_query = 'TRUNCATE `' . $table .'`';
		return $this;
	}
	
	/* Protected Methods
	-------------------------------*/
	/* Private Methods
	-------------------------------*/
}