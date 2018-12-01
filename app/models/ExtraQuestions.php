<?php

use \Phalcon\Mvc\Model;

/**
 * 
 */
class ExtraQuestions extends Model
{

    /**
     *
     * @var integer
     */
    public $id_question;

    /**
     *
     * @var string
     */
    public $question;

    /**
     *
     * @var String
     */
    public $answer;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("public");
    }

    public function findQuestion($question)
    {
        $sql = " SELECT *
                 FROM extra_questions eq
                 WHERE eq.question like '%$question%'
                 ORDER BY eq.id_question DESC LIMIT 1 ";

        $prepare = $this->getDi()->getShared("db")->prepare($sql);
        $prepare->execute();

        return $prepare;
    }


}