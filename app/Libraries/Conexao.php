<?php
namespace App\Libraries;

use Exception;
use PDO;
// 
class Conexao{

private $host=DB['HOST'];
private $port=DB['PORT'];
private $db=DB['SGBD'];
private $dbname=DB['DBNAME'];
private $user=DB['USER'];
private $pass=DB['PASS'];
private object $conn;
private $crud;
    public function __construct()
    {
        $dbi= $this->db.":host=".$this->host.";port=".$this->port.";dbname=";
        try 
        {
            $this->conn= new \PDO($dbi.$this->dbname,$this->user,$this->pass);
            $this->conn->setAttribute(\PDO::ATTR_PERSISTENT,true);
            $this->conn->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
        } 
        catch (Exception $th)
         {
            echo "Erro na conexao com db->".$this->dbname.". Erro gerado->".$th->getMessage();
            die();
        }   
    }
    public function query($sql)
    {
        $this->crud= $this->conn->prepare($sql);
    }
    public function bind($param,$valor,$tipo=null)
    {
        if(is_null($tipo)):
            switch (true):
                case is_int($valor):
                    $tipo=PDO::PARAM_INT;
                    break;
                case is_bool($valor):
                    $tipo=PDO::PARAM_BOOL;
                    break;
                case is_null($valor):
                    $tipo=PDO::PARAM_NULL;
                    break;
                default:
                $tipo=PDO::PARAM_STR;
            endswitch;
        endif;
       
        $this->crud->bindValue($param,$valor,$tipo);
    }
    public function executa()
    {
        return $this->crud->execute();
    }
    public function resultado()
    {
        return $this->crud->fetch(PDO::FETCH_ASSOC);
    }
    public function resultados()
    {
        return $this->crud->fetchAll(PDO::FETCH_ASSOC);
    }
    public function total()
    {
        return $this->crud->rowCount();
    }
    public function ultimoid()
    {
        return $this->conn->lastInsertId();
    }


}