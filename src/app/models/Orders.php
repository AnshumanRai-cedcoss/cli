<?php

use Phalcon\Mvc\Model;

class Orders extends Model
{
    public $order_id;
    public $customerName;
    public $address;
    public $zip;
    public $product;
    public $quantity;
     				
}