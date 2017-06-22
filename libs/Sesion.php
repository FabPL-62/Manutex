<?php
    
// clase sesion
class Sesion {
    
    static function init(){
        @session_start();
    }
    
    static function destroy(){
        session_destroy();
    }
    
    static function get_value($var){
        return $_SESSION[$var];
    }
    
    static function set_value($var,$val){
        $_SESSION[$var] = $val;
    }
    
    static function unset_value($var){
        if (isset($_SESSION[$var])) unset($_SESSION[$var]);
    }
    
    static function exists(){
        if (sizeof($_SESSION) > 0) return true;
        else return false;
    }
}