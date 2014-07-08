<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Mailer\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class FileController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }
    public function downloadAction(){
        
        \Mailer\Imap::i()->selectFolder($this->getEvent()->getRouteMatch()->getParam('folder'));
		$formated = \Mailer\Imap::i()->getFormatedMessage($this->getEvent()->getRouteMatch()->getParam('message'));
		
		$parts = $formated->getAllParts();
		$file = $parts['file'][$this->getEvent()->getRouteMatch()->getParam('id')];
		
		if(!$file->getHeaders()->get('Content-Disposition') && !$file->getHeaders()->get('Content-Transfer-Encoding')){
		    echo nl2br($file->getContent());exit;
		}
		header('Content-Type:'.$file->getHeaders()->get('contenttype')->getFieldValue());
		$file->getHeaders()->get('Content-Disposition')&&header('Content-Disposition:'.$file->getHeaders()->get('Content-Disposition')->getFieldValue());
		$file->getHeaders()->get('Content-Transfer-Encoding')&&header('Content-Transfer-Encoding:'.$file->getHeaders()->get('Content-Transfer-Encoding')->getFieldValue());
		//header('Content-Transfer-Encoding: binary');
		echo(base64_decode($file->getContent()));
		//var_dump($file->getHeaders());
		exit;
		
		return $viewModel;
    }
    protected $data;
	public function getRequestData(){
		if(is_null($this->data)){
			$this->data = json_decode(file_get_contents("php://input"));
		}
		return $this->data;
	}
}