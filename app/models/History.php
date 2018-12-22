<?php

/**
 *
 */
class History extends \Phalcon\Mvc\Model
{
    /**
     *
     * @var Integer
     */
    public $id;

    /**
     *
     * @var Integer
     */
    public $id_chat;

    /**
     *
     * @var String
     */
    public $question;

    /**
     *
     * @var String
     */
    public $answer;

    /**
     *
     * @var String
     */
    public $date;


    public function initialize()
    {
        $this->setSchema("public");
    }
    
    public function getHistoryByUser($email)
    {
        $sql = " SELECT * 
                 FROM history h
                 INNER JOIN request r ON r.id_chat = h.id_chat
                 INNER JOIN user_chat us ON us.id_user = r.id_user
                 WHERE us.email = '$email'
                 ORDER BY id DESC";

        $prepare = $this->getDi()->getShared("db")->prepare($sql);
        $prepare->execute();

        return $prepare;
    }
}
