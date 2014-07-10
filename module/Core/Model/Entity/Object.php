<?php
namespace Core\Model\Entity;


use Doctrine\ORM\EntityNotFoundException;

use Doctrine\ORM\Query\ResultSetMapping;

use Doctrine\ORM\Tools\SchemaTool;

use Doctrine\ORM\Tools\EntityGenerator;

use Doctrine\ORM\Mapping\ClassMetadataInfo;

use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;


/**
 * etendre de cet objet pour avoir un panel de fonctions utiles pour les objets doctrine 
 * les anciens objets doctrine cis doivent hériter de celle ci
 * pour les nouveaux, plus structuré, hériter de ObjectAdvanced, il défini des champs par défaut de base
 * @author bbaschet
 */
abstract class Object
{
    /**
     * @return AbstractDb
     */
    public static function getDb(){
       return \Core\Model\Db\ParimailDb::i();
	}
	public static function getManager(){
		return self::getDb()->getManager();
	}
	/**
	 * sauvegarde en base
	 * @return Object
	 */
	public function save(){
		// sauvegarde
		self::getManager()->flush($this);
		
		return $this;
	}
	/**
	 * suppression dans la base de l'objet
	 * @return false
	 */
	public function delete(){
		self::getManager()->remove($this);
		self::getManager()->flush();
		
		return false;
	}
	/**
	 * Declaration en objet persistant, l'objet est géré par doctrine
	 * @return Object
	 */
	public function persist(){
		self::getManager()->persist($this);
		return $this;
	}
	
	
	
	/**
	 * retourne toutes les données de la table
	 * @param array $orderBy
	 * @param integer $limit
	 * @param integer $offset
	 * @return array:
	 */
	public static function findAll($orderBy = null, $limit = null, $offset = null){
		return self::findBy(array(), $orderBy, $limit,$offset);
	}
	/**
	 * retourne toutes les données selon la clause where
	 * @param array $array clause where
	 * @param array $orderBy
	 * @param integer $limit
	 * @param integer $offset
	 * @return array:
	 */
	public static function findBy($array, $orderBy = null, $limit = null, $offset = null){
		return self::getManager()->getRepository(get_called_class())->findBy($array, $orderBy, $limit, $offset);
	}
	/**
	 * Query Builder
	 * @param array $where
	 * @param string $alias
	 * @param array $orders
	 * @param integer $limit
	 * @param integer $offset
	 */
	public static function makeQuery($where, $alias='u', $orders = null, $limit = null, $offset = null){
		
		$query = self::createQuery($where,$alias, $orders);
		$limit	&& $query->setMaxResults($limit);
		$offset	&& $query->setFirstResult($offset);
		return $query->getQuery();
	}
	
	public static function createQuery($where, $alias='u', $orders = null){
		$query = self::getManager()->getRepository(get_called_class())->createQueryBuilder($alias);
		$where  && $query->andWhere($where);
		
		if($orders){
			foreach($orders as $sort=>$order){
				$query->addOrderBy($sort,$order);
			}
		}
		foreach(self::getAssociations() as $k=>$association){
			$query->leftJoin($alias.'.'.$k, $k);
			$query->addSelect($k);
		}

        
		return $query;
	}
	/**
	 * retourne l'enregistrement cible
	 * @param array $array
	 * @param array $orderBy
	 */
	public static function findOneBy($array, $orderBy = null){
		return self::getManager()->getRepository(get_called_class())->findOneBy($array, $orderBy);
	}
	/**
	 * retourne toutes les données de la table en json
	 * @param array $orderBy
	 * @param integer $limit
	 * @param integer $offset
	 * @return array:
	 */
	public  static function jsonFindAll($orderBy = null, $limit = null, $offset = null){
		return self::jsonFindBy(array(), $orderBy, $limit,$offset);
	}
	/**
	 * retourne toutes les données selon la clause where en json
	 * @param array $array clause where
	 * @param array $orderBy
	 * @param integer $limit
	 * @param integer $offset
	 * @return array:
	 */
	public static function jsonFindBy($array, $orderBy = null, $limit = null, $offset = null){
		
		return self::makeJsonFor(self::findBy($array, $orderBy, $limit, $offset));
	}
	/**
	 * construction du format json pour la datas en param
	 * @param array $datas
	 * @return string
	 */
	public static function makeJsonFor($datas, array $properties = null, $nb=null, $recursive=1){
		$properties = is_null($properties)?self::getProperties():$properties;
		
		foreach($datas as $k=>$data){
			$datas[$k] = $data->getDataObject($properties, $recursive);
		}
		if(is_null($nb)){
			$nb = count($datas);
		}
		return json_encode($datas);
	}
	/**
	 * return les propriété d'un objet
	 * @return array
	 */
	public static function getProperties(){
		$class = get_called_class() ;
		$reflexion = new \ReflectionClass(new $class());
		return $reflexion->getProperties();
	}
	/**
	 * retourne un objet avec les valeurs selon les prop définies
	 * @param array $properties
	 * @return \stdClass
	 */
	public function getDataObject($properties=null, $recursive=1){
		$class = get_called_class();
		
		if(is_null($properties)) $properties = $class::getProperties(); 
		$object = new \stdClass();
		
		foreach($properties as $propertie){
			$prop = $propertie->name;
			
			$method = 'get'.ucfirst($prop);
			if(method_exists($this, $method)){
				
				$value = $this->$method();
				if(is_string($value)){
					$value = html_entity_decode($value);
				}
				elseif(is_object($value) && method_exists($value, 'format')){
					$value = $value->format('Y-m-d H:i:s');
				}
				try{
					
					if($value && is_object($value) && is_a($value, 'Core\Model\Entity\Object') && $recursive){
						$value = $value->getDataObject(null, $recursive-1);
					}
				}
				catch(EntityNotFoundException $e){ }
				
				if(!is_resource($value)){
					$object->$prop = $value;
				}
			}
		}
		return $object;
	}
	/**
	 * @return ClassMetadataInfo
	 */
	public static function getMetaStructure(){
		return self::getManager()->getMetadataFactory()->getMetadataFor(get_called_class());
	}
	/**
	 * @return ClassMetadataBuilder
	 */
	public static function getBuilder(){
		return new ClassMetadataBuilder(self::getMetaStructure());
	}
	public static function generateModel($dir = '/tmp', $extend = 'Base\Model\Cis\Object', $meta = null){
		$entityGenerator = new EntityGenerator();
		$entityGenerator->setClassToExtend($extend);
		$entityGenerator->setGenerateAnnotations(true);
		$entityGenerator->setGenerateStubMethods(true);
		$entityGenerator->setRegenerateEntityIfExists(false);
		$entityGenerator->setUpdateEntityIfExists(true);
		$entityGenerator->setFieldVisibility(EntityGenerator::FIELD_VISIBLE_PROTECTED);
		
		if(is_null($meta)){
			$entityGenerator->generate(array(self::getMetaStructure()), $dir);
		}
		else{
			$entityGenerator->generate(array($meta), $dir);
		}
		
		return $this;
	} 
	public static function getSqlDiff(){
		$schema = new SchemaTool(self::getManager());
		$sqls = array();
		foreach($schema->getUpdateSchemaSql(array(self::getMetaStructure())) as $sql){
			if(strpos($sql,'DROP TABLE')===false){
				$sqls[] = $sql;
			}
		}
		return $sqls;
	}
	public static function doSqlDiff(){
		foreach(self::getSqlDiff() as $sql){
			try{
				$rsm = new ResultSetMapping();
				$res = self::getManager()->createNativeQuery($sql, $rsm);
				$res->execute();
			}
			catch(\Exception $e){
				
			}
		}
		
	}
	public static function getAssociations(){
		return self::getMetaStructure()->getAssociationMappings();
	}
	public static function getJsonStructure(){
		$mappings = array();
		foreach(self::getMetaStructure()->fieldMappings as $mapping){
			$mappings[] = $mapping;
		}
		return json_encode($mappings);
	}
	public function handle($data){
		foreach($this->getAssociations() as $property=>$association){
			if(array_key_exists($property, $data)){
				$method = 'set'.ucfirst($property);
				if(method_exists($this, $method)){
					$class = $association['targetEntity'];
					if($data[$property]){
						$object = $class::findOneBy(array('id'=>$data[$property]));
					}
					else{
						$object = null;
					}
					$this->$method($object);
				}
			}
		}
		foreach($this->getMetaStructure()->fieldMappings as  $field){
			$property = $field['fieldName'];
			if(array_key_exists($property, $data)){
				$method = 'set'.ucfirst($property);
				if(method_exists($this, $method)){
					if($data[$property]){
						switch($field['type']){
							case 'date':
							case 'datetime':
								$data[$property] = new \DateTime($data[$property]);
								break;
							case 'boolean':
								$data[$property] = ($data[$property]=='true')?true:false;
								break;
						}
					}
					else{ 
						switch($field['type']){
							case 'date':
							case 'datetime':
							case 'boolean':
								$data[$property] = null;
								break;
							
								
								 	
						}
						
					}
					$this->$method($data[$property]);
				}
				elseif(property_exists($this, $property)){
					$this->$property = $data[$property];
				}
			}
		}
		$this->save();
		return $this;
	}
	
}