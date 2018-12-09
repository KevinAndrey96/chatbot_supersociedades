<?php

use \Phalcon\Mvc\Model;

/**
 * 
 */
class Consult extends Model
{

    /**
     *
     * @var integer
     */
    public $id_consult;

    /**
     *
     * @var string
     */
    public $description;

    /**
     *
     * @var String
     */
    public $type;


    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("public");
    }

}