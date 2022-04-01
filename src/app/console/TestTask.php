<?php 
namespace App\Console;

use DateTimeImmutable;
use Phalcon\Cli\Task;
use Settings;
use Product;
use Orders;

class TestTask extends Task 
{
    public function mainAction()
    {
       echo "Test Task and Main action here";
    }

    public function createTokenAction($role)
    {
       $ab = new \App\Controllers\SecureController();
       $var = $ab->createTokenAction($role);
       echo $var."<- This is the token ";
    }

    public function removeLogAction()
    {
       unlink(BASE_PATH.'/storage/log/main.log');
       echo "Log files deleted";
    }

    public function removeCacheAction()
    {
       unlink(APP_PATH.'/security/acl.cache');
       echo "Cache files deleted";
    }
    public function defaultAction($price,$stock)
    {
        $sett = new Settings();
        $res = $sett->findFirst(1);
            $res->defaultPrice = $price;
            $res->defaultStock = $stock;
            $succ = $res->save();
            echo $succ;
    }
    public function prodAction()
    {
        $sett = new Product();
        $var = 10;
        $res = $sett->find(
                [
                    'columns'    => 'name',
                    'conditions' => 'stock+0 <= ?1  ',
                    'bind'       => [
                        1 => $var,
                    ]
                    ]
        );
        print_r(json_encode($res));
    }

    public function todayOrderAction()
    {
        $sett = new Orders();
        $date = new DateTimeImmutable();
        date_default_timezone_set('Asia/Kolkata');
        $date = date('y-m-d');
        $res = $sett->find(
                [
                    'columns'    => 'name',
                    'conditions' => 'date = ?1  ',
                    'bind'       => [
                        1 => $date,
                    ]
                    ]
        );
        print_r(json_encode($res));
    }
}