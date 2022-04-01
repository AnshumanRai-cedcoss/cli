<?php

namespace App\Components;

use Phalcon\Di\Injectable;
use Phalcon\Events\ManagerInterface;


class MyHelper extends Injectable {
    protected $eventsManager;
    
    public function getEventsManager()
    {
        return $this->eventsManager;
    }

    public function setEventsManager(ManagerInterface $eventsManager)
    {
        $this->eventsManager = $eventsManager;
    }
    public function settingProd()
    {   
        $this->eventsManager->fire('notifications:productSett', $this);
    }
    public function settingOrder()
    {   
        $this->eventsManager->fire('notifications:orderSett', $this);
    }
}