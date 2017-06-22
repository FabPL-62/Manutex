<?php
class Conexion {
    
    // guarda la conexion
    private $conexion;
    
    // constructor
    public function __construct($db_host, $db_user, $db_pass, $db_name) {
        
        $this->db_host = $db_host;
        $this->db_user = $db_user;
        $this->db_pass = $db_pass;
        $this->db_name = $db_name;
        $this->conexion = new mysqli($db_host,$db_user,$db_pass,$db_name);
        $this->conexion->set_charset("utf8");
        
        if (mysqli_connect_errno()){
           
            printf("Conexion fallida : %s\n", mysqli_connect_error());
            exit();
        }
    }
    
    // destructor
    function __destruct() {
        
        $thread_id = $this->conexion->thread_id;
        $this->conexion->kill($thread_id);
        $this->conexion->close();
        
    }
    
    // consulta directa
    function sql_query($sql) {
        
        $resultado = $this->conexion->query($sql) or die($this->conexion->error.__LINE__);
        if ($resultado->num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) {
                $respuesta[] = $row;
            }
            return $respuesta;
        }
        return null;
        
    }
    
    // ejecucion directa
    function sql_execute($sql) {
        
        $resultado = $this->conexion->query($sql) or die($this->conexion->error);
        if ($resultado > 0) return true;
        else return false;
        
    }
}