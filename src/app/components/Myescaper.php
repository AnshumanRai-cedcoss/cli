<?php 
namespace App\Components;

use Phalcon\Escaper;

class Myescaper 
{
    public function sanatize($t)
    {
       $escaper = new Escaper();
       return  $escaper->escapeHtml($t) ;
    }
}