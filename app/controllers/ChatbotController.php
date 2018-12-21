<?php


class ChatbotController extends ControllerBase {

    /*
    * Obtener respuesta a la pregunta
    */
    public function getAnswerAction() {

        $dataRequest = $this->request->getJsonPost();

        $fields = array(
            "query",
            "id_chat"
        );

        $optional = array();

        if ($this->_checkFields($dataRequest, $fields, $optional)) {

            try {

                if (strlen(ControllerBase::ENDPOINTKEY) == 32) {
                    
                    //addquestion[supersociedades*]=cual es tu nombre/me llamo pepito

                    $_query = $this->removeAccents($dataRequest->query);
                    
                    $validate = (explode("=", $_query));
                    $_key = $validate[0];
                    $key = "addquestion[".ControllerBase::PASSWORD."]";
                    
                    if ($key == $_key){
                        
                        if ($this->addQuestion($validate[1])){
                            $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                                "return" => true,
                                "message" => "Se ha agregado la pregunta.",
                                "status" => ControllerBase::SUCCESS
                            ));
                        } else {
                            $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                                "return" => true,
                                "message" => "No se ha podido agregar la pregunta.",
                                "status" => ControllerBase::SUCCESS
                            ));
                        }
                        
                    } else {
                        
                        $result = $this->AnalyzeText(ControllerBase::ENDPOINT, ControllerBase::APPID, ControllerBase::ENDPOINTKEY, $_query);
                        $result = json_decode($result);
                        
                        $intent = $result->topScoringIntent;
                        $date = $this->_dateTime->format('H:i:s');
                        $answer = "";

                        switch ($intent->intent) {
    
                            case "Greeting":
    
                                $entities = isset($result->entities[0]->type) ? $result->entities[0]->type : null;
                                $answer = Greeting::findFirst(array(
                                    "conditions" => 
                                    "type = ?1 and ((initial_hour <= ?2 and finish_hour >= ?2) or (initial_hour is null and finish_hour is null)) ",
                                    "bind" => array(1 => $entities,
                                                    2 => $date)
                                ));
                                
    
                                if (isset($answer->id_greeting))
                                    $answer = $answer->description;
                                else 
                                    $answer = $this->findStaticQuestion($_query, $dataRequest->id_chat);
    
                                break;
    
                            case "Information":
    
                                $entities = isset($result->entities[0]->type) ? $result->entities[0]->type : null;
                                $answer = Information::findFirst(array(
                                    "conditions" => "type = ?1",
                                    "bind" => array(1 => $entities)
                                ));
    
                                if (isset($answer->id_information))
                                    $answer = $answer->description;
                                else 
                                    $answer = $this->findStaticQuestion($_query, $dataRequest->id_chat);
    
                                break;  
    
                            case "General":
    
                                $entities = isset($result->entities[0]->type) ? $result->entities[0]->type : null;
                                $answer = General::find(array(
                                    "conditions" => "type = ?1",
                                    "bind" => array(1 => $entities)
                                ));
    
                                $count = count($answer);
    
                                if ($count > 0){
                                    $pos = rand(0, $count-1);
                                    $answer = $answer[$pos]->description;
                                } else {
                                    $answer = $this->findStaticQuestion($_query, $dataRequest->id_chat);
                                }
    
                                break;    
    
                            case "Process":
    
                                $entities = isset($result->entities[0]->type) ? $result->entities[0]->type : null;
                                $answer = Procesos::findFirst(array(
                                    "conditions" => "type = ?1",
                                    "bind" => array(1 => $entities)
                                ));
    
                                if (isset($answer->id_proceso))
                                    $answer = $answer->description;
                                else 
                                    $answer = $this->findStaticQuestion($_query, $dataRequest->id_chat);
                                break;    
                                
                            case "Sociedades":
    
                                $entities = isset($result->entities[0]->type) ? $result->entities[0]->type : null;
                                $answer = Sociedades::findFirst(array(
                                    "conditions" => "type = ?1",
                                    "bind" => array(1 => $entities)
                                ));
                                
    
                                if (isset($answer->id))
                                    $answer = $answer->description;
                                else 
                                    $answer = $this->findStaticQuestion($_query, $dataRequest->id_chat);
                                break;  

                            case "Consult":
                                
                                $entities = isset($result->entities[0]->type) ? $result->entities[0]->type : null;
                                $answer = Consult::findFirst(array(
                                    "conditions" => "type = ?1",
                                    "bind" => array(1 => $entities)
                                ));
    
                                
                                if (isset($answer->id_consult))
                                    $answer = $answer->description;
                                else 
                                    $answer = $this->findStaticQuestion($_query, $dataRequest->id_chat);
                                break; 

                            case "Concepts":
                                
                                $entities = isset($result->entities[0]->type) ? $result->entities[0]->type : null;
                                $answer = Concepts::findFirst(array(
                                    "conditions" => "type = ?1",
                                    "bind" => array(1 => $entities)
                                ));
    
                                
                                if (isset($answer->id_concept))
                                    $answer = $answer->description;
                                else 
                                    $answer = $this->findStaticQuestion($_query, $dataRequest->id_chat);
                                break;  

                            case "AuxiliaryJustice":

                                $entities = $result->entities; 

                                $entities_1 = isset($result->entities[0]->type) ? $result->entities[0]->type : null;
                                $entities_2 = isset($result->entities[1]->type) ? $result->entities[1]->type : null;
                                
                                $answer = AuxiliaryJustice::findFirst(array(
                                    "conditions" => "type = ?1 and action = ?2",
                                    "bind" => array(1 => $entities_1,
                                                    2 => $entities_2)
                                ));
    
                                if (isset($answer->id_auxiliary_justice)){
                                    $answer = $answer->description;
                                } else {
                                    $answer = AuxiliaryJustice::findFirst(array(
                                        "conditions" => "type = ?1 and action = ?2",
                                        "bind" => array(1 => $entities_2,
                                                        2 => $entities_1)
                                    ));

                                    if (isset($answer->id_auxiliary_justice))
                                        $answer = $answer->description;
                                    else
                                        $answer = $this->findStaticQuestion($_query, $dataRequest->id_chat);
                                } 
                                    
                                break;      
    
                            case "None":

                                $answer = $this->findStaticQuestion($_query, $dataRequest->id_chat);
                                break;    
                        }

                        $history = new History;
                        $history->id_chat = $dataRequest->id_chat;
                        $history->question = $dataRequest->query;
                        $history->answer = $answer;
                        $history->date = date("Y-m-d H:i:s"); 
                        $history->save();
    
    
                        $data['question'] = $dataRequest->query;
                        $data['answer'] = $answer; 
    
                        $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                            "return" => true,
                            "message" => "Operation Success",
                            "data" => $data,
                            "status" => ControllerBase::SUCCESS
                        ));
                    }

                } else {

                    $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                        "return" => false,
                        "message" => "Invalid LUIS key.",
                        "status" => ControllerBase::FAILED
                    ));
                }

            } catch (Exception $e) {
                $this->logError($e, $dataRequest);
            }
        }
    }
    
    /*
    * Agregar usuario y generar solicitud de chat
    */
    public function addUserAction() {

        $dataRequest = $this->request->getJsonPost();

        $fields = array(
            "name",
            "department",
            "phone",
            "email"
        );

        if ($this->_checkFields($dataRequest, $fields)) {

            try {
                
                $user = User::findFirst(array(
                    "conditions" => "email = ?1",
                    "bind" => array(1 => $dataRequest->email)
                ));
                
                if (isset($user->id_user)){
                    
                    $new_chat = new Request;
                    $new_chat->id_user = $user->id_user;
                    $new_chat->date = date("Y-m-d H:i:s");  
                    $new_chat->save();
                    
                    $data[] =  array(
                        "id_user" => $user->id_user,
                        "name" => $user->name,
                        "id_chat" => $new_chat->id_chat
                    );
                        
                    $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                        "return" => true,
                        "message" => "Usuario existente.",
                        "data" => $data,
                        "status" => ControllerBase::SUCCESS
                    ));
                    
                } else {
                    $usuario = new User;
                    $usuario->name = $dataRequest->name;
                    $usuario->department = $dataRequest->department;
                    $usuario->phone = $dataRequest->phone;
                    $usuario->email = $dataRequest->email;
                    
                    if ($usuario->save()) {

                        $new_chat = new Request;
                        $new_chat->id_user = $usuario->id_user;
                        $new_chat->date = date("Y-m-d H:i:s");  
                        $new_chat->save();
                        
                        $data[] =  array(
                            "id_user" => $usuario->id_user,
                            "name" => $usuario->name,
                            "id_chat" => $new_chat->id_chat
                        );
    
                        $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                            "return" => true,
                            "message" => "Se ha guardado el usuario satisfactoriamente.",
                            "data" => $data,
                            "status" => ControllerBase::SUCCESS
                        ));
                        
                    } else {
                        
                        $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                            "return" => false,
                            "message" => "No se pudo guardar la informacion del usuario.",
                            "status" => ControllerBase::FAILED
                        ));
                        
                    }
                }
                
            } catch (Exception $e) {
                $this->logError($e, $dataRequest);
            }
        }
    }

    /*
    * Historial conversacion
    */
    public function historyAction() {

        $dataRequest = $this->request->getJsonPost();

        $fields = array(
            "id_chat"
        );

        if ($this->_checkFields($dataRequest, $fields)) {

            try {

                $history = History::find(array(
                    "conditions" => "id_chat = ?1",
                    "bind" => array(1 => $dataRequest->id_chat)
                ));

                if (count($history) > 0) {
                    $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                        "return" => true,
                        "message" => "Historial.",
                        "data" => $history,
                        "status" => ControllerBase::SUCCESS
                    ));
                } else {
                    $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                        "return" => false,
                        "message" => "No se ha encontrado la conversacion.",
                        "status" => ControllerBase::FAILED
                    ));                    
                }
                
                
            } catch (Exception $e) {
                $this->logError($e, $dataRequest);
            }
        }
    }
    
    /*
    * Respuesta api LUIS
    */
    public function AnalyzeText($url, $app, $key, $query) {

        $headers = "Ocp-Apim-Subscription-Key: $key\r\n";
        $options = array ( 'http' => array (
                           'header' => $headers,
                           'method' => 'GET',
                           'cafile' => "/path/to/bundle/cacert.pem",
                           'verify_peer'=> true,
                           'verify_peer_name'=> true,
                           'ignore_errors' => true));
    
        $qs = http_build_query( array (
                "q" => $query,
                "verbose" => "false",
            )
        );

        $url = $url . $app . "?" . $qs;

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        return $result;
    }
    
    /*
    * Agregar pregunta estatica 
    */
    public function addQuestion($data) {
        
        $res = (explode("/", $data));
        $question = new ExtraQuestions;
        $question->question = $res[0];
        $question->answer = $res[1];
        
        if ($question->save()) {
            return true;
        } else {
            return false;
        }
    }

    /*
    * Quitar acentos
    */
    public function removeAccents($cadena){
   
        $cadena = str_replace(
            array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
            array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
            $cadena
        );
    
        $cadena = str_replace(
            array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
            array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
            $cadena );
    
        $cadena = str_replace(
            array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
            array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
            $cadena );
    
        $cadena = str_replace(
            array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
            array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
            $cadena );
    
        $cadena = str_replace(
            array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
            array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
            $cadena );
    
        return $cadena;
    }

    /*
    * Busca la pregunta en la tabla de preguntas estaticas
    */
    public function findStaticQuestion($query, $id_chat) {

        $question = new ExtraQuestions;
        $result = ($question->findQuestion($query));
        $result = $result->fetchAll();

        if (count($result) > 0 ){
            return $result[0]['answer'];
        } else {
            $not_found_questions = new NotfoundQuestions;
            $not_found_questions = $query;
            $not_found_questions = $id_chat;
            $not_found_questions->save();
            return ControllerBase::ANSWER_FAILURE;
        }
    }
}
