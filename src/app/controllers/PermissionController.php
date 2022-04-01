<?php

use Phalcon\Mvc\Controller;


class PermissionController extends Controller
{
    public function indexAction()
    {
        $var = new \App\Components\MyControllers();
        $resultC = $var->getcontrol();
        $var2 = new Role();
        $result = $var2->find();
        $result = json_decode(json_encode($result));
        $val = array();
        foreach ($result as $key => $value) {
            array_push($val, $value->jobProfile);
        }

        $action = array();
        foreach ($resultC as $k => $v) {
            foreach ($v as $m) {

                array_push($action, $m);
            }
        }
        $action = array_unique($action);


        $this->view->data = $val;
        $this->view->resultC = $resultC;
        $this->view->action = $action;


        if (count($this->request->getPost()) > 0) {
            $res = $this->request->getPost();

            $flag = 0;
            foreach ($resultC as $key => $value) {
                if ($key == $res["controller"]) {
                    foreach ($value as $m) {
                        if ($m == $res["action"]) {
                            $flag = 1;
                            break;
                        }
                    }
                }
            }
            if ($flag == 0) {
                $this->view->error = "No such combination of controller and action!Try something else";
            } else {
                if (isset($res["access"])) {
                    $var = new Access();
                    $res["controller"] = strtolower(str_replace("Controller", "", $res["controller"]));
                    $res["action"] = strtolower(str_replace("Action", "", $res["action"]));
                    $var->assign(
                        $res,
                        [
                            'jobProfile',
                            'controller',
                            'action',
                            'access'
                        ]
                    );
                    $ucc = $var->save();
                    die($ucc);
                }
            }
        }
    }
}
