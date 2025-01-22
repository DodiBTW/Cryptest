<?php
require_once("./models/model.php");

function DisplayHome($id)
{
    $model = new Model();
    $currentPrice = $model->get_current_price($id);
    $pricesChart = $model->get_prices_by_token_id($id);
    $token = $model->get_token_by_id($id);

    $labels = [];
    $values = []; 

    foreach ($pricesChart as &$priceChart) {
        $dateTime = new DateTime($priceChart["date"]);
        $heure = $dateTime->format('H:i');
        
        $labels[] = $heure;
        $values[] = $priceChart["price"];
    }

    $chartLabels = $labels;
    $chartValues = $values;

    require("./views/home.php");
}

function DisplayWallet()
{
    $model = new Model();
    if (!$model->check_login()) {
        echo "Vous devez être connecté";
    } else {
        require("./views/wallet.php");
    }
}

function DisplayCrypto()
{
    $model = new Model();
    $tokens = $model->get_all_tokens();

    foreach ($tokens as &$token) {
        $currentPrice = $model->get_current_price($token['id']);
        $token['price'] = $currentPrice['price'];
    }
    unset($token);

    require("./views/cryptocurrencies.php");
}


function DisplayLogin()
{
    $model = new Model();
    $currentPrice = $model->get_current_price(2);
    $pricesChart = $model->get_all_prices(2);
    require("./views/home.php");
}

function Display404()
{
    require("./views/404.php");
}
?>