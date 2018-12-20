<?php

use \Phalcon\Mvc\Model;

/**
 * 
 */
class AuxiliaryJustice extends Model
{

    /**
     *
     * @var integer
     */
    public $id_auxiliary_justice;

    /**
     *
     * @var string
     */
    public $type;

    /**
     *
     * @var String
     */
    public $action;

    /**
     *
     * @var String
     */
    public $description;


    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("public");
    }

}