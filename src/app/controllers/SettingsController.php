<?php

use Phalcon\Mvc\Controller;


class SettingsController extends Controller
{
    public function indexAction()
    {
        $sett = new Settings();
        $this->view->data = $sett->find();
    }

    public function addAction()
    {
        if(isset($this->request->getPost()["addSett"]))
        {
            $data = $this->request->getPost();
            $sett = new Settings();
            $res = $sett->findFirst(1);
            $res->defaultZip = $data["defaultZip"];
            $res->defaultPrice = $data["defaultPrice"];
            $res->defaultStock = $data["defaultStock"];
            $res->title = $data["product"];
            $succ = $res->save();


            //updating order 
            $product = new Orders();
            $res = $product->find();
            $var=$res->getlast();
            $id = $var->order_id;
            $res = $product->findFirst($id);
            if($res->zip == "")
            {
                $res->zip = $data["defaultZip"];
                $res->save();
            }
            //updating order end

            //update product
             $product = new Product();
             $res = $product->find();
             $var=$res->getlast();
             $id = $var->id;
             $res = $product->findFirst($id);
             if($data["product"] == "withTag")
             {
                 $res->name = $res->name.$res->tags;
             }
             if($res->price == "")
             {
                $res->price = $data["defaultPrice"];
             }
             if($res->stock == "")
             {
                $res->stock = $data["defaultStock"];
             }
             $success = $res->save();
             $role= $this->request->get('bearer');
             $lang= $this->request->get('lang');

                 $this->response->redirect("index?bearer=".$role."&lang=".$lang);
            //product updating ended
        }
    }

}