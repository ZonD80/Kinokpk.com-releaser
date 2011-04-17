<?php

/**
 * Cache Driver storing data in Memcache
 * @access public
 * @license GPL
 * @author Latik, <latkovsky@yandex.ru>
 * @version 0.5
 * @package Kinokpk.com releaser
 * @link http://dev.kinokpk.com
 */

class MemCacheDriver implements CacheDriver
{
	private $_memcache = null;

	public function __construct($options = null)
	{
		if ( $options == null ) {
			$options = array(
        			'memcache'=>array(
                		'server'=>array(
                    		'host'=>"localhost", 'port'=>11211)
			)
			);
		}
		if (!class_exists('Memcache')) throw new Exception('memcached is not installed');

		if (isset($options['memcache']) && is_array($options['memcache']))	{
			$this->_memcache = new Memcache;
			foreach ($options['memcache'] as $server) {
				if (!is_array($server) || !isset($server['host'])) {// host должен быть указан
					continue;
				}
				$server['port'] = isset($server['port']) ? (int) $server['port'] : 11211;
				$server['persistent'] = isset($server['persistent']) ? (bool) $server['persistent'] : true;
				if (!$this->_memcache->addServer($server['host'], $server['port'], $server['persistent'])) throw new Exception('cannot add memcache server, verify that memcached running on localhost:11211');
			}
		}
	}

	public function set($groupName, $identifier, $data, $ttl = 300) {
		if (!$this->_memcache->replace($this->getGroupKey($groupName).$identifier, $data, MEMCACHE_COMPRESSED, $ttl)) {
			$this->_memcache->set($this->getGroupKey($groupName).$identifier, $data, MEMCACHE_COMPRESSED, $ttl);
		}
	}

	public function get($groupName, $identifier){
		return $this->_memcache->get($this->getGroupKey($groupName).$identifier);
	}

	public function clearCache($groupName, $identifier){
		$this->_memcache->delete($this->getGroupKey($groupName).$identifier);
	}

	public function clearGroupCache($groupName){
		$this->_memcache->increment("$groupName");
	}

	public function clearAllCache(){
		$this->_memcache->flush();
	}

	public function addServer($host = localhost,$port = 11211, $weight = 10) {
		return $this->_memcache->addServer($host,$port,true,$weight);
	}

	private function getGroupKey($groupName){
		$gr_key = $this->_memcache->get("$groupName");
		if($gr_key===false) $this->_memcache->set("$groupName", rand(1, 10000));
		return $gr_key;
	}

	public function modificationTime($groupName, $identifier) {
		return time();
	}

	public function exists($groupName, $identifier){
		if ($this->_memcache->get($this->getGroupKey($groupName).$identifier)) return true;
		else return false;
	}
}

?>