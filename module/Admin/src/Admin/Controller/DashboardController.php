<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Form\NewpageForm;
use Zend\Db\TableGateway\TableGateway;
use Admin\Model\PagesTable;
use Zend\Authentication\AuthenticationService;

class DashboardController extends AbstractActionController
{

    public function indexAction()
    {
        
        if($this->getRequest()->isPost()){
            $this->createPage($this->getRequest()->getPost());
        }
        
        $pages = $this->getPagesTable()->fetchAll();
        
        $form = new NewpageForm();
        $sm = $this->getServiceLocator();
        $authService = new AuthenticationService();
        return array("user"=>$authService->getIdentity(),"form"=>$form,"pages"=>$pages);            
        
    }
    
    public function editAction()
    {
        
     $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('Dashboard');
        }
        $data = $this->getPagesTable()->getPage($id);
        print_r($data);
        exit;
        $form = new NewpageForm();
        $form->bind($data);
        $this->layout()->setTemplate('layout/page');
        return new ViewModel(array("data"=>$data,"form"=>$form));
        
    }
    
   
    private function getPagesTable()
    {
        return $this->getServiceLocator()->get('Admin\Model\PagesTable');
    }
    
    private function createPage($post){       
        
        $sm = $this->getServiceLocator();
        $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');           
        $pagesTable = new TableGateway('pages',$dbAdapter);
        $insert = array("title"=>$post->title,"name"=>$post->name,"html"=>$post->html);
        $id = $pagesTable->insert($insert);
    }
}
