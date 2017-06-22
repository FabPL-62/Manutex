<?php
require_once "Conexion.php";
class Modelo {
    protected $db;
    function __construct() {
        $this->db = new Conexion("localhost",DB_USER,DB_PASS,DB_NAME);
    }
}
