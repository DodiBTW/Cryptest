<?php
require("controller.php");

$url = "$_SERVER[REQUEST_URI]";

if(isset($_GET["page"]) && !empty($_GET["page"])){
    $page = htmlspecialchars($_GET["page"]);

    if($page == "home"){
        DisplayHome();
    }
    else if($page == "wallet"){
        DisplayWallet();
    }   
    else{
        Display404();
    }
}