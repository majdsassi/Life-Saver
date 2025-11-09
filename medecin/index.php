<?php 
session_start();
require_once '../config.php';
require_once '../utils/connection.php' ; 
if(isset($_SESSION["user_id"])){
    if($_SESSION["user_role"] == "MEDECIN") {
        echo " Welcome ! " ;


    }
    else{
        header("Location:".DOMAIN."login.php?error=403");
        exit ; 
    }
}
else{
        header("Location:".DOMAIN."login.php?error=401");
        exit ; 
}