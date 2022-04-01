<?php

use Phalcon\Mvc\Controller;


class RoleController extends Controller
{
    public function indexAction()
    {
        if(count($this->request->getPost()) > 0)
       {
           $res = $this->request->getPost();
           $role = new Role();
           $role->assign(
            $res, 
              [
                   'jobProfile'    
              ]
          );
          $role->save();
          $rolelog= $this->request->get('bearer');
          $lang= $this->request->get('lang');

          $this->response->redirect("permission/index?bearer=".$rolelog.'&lang='.$lang);
       }
    }
  
    
}