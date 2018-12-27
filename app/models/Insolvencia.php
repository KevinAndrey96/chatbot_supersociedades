<?php

use \Phalcon\Mvc\Model;

/**
 * 
 */
class Insolvencia extends Model
{

    /**
     *
     * @var integer
     */
    public $id_insolvencia;

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

    public function getAnswer($entity_1, $entity_2)
    {
        $sql = " SELECT * 
                 FROM insolvencia aj
                 WHERE aj.type = '$entity_1' and aj.action = '$entity_2'";

        $prepare = $this->getDi()->getShared("db")->prepare($sql);
        $prepare->execute();

        return $prepare;
    }

}