<?php

use Phalcon\Mvc\Controller;


class OrdersController extends Controller
{
    public function indexAction()
    {
      
       $name = Product::find(
          [
        'columns'    => 'name,tags',
        ]
    );
    $data = array();
    $res = json_decode(json_encode($name));
   
    foreach ($res as $key => $value) {
        if(strpos($value->name,$value->tags))
        {  
          array_push($data,str_replace($value->tags,"",$value->name));
        }
        else{
          array_push($data,$value->name);
        }
        
    }
    $this->view->data = $data;

    if(isset($this->request->getPost()["placeOrder"]))
    {
        $res = $this->request->getPost();
  
        $order = new Orders();
        $order->assign(
        $res, 
          [
            'customerName',
             'address',
             'zip',
            'product',
            'quantity'	
          ]
      );
      $success = $order->save();
      if($success)
    {
       $events = $this->eventsManager;
       $action = new \App\Components\MyHelper();
       $action->setEventsManager($events);
       $events->attach(
      'notifications',
       new App\Listeners\NotificationsListeners()
        ); 
       $action->settingOrder();
    }
    }

    }

    public function listAction()
    {
  
      $this->view->data = json_decode(json_encode(Orders::find(
        [
          'columns'    => '*'
        ]
      )));
    }


}