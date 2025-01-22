<?php
require_once("./models/model.php");

function DisplayHome(){
    $model = new Model();
    $currentPrice = $model->get_current_price(2);
    $pricesChart = $model->get_all_prices(2);
    require("./views/home.php");
}

function DisplayWallet(){
    $model = new Model();
    if (!$model->check_login()){
        echo "T Pa Laugine";
    } else {
        require("./views/wallet.php");
    }
}

function DisplayCrypto(){
    require("./views/cryptocurrencies.php");
}


function DisplayLogin(){
    $model = new Model();
    $currentPrice = $model->get_current_price(2);
    $pricesChart = $model->get_all_prices(2);
    require("./views/home.php");
}

function Display404(){
    require("./views/404.php");
}
?>
