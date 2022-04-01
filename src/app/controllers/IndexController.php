<?php

use Phalcon\Mvc\Controller;


class IndexController extends Controller
{
    public function indexAction()
    {  
        
        if(count($this->request->getPost()) > 0)
        {
           $res = $this->request->getPost();

           header("location:?lang=".$res['language']."&bearer=".$this->request->get('bearer'));
        } 
     }

    public function fpageAction()
    {
        $text = $this->locale->_('hello world');
        $this->view->hello = $text; 
    }
  
}