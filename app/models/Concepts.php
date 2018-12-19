<?php

use \Phalcon\Mvc\Model;

/**
 * 
 */
class Concepts extends Model
{

    /**
     *
     * @var integer
     */
    public $id_concept;

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