<?php

use Phalcon\Mvc\Controller;


class ProductController extends Controller
{
    public function indexAction()
    {
  
      if(isset($this->request->getPost()["addProd"]))
      {

       
          $res = $this->request->getPost();
          $product = new Product();
          
          $product->assign(
          $res, 
            [
                 'name',
                 'description',
                 'tags',
                 'price',
                 'stock',
            ]
        );
        
    
        $success = $product->save();
        if($success)
        {
           $eventsManager = $this->eventsManager;
           $action = new \App\Components\MyHelper();
           $action->setEventsManager($eventsManager);
           $eventsManager->attach(
          'notifications',
           new App\Listeners\NotificationsListeners()
            ); 
      
          $action->settingProd();
        }
      }
    }

    public function listAction()
    {
  
      $res = json_decode(json_encode(Product::find(
        [
          'columns'    => '*'
        ]
      )));
  

      $this->view->data = $res;
    }

}