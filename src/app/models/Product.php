<?php

use Phalcon\Mvc\Model;

class Product extends Model
{
    public $id;
    public $name;
    public $description;
    public $tags;
    public $price;
    public $stock;					
		
}