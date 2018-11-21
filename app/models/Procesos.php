<?php

use \Phalcon\Mvc\Model;

/**
 * 
 */
class Procesos extends Model
{

    /**
     *
     * @var integer
     */
    public $id_proceso;

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
     *
     * @var String
     */
    public $action;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("public");
    }

}