<?php 
namespace App;
class Users extends Rest{
    private $response;
    
    private $id;
    private $pseudo;
    private $userUpdate = false;
    
    public function __construct( $p_id = null ){
        $this->id = $p_id;
        $listefieldUpdate = array('firstname'=>true, 'email'=>true,'password'=>true,'login'=>true,'lastname'=>true);
    }
     /**
     * 
     * Affichage de tous les utilisateurs
     * 
     */
      public function getUsers( $bdd ){
        try
        {
            
            if($this->id){
            //Interroge la base de données pour recuperer les info de l'utilisateur demandé !
                if($this->userExiste( $bdd )){
                    $data = $bdd->query('SELECT  * FROM users WHERE id='.$this->id);
                    return  $data->fetch();   
                }
                $this->response('User not found or not exist !',404);// gerer le cas si l'utilisateur demandé n'existe pas dans la bdd !
            
            }
            else
            {
                $data = $bdd->query('SELECT  * FROM users');
                return  $data->fetchAll();  
            }
        }catch(Exception $e)
        {
            echo $e->getMessage();
        }
        
    }
    
    
    
     /**
     * 
     * creation d'un utilisateur
     * if(isset($_GET["id"]) and !empty($_GET['id'])){
     */
    public function postUser( $bdd, $data ){
        
        $request = $bdd->prepare("INSERT INTO users(firstName,email,password,roles,login,lastName) VALUES(:firstname,:email,sha1(:password),:roles, :login,:lastname)");
        $success = $request->execute(
            array(
                ':firstname'=>$data['firstname'],
                ':email'=>$data['email'],
                ':password'=>sha1 ($data['password']),
                ':roles'=>$data['roles'],
                ':login'=>$data['login'],
                ':lastname'=>$data['lastname']
            )
        );
        
        if($success){
            $this->response = array('status'=>'Utilisateur cree avec succes ! ');
            return $this->response;
        }else{
            $this->response = array('status'=> 'Impossible de creer l\'utilisateur !');
            return $this->response;
        }
            
    }
    
    /**
     * 
     * Verifie si l'utilisateur avec l'id actuelle existe dans la base de données 
     *
     */
    private function userExiste( $bdd )
    {
        $query = $bdd->query('SELECT * FROM users WHERE id ='.$this->id );
        $query->execute();
        $data = $query->fetch();
        $this->pseudo = $data->login;
        return $data;
    }
    
    /**
     * 
     *Suppression de l'utilisateur dans la base de données ! 
     * 
     */
     private function supprimerUtilisateur( $bdd )
     {
        $query = $bdd->query("DELETE FROM users WHERE id =".$this->id);
        return $query->execute();
     }
    
    
     /**
     * 
     * Suppression d'un utilisateur
     * 
     */
    public function deleteUser( $bdd ){
        
        if( $this->userExiste( $bdd ) ){
            
            echo $this->supprimerUtilisateur( $bdd );
            
            $this->response = array("status"=> "L'utilisateur ".$this->pseudo." a ete supprime avec succes ! ");
            return $this->response;
            
        }else{
            $this->response = array("status"=> "L'utilisateur n'existe pas ou a deja ete supprime !");
            return $this->response;
            
        }
        if($this->get_request_method() != 'DELETE') $this->response('undefined route',406);
         if( isset($_GET["id"]) && !empty($_GET['id']) ){
            if(isset($_GET['deleteUser']) ){
                 $this->response = array('status'=>'utilisateur a ete supprime ! ');
                 $this->response($this->json($this->response),200);   
            }
        }
        
    }
    
    /**
     * 
     * Mise à jour d'un utilisateur
     * 
     */
    public function saveUser( $bdd, $data ){
       
        if( $this->userExiste( $bdd ) ){
            $query = $this->createRequestSqlUpdate($data);
            if( $this->userUpdate ){
           
                $request = $bdd->query($query);
                $success = $request->execute();
                if( $success ){
                    $this->response = array('status'=>'Les donnees de l\'utilisateur avec ID: '.$this->id.' ont ete mise a jour !');
                    //$this->response($this->json( $this->response ),200);
                    return $this->response;
                }else{
                    $this->response = array('status'=>'Impossible de mettres les données de l\'utilisateur ' .$this->id.'!<br/>Veuillez réessayer plus tard. ');
                    //$this->response($this->json( $this->response ),200);
                    return $this->response;
                }
            }else{
                    $this->response = array('status'=>'Impossible de mettres les données de l\'utilisateur ' .$this->id.'!<br/>Veuillez réessayer plus tard. ');
                    //$this->response($this->json( $this->response ),200);
                    return $this->response;
            }
            
        } 
    }
    private function createRequestSqlUpdate($data){
        $query ="UPDATE users SET";
        if( isset($data['firstname']) && !empty($data['firstname']) ) {
            $this->userUpdate = true;
            $query .= ' firstName = "' . $data['firstname'].'",';
        }
        if( isset($data['email']) && !empty($data['email']) ){
            $this->userUpdate = true;
            $query .= ' email = "' . $data['email'].'",';
        }
        if( isset($data['password']) && !empty( $data['password'] ) ){
            $this->userUpdate = true;
            $query .= ' password = "' . sha1($data['password']).'",';
        }
        if( isset($data['roles']) && !empty( $data['roles'] ) ){
            $this->userUpdate = true;
            $query .= ' roles = "' . $data['roles'].'",';
        }
        if( isset($data['login']) && !empty($data['login']) ){
            $this->userUpdate = true;
            $query .= ' login = "' . $data['login'].'",';
        }
        if( isset($data['lastname']) && !empty( $data['lastname'] ) ){
            $this->userUpdate = true;
            $query .= ' lastName = "' . $data['lastname'].'",';
        }
        $query = substr($query, 0, strlen($query)-1);
        $query .= " WHERE id = " . $this->id .";";
        return $query;

    }
    /*
	*	Encode array into JSON
	*/
	private function json( $data ){
		if(is_array( $data )){
			return json_encode( $data );
		}
	}
    
}