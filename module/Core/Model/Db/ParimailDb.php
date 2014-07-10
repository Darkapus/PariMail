<?php
namespace Core\Model\Db;
use Core\Model\Db\AbstractDb;
use Core\Model\Db\ParimailDb;
/**
 * classe de connexion Ã  la bdd CIS
 * @author bbaschet
 * 
 */
class ParimailDb extends AbstractDb
{
	/**
	 * pour singleton
	 * @var unknown_type
	 */
	static public $i=null;
	
	/**
	 * singleton
	 * @return CisDb
	 */
	static public function i()
	{
		if(is_null(self::$i))
		{
			self::$i = new ParimailDb();
		}
		return self::$i;
	}
	/**
	 * constructeur
	 */
	function __construct()
	{
		$isDevMode = true;
		parent::__construct('root', 'zrxipuic', 'parimail', '127.0.0.1', $isDevMode, array(1002=>'SET NAMES utf8'));
		
		$this->addPaths(array(__DIR__.'/../Entity/'));
		
		$platform = $this->getManager()->getConnection()->getDatabasePlatform();
		$platform->registerDoctrineTypeMapping('enum', 'string');
		$platform->registerDoctrineTypeMapping('set', 'string');
	}
}