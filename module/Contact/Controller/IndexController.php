<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Contact\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\ORM\Tools\EntityGenerator;
use Zend\Http\Client;

class IndexController extends AbstractActionController
{
    private $view;
    public function getView(){
        if(is_null($this->view)){
            $this->view = new ViewModel();
        }
        return $this->view;
    }
    public function indexAction()
    {
        
        $viewModel  = new ViewModel();
		
		return $viewModel;
    }
    public function searchAction(){
        header('Content-Type: application/json');
        
        $url = "http://cloud.baschet.fr/index.php/search/ajax/search.php?query=".$this->getRequestData()->query;
        
        $client = new Client($url);
        
        // Performing a POST request
        $client->setAuth('benjamin@baschet.fr', 'zrxipuic');
        
        $response = $client->send();
        echo $response->getContent();
        return $this->response;
    }
    public function jsonAction(){
        header('Content-Type: application/json');
        if(property_exists($this->getRequestData(), 'id')){
            $contact = \Core\Model\Entity\PmContact::findOneBy(array('id'=>$this->getRequestData()->id));
            $contact->setEmail($this->getRequestData()->email);
            $contact->setFirstname($this->getRequestData()->firstname);
            $contact->setLastname($this->getRequestData()->lastname);
            $contact->setFullname($this->getRequestData()->fullname);
            $contact->save();
            echo '{"success":true}';
        }
        else{
            echo \Core\Model\Entity\PmContact::jsonFindAll();
        }
        return $this->response;
    }
    public function insertAction(){
        $this->getView()->setTerminal(true);
        preg_match("/[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]{2,}[.][a-zA-Z]{2,4}/", $this->getRequestData()->data, $output_array);
        if($output_array){
            $email = $output_array[0];
            if(is_null(\Core\Model\Entity\PmContact::findOneBy(array('email'=>$email)))){
                $contact = new  \Core\Model\Entity\PmContact();
                $contact->setEmail($email);
                $contact->persist();
                $contact->save();
            }
            
        }
        return $this->getView();
    }
    protected $data;
	public function getRequestData(){
		if(is_null($this->data)){
			$this->data = json_decode(file_get_contents("php://input"));
		}
		return $this->data;
	}
    public function createmodelAction(){
        // nouveau driver pour ne pas avoir de conflit de lecture
		$driver = new \Doctrine\ORM\Mapping\Driver\DatabaseDriver(\Core\Model\Db\ParimailDb::i()->getManager()->getConnection()->getSchemaManager());
		// definition du namespace
		$driver->setNamespace('Core\\Model\\Entity\\');
		
		\Core\Model\Db\ParimailDb::i()->getManager()->getConfiguration()->setMetadataDriverImpl($driver);
		$cmf = new \Doctrine\ORM\Tools\DisconnectedClassMetadataFactory();
		
		$cmf->setEntityManager(\Core\Model\Db\ParimailDb::i()->getManager());
		
		$metadata = $cmf->getAllMetadata();
		
		foreach($metadata as $meta){
			$meta->setInheritanceType(ClassMetadataInfo::INHERITANCE_TYPE_NONE);
		}
		$entityGenerator = new \Doctrine\ORM\Tools\EntityGenerator();
		
		$entityGenerator->setGenerateAnnotations(true);
		$entityGenerator->setGenerateStubMethods(true);
		$entityGenerator->setRegenerateEntityIfExists(true);
		
		$entityGenerator->setClassToExtend('Core\Model\Entity\Object');
		$entityGenerator->setFieldVisibility(EntityGenerator::FIELD_VISIBLE_PROTECTED);
		// repertoire dans lequel on stock les class
        echo ('try to generate to : '.__DIR__.'/../..'.'<br>');
		$entityGenerator->generate($metadata, __DIR__.'/../..');
		return $this->response;
    }
}
