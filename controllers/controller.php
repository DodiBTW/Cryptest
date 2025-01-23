<?php
require_once("./models/price_helper.php");
require_once("./models/wallet_helper.php");
require_once("./models/token_helper.php");
require_once("./models/user_helper.php");
function DisplayHome($id,$limit)
{
    $price_helper = new PriceHelper();
    $token_helper = new TokenHelper();
    $currentPrice = $price_helper->get_current_price($id);
    $pricesChart = $price_helper->get_prices_by_token_id($id);
    $token = $token_helper->get_token_by_id($id);

    $labels = [];
    $values = []; 
    $counter = 0;

    foreach ($pricesChart as &$priceChart) {

        if ($counter >= $limit) {
            break;
        }

        $dateTime = new DateTime($priceChart["date"]);
        $heure = $dateTime->format('H:i');
        
        $labels[] = $heure;
        $values[] = $priceChart["price"];
        $counter++;
    }

    $chartLabels = array_reverse($labels);
    $chartValues = array_reverse($values);

    require("./views/home.php");
}

function DisplayWallet()
{
    $user_helper = new UserHelper();
    if (!$user_helper->check_login()) {
        echo "Vous devez être connecté";
    } else {
        require("./views/wallet.php");
    }
}

function DisplayCrypto()
{
    $price_helper = new PriceHelper();
    $token_helper = new TokenHelper();
    $tokens = $token_helper->get_all_tokens();

    foreach ($tokens as &$token) {
        $currentPrice = $price_helper->get_current_price($token['id']);
        $token['price'] = $currentPrice['price'];
    }
    unset($token);

    require("./views/cryptocurrencies.php");
}


function DisplayLogin()
{
    $price_helper = new PriceHelper();
    $currentPrice = $price_helper->get_current_price(2);
    $pricesChart = $price_helper->get_all_prices(2);
    require("./views/home.php");
}

function Display404()
{
    require("./views/404.php");
}
?>