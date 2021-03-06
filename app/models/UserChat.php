<?php

use \Phalcon\Mvc\Model;

/**
 * 
 */
class UserChat extends Model
{

    /**
     *
     * @var integer
     */
    public $id_user;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var String
     */
    public $department;
    
    /**
     *
     * @var String
     */
    public $email;
    
    /**
     *
     * @var String
     */
    public $phone;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("public");
    }

}
