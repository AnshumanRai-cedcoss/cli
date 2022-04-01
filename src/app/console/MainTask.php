<?php 
namespace App\Console;

use Phalcon\Cli\Task;

class MainTask extends Task 
{
    public function mainAction()
    {
       echo "Main Task and Main action here";
    }
}