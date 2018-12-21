<?php

use \Phalcon\Mvc\Model;

/**
 * 
 */
class NotFoundQuestions extends Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $question;

    /**
     *
     * @var String
     */
    public $id_chat;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("public");
    }

}