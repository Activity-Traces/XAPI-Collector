<?php

namespace App\Requests;

class sqlRequest {

//*****************************************************************************************************************************
//*****************************************************************************************************************************

    private $database;
    private $login;
    private $password;
    private $host;
    
    private $link;

    public $request;
    public $result;
       
    //***************************************************************************************************************************** */

    function initConnexion ($host, $login, $password, $database){
        
        $this->host=$host;
        $this->login=$login;
        $this->password=$password;
        $this->database=$database;

    }

    //*****************************************************************************************************************************   
    // se connecter à la base de données avec PDO
    public function connect(){

        try {
          //  echo "ici: ".$this->login;

                $this->link = new \PDO(
                                        'mysql:host='.$this->host.
                                        ';dbname='.$this->database,
                                        $this->login,
                                        $this->password,

                                        array(
                                            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                                            \PDO::ATTR_PERSISTENT => false
                                    )
                );

        } 
        catch (\PDOException $ex) {

            print($ex->getMessage());

        }
    }

    //*****************************************************************************************************************************

    public function Execute(){
        try {

            $this->connect();
            $handle = $this->link->prepare($this->request);
            $handle->execute();
            $this->result = $handle->fetchAll(\PDO::FETCH_OBJ);

        } 
        catch (\PDOException $ex) {
            print($ex->getMessage());
        }
    }

    //*****************************************************************************************************************************

    public function Update(){
        $this->connect();
        $handle = $this->link->prepare($this->request);
        $handle->execute();
    }

    //*****************************************************************************************************************************
    
    public function getResult(){
        return $this->result;
    }

    //*****************************************************************************************************************************

    public function getUserAnswers($user, $tmin, $tmax){
        $this->request = "
            
            SELECT 
            claire_exercise_answer.id as answerId,
            claire_exercise_answer.attempt_id as attemptId,

            claire_exercise_answer.mark as mark, 
            claire_exercise_answer.created_at as createdAt,  
            claire_exercise_answer.content as ansewerContent,  
            

            claire_exercise_stored_exercise.id as exerciseId,
            claire_exercise_stored_exercise.content as exerciseContent,

            claire_exercise_model.id as modelId,
            claire_exercise_model.type as questionType,
            claire_exercise_model.title as title,
            claire_exercise_model.content as exoModelContent,
    
            claire_exercise_item.content as itemContent
            
            FROM claire_exercise_answer, claire_exercise_stored_exercise, claire_exercise_attempt, claire_exercise_model,claire_exercise_item

            
            WHERE 
            (claire_exercise_answer.created_at >'".$tmin."') 
            AND (claire_exercise_answer.created_at <'".$tmax."') 
            
            AND (claire_exercise_answer.attempt_id = claire_exercise_attempt.id)
            AND (claire_exercise_attempt.user_id=".$user.") 
            AND (claire_exercise_attempt.exercise_id=claire_exercise_stored_exercise.id)
            AND (claire_exercise_model.id=claire_exercise_stored_exercise.exercise_model_id)
            AND (claire_exercise_answer.item_id = claire_exercise_item.id)

            ORDER BY claire_exercise_answer.created_at ASC
            
        ";
    
        $this->Execute();

    }

    //*****************************************************************************************************************************
    //*****************************************************************************************************************************

}
?>