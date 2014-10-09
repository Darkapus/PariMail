<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Shower\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
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
    public function imagesAction(){
        header('Content-Type: application/json');
        $files = scandir('/var/www/Waste/public/img/shoot');
        
        echo json_encode($files);
        exit;
    }
    public function shootAction(){
        $files = scandir('/var/www/Waste/public/img/shoot');
        header('Content-type:image/jpg');
        $fullpath = '/var/www/Waste/public/img/shoot/'.$files[$this->getEvent()->getRouteMatch()->getParam('id')];
        
        echo readfile($fullpath);
        
        exit;
    }
    protected $data;
	public function getRequestData(){
		if(is_null($this->data)){
			$this->data = json_decode(file_get_contents("php://input"));
		}
		return $this->data;
	}
}
