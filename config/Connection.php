<?php
namespace Config;
class Connection{

    private $bdd;
    
    public function __construct($DB_NAME,$DB_HOST, $DB_USER, $DB_PWD){
        $this->connect($DB_NAME,$DB_HOST, $DB_USER, $DB_PWD);
    }
    /*
    *
    *   Connection à la base de données
    */
    private function connect($DB_NAME,$DB_HOST, $DB_USER, $DB_PWD){
            
        try
        {
            $this->bdd = new \PDO("mysql:host =".$DB_HOST.";dbname=".$DB_NAME,$DB_USER,$DB_PWD);
            $this->bdd->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->bdd->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
        }catch(\PDOException $e){
            var_dump("Impossible de connecter à la BDD ! Veuillez réessayer plus tard");
            var_dump($e->getMessage());
            die();
        }
    }
    
    public function getPDO(){
        return $this->bdd;
    }
    
}
    