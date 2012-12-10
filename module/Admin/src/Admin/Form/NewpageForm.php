<?php

/*
 * Created on Dec 5, 2012
 * Author: Mian Khurram Ijaz (khurramijazm@gmail.com)
 * Copyright 2012 NextBridge Vteams USA. All rights reserved.
 * COMPANY PROPRIETARY/CONFIDENTIAL. Use is subject to license terms.
 */

namespace Admin\Form;

use Zend\Form\Form;

class NewpageForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('newPage');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'title',
            'attributes' => array(
                'type'  => 'text',
            ),
            
        ));
        $this->add(array(
            'name' => 'name',
            'attributes' => array(
                'type'  => 'text',
            ),
            
        ));
        $this->add(array(
            'name' => 'html',
            'attributes' => array(
                'type'  => 'textarea',
            ),
            
        ));
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Submit',
                'id' => 'submitbutton',
            ),
        ));
    }
}

?>
