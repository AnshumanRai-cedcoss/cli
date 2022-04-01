<?php
 namespace App\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Acl\Adapter\Memory;
use Phalcon\Security\JWT\Builder;
use Phalcon\Security\JWT\Signer\Hmac;
use Phalcon\Security\JWT\Token\Parser;
use Phalcon\Security\JWT\Validator;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class SecureController extends Controller
{
    public function indexAction()
    {
    }
 public function createTokenAction($role)
 {





  //  Defaults to 'sha512'
    $signer  = new Hmac();
    
    // Builder object
    $builder = new Builder($signer);
    

    $passphrase = 'QcMpZ&b&mo3TPsPk668J6QH8JA$&U&m2';
    
    // Setup
    $builder
        ->setAudience('https://target.phalcon.io')  // aud
        ->setContentType('application/json')        // cty - header
        ->setId('abcd123456789')                    // JTI id 
        ->setIssuer('https://phalcon.io')           // iss bf
        ->setSubject($role)                        // sub
        ->setPassphrase($passphrase)                // password 
    ;
    
    // Phalcon\Security\JWT\Token\Token object
    $tokenObject = $builder->getToken();
    
    // The token
   
    $token =  $tokenObject->getToken();
    return $token;
  
 }

    public function BuildAclAction()
    {
        $aclFile = APP_PATH . '/security/acl.cache';

        if (true !== is_file($aclFile)) {

            // The ACL does not exist - build it
            $acl = new Memory();
             

            $var = new Role();
            $res = json_decode(json_encode($var->find())); 

            foreach ($res as $key => $value) {
                $acl->addRole($value->jobProfile);
            }
         

            $var = new Access();
            $res = json_decode(json_encode($var->find()));  
     
            foreach ($res as $key => $value) {

                $acl->addComponent(
                    $value->controller,
                    [
                        $value->action
                    ]
                );
                $acl->allow($value->jobProfile, $value->controller, $value->action);
            }
         


            // Store serialized list into plain file
            file_put_contents(
                $aclFile,
                serialize($acl)
            );
        }
    }
}
