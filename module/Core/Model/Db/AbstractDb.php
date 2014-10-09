<?php
namespace Core\Model\Db;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Cache\MemcacheCache;

class AbstractDb{
	protected $manager = null;
	function __construct($user, $password, $dbname, $host, $isDevMode = true, $options=array(), $driver='pdo_mysql')
	{
		$config = Setup::createConfiguration($isDevMode, null, null);
		$conn = array(
					
				'driver'   => $driver,
				'user'     => $user,
				'password' => $password,
				'dbname'   => $dbname,
				'host'	   => $host,
				'driverOptions' => $options
		);
		
		AnnotationRegistry::registerLoader('class_exists');
		$config->setMetadataDriverImpl($this->getDriver());
		
		if($isDevMode == false){
    		$memcache = new \Memcache();
    		$memcache->connect('localhost', 11211);//11211);
    		$mm = new MemcacheCache();
    		$mm->setMemcache($memcache);
    		$mm->save('cache_id', 'data');
    		$config->setMetadataCacheImpl($mm);
		}
		$this->manager = EntityManager::create($conn, $config);
	}
	protected $driver=null;
	function getDriver(){
		if(is_null($this->driver)){
			$this->driver = new AnnotationDriver(new AnnotationReader());
		}
		return $this->driver;
	}
	function addPaths($path){
		$this->getDriver()->addPaths($path);
	}
	/**
	 * passer par la pour recupÃ©rer les manager entity
	 * @return EntityManager
	 */
	function getManager()
	{
		return $this->manager;
	}
}
