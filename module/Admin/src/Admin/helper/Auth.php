<?php

/*
 * Created on Dec 5, 2012
 * Author: Mian Khurram Ijaz (khurramijazm@gmail.com)
 * Copyright 2012 NextBridge Vteams USA. All rights reserved.
 * COMPANY PROPRIETARY/CONFIDENTIAL. Use is subject to license terms.
 */


namespace Admin\Helper;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;
use Zend\Authentication\Adapter\DbTable as dbTable;
use Zend\ServiceManager\ServiceManager;

class Auth implements AdapterInterface
{
    private $_username = '';
    private $_password ='';
    
    public function __construct($username, $password){
        $this->_username = $username;
        $this->_password = $password;
    }
    public function authenticate(){
        
        
        $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');           
        $dbTable = new dbTable($dbAdapter,'users','username','password');
        $dbTable->setIdentity($this->_username);
        $dbTable->setCredential($this->_password);
         $result = $dbTable->authenticate();
            
            if(!$result->isValid()){
                // Authentication failure
                foreach($result->getMessages() as $message){
                    echo "$message\n";
                }
            }
            else{
                return $result;
            }

            
    }
}
?>
