<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Project\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Core\Model\Entity\PmProject;
class IndexController extends AbstractActionController{
    private $view;
    public function getView(){
        if(is_null($this->view)){
            $this->view = new ViewModel();
        }
        return $this->view;
    }
    public function indexAction(){
        return new ViewModel();
    }
    public function jsonstatusAction(){
        header('Content-Type: application/json');
        echo \Core\Model\Entity\PmProjectStatus::jsonFindAll();
        return $this->response;
    }
    public function jsonAction(){
        header('Content-Type: application/json');
        if(property_exists($this->getRequestData(), 'id')){
            $project = PmProject::findOneBy(array('id'=>$this->getRequestData()->id));
            
            $project->setLabel($this->getRequestData()->label);
            $project->setStatus(\Core\Model\Entity\PmProjectStatus::findOneBy(array('id'=>$this->getRequestData()->status->id)));
            $project->setPriority($this->getRequestData()->priority);
            $project->setLength($this->getRequestData()->length);
            $project->setPercent($this->getRequestData()->percent);
            $project->setNote($this->getRequestData()->note);
            $project->save();
            
            echo '{"success":true, "object":'.json_encode($project->getDataObject()).'}';
        }
        else{
            echo \Core\Model\Entity\PmProject::jsonFindAll();
        }
        return $this->response;
    }
    public function insertAction(){
        header('Content-Type: application/json');
        $project = new \Core\Model\Entity\PmProject();
        $project->persist();
        $project->save();
        echo '{"success":true, "id":'.$project->getId().'}';
        return $this->response;
    }
    
    protected $data;
	public function getRequestData(){
		if(is_null($this->data)){
			$this->data = json_decode(file_get_contents("php://input"));
		}
		return $this->data;
	}
}
