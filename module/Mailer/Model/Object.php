<?php
namespace Base\Model\Cis;

use Base\Model\Component\Store\DoctrineStorage;

use Base\Model\Db\AbstractDb;
use Doctrine\ORM\EntityNotFoundException;

use Base\Model\Core\EditableGridAuto;

use Doctrine\ORM\Query\ResultSetMapping;

use Doctrine\ORM\Tools\SchemaTool;

use Doctrine\ORM\Tools\EntityGenerator;

use Doctrine\ORM\Mapping\ClassMetadataInfo;

use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;

use Authentificator\Model\User\Me;

use Doctrine\DBAL\DBALException;

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
       $class = get_called_class();
       return $class::getDb();
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
		try {
			self::getManager()->flush($this);
		}
		catch(DBALException $e){
			
			Me::i()->setMessage('Une erreur est survenue lors de la sauvegarde.'.$e->getMessage());
		}
		return $this;
	}
	/**
	 * suppression dans la base de l'objet
	 * @return false
	 */
	public function delete(){
		self::getManager()->remove($this);
		//self::getManager()->flush($this);
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
		
		//getenv('APPLICATION_ENV')=='development'  &&  error_log(var_export($orders, true));
		if($orders){
			foreach($orders as $sort=>$order){
				$query->addOrderBy($sort,$order);
			}
		}
		//error_log(var_export(self::getAssociations(), true));
		foreach(self::getAssociations() as $k=>$association){
			$query->leftJoin($alias.'.'.$k, $k);
			$query->addSelect($k); // ajout de la table dans le select sinon plein de notices
		}

        //$query->addSelect('catalog.parent');

		//getenv('APPLICATION_ENV')=='development'  &&  error_log('exec req on '.get_called_class());
		//getenv('APPLICATION_ENV')=='development'  &&  error_log($query->getQuery()->getSQL());
		
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
			//error_log(json_encode($data->getDataObject($properties)));
		}
		if(is_null($nb)){
			$nb = count($datas);
		}
		
		return json_encode(array('totalCount'=>$nb,'items'=>$datas));
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
					
					if($value && is_object($value) && is_a($value, 'Base\Model\Cis\Object') && $recursive){
                        $recursive--;
						$value = $value->getDataObject(null, $recursive);
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
		
		// repertoire dans lequel on stock les class
		if(is_null($meta)){
			$entityGenerator->generate(array(self::getMetaStructure()), $dir);
		}
		else{
			$entityGenerator->generate(array($meta), $dir);
		}
		
		//return $this;
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
				// on ne génère pas d'erreur à ce niveau pour le moment.
			}
		}
		//return $this;
	}
	public static function getAssociations(){
		return self::getMetaStructure()->getAssociationMappings();
	}
	public static function getJsonStructure(){
		$mappings = array();
		//var_dump(self::getMetaStructure()->getAssociationMappings());
		//var_dump(self::getMetaStructure()->getAssociationMapping('action'));
		//$map = self::getMetaStructure()->getAssociationMapping('action');
		//self::getMetaStructure()
		//var_dump($map['joinColumnFieldNames']['id_action']);
		//exit;
		foreach(self::getMetaStructure()->fieldMappings as $mapping){
			$mappings[] = $mapping;
		}
		/*
		foreach(self::getMetaStructure()->associationMappings as $association){
			$mappings[] = $association;
		}*/
		return json_encode($mappings);
	}
	public function handle($data){
		// si le parametre poussé en handle est une association alors, on cherche en fonction de id et on pousse la daa
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
			//error_log($property.' : '.$data[$property]);
			if(array_key_exists($property, $data)){
				$method = 'set'.ucfirst($property);
				if(method_exists($this, $method)){
					// les transformations
					if($data[$property]){
						switch($field['type']){
							case 'date':
							case 'datetime':
								$data[$property] = new \DateTime($data[$property]);
								break;
							case 'boolean':
							//	error_log('value ==== '.$data[$property]);
								
								$data[$property] = ($data[$property]=='true')?true:false;
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
		// sauvegarde
		$this->save();
		return $this;
	}
	
	public static function getEditableGrid($title, $add=true, $delete=true, $columns=null, $closable=true, $where = null, $where_string = null){
		$class = get_called_class();
		$grid = new EditableGridAuto($class, $title, $add, $delete, $columns, $closable, $where, $where_string);
		return $grid;
	}
	
	public static function getDoctrineStorage($where=null, $order =null, $filter='id', $where_string=null){
		return new DoctrineStorage(get_called_class(), $where, $order, $filter, $where_string);
	}
}