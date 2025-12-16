<?php

ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL ^ (E_NOTICE | E_DEPRECATED)); 

class Config {

    public static function DB_HOST() { return "localhost"; }
    public static function DB_NAME() { return "revlonvotingdb"; }
    public static function DB_USER() { return "root"; }
    public static function DB_PASS() { return ""; }

    public static function JWT_SECRET() {
        return "elvir_pandur_secret_key_123";  // SECRET KEY!!
    }
}
