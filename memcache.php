<?php
/**
 * Default class to use cuztomise MYSQL - NOSQL
 * using memcached library.
 * @author Darelle Luntao darelleluntao@gmail.com
 */
Class Memcache_Library {
	/* Constants
	-------------------------------*/
	/* Public Properties
	-------------------------------*/
	/* Protected Properties
	-------------------------------*/
	protected $_memcached;
	protected $_isConnected = false;
	/* Private Properties
	-------------------------------*/
	/* Magic
	-------------------------------*/
	/* Public Methods
	-------------------------------*/
	public function __construct($config = null) {
		$this->connect($config);

		// TODO: make a seperate connection method
		mysql_connect('localhost', 'root', 'root') or die(mysql_error());
		mysql_select_db('test') or die(mysql_error());
	}
	/*
	 * get specific key to database using
	 * memcache plugin.
	 * @param string $container
	 * @param string $key
	 */
	public function getKey($container, $key) {
		return $this->_memcached->get('@@'.$container.'.'.$key);
	}

	public function getKeys($limit = 10000) {
	    $keysFound = array();
	    $memcache = $this->_memcached;
	    // TODO: reseach how to get all records from DB
	    // $this->_memcached->getAllKeys(); // NOT WORKING.
	    return $keysFound;
	}
	/* set new record to the default container
	 * if not exists insert else update the record
	 * using key. cloumns are seperated by |
	 */
	public function setKey($key, $data) {
	    $this->_memcached->set($key, $data);
	    return $this;
	}
	/* set new record to the default container
	 * if record exists it will return false
	 * @param key string $key
	 * @param data to store
	 */
	public function addKey($key, $data) {
	    $this->_memcached->add($key, $data);
	    return $this;
	}
	/*
	 * Delete a record based on key
	 * @param $key string
	 */
	public function deleteKey($key) {
	    $this->_memcached->delete($key);
	    return $this;
	}
	/*
	 * do a raw mysql query for fetching records
	 * using memcached plugin. Nosql thing
	 */
	public function query($query) {
		return $this->_memcached->get($query);
	}

	public function q($query) {
		return mysql_query($query);
	}

	public function output($var) {
		echo '<pre>'.print_r($var, true).'</pre>';
		return $this;
	}

	public function flush() {
		if (!$this->_isConnected)
			return false;
		if ($this->_memcached->flush())
			return true;
		return false;
	}

	/* Protected Methods
	-------------------------------*/
	/*
	 * connection to memcache
	 * @param array settings
	 */
	protected function connect($config) {
		// set the Memcache plugin
		$this->_memcached = new Memcached;

		$this->_memcached->addServer($config['host'], $config['port'])
			or die('Could not connect to server');

		$this->_isConnected = true;
	}

	protected function close() {
		if (!$this->_isConnected)
			return false;
		return $this->_memcached->close();
	}

}