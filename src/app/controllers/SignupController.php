<?php

use Phalcon\Mvc\Controller;


class SignupController extends Controller
{

    public function indexAction()
    {
        
    }

    public function addAction()
    {  
        $var2 = new Role();
        $result = $var2->find();
        $result = json_decode(json_encode($result));
        $val = array();
        foreach ($result as $key => $value) {
            array_push($val, $value->jobProfile);
          
        }
       $this->view->data = $val;
       if(count($this->request->getPost()))
       {
           
           $res = $this->request->getPost();
           if($res["jobProfile"] != null)
           {
               $var = new SecureController();
               $var->createTokenAction($res["jobProfile"],$res["name"],$res["email"]);
           } 
       }
    }
  
}