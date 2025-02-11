<?php
require_once("./models/price_helper.php");
require_once("./models/wallet_helper.php");
require_once("./models/token_helper.php");
require_once("./models/user_helper.php");
function DisplayHome($id, $limit, $error = null)
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
function HandleLogin()
{
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';


        $user_helper = new UserHelper();
        if ($user_helper->login($username, $password)) {
            $balance_helper = new BalanceHelper();
            $user_helper = new UserHelper();

            $user_id = $user_helper->get_user_id();
            if ($balance_helper->get_wallet_balance() < 10){
                $balance_helper->add_wallet_balance($user_id);
            }
            require("./views/wallet.php");
            exit;
        } else {
            $error = "Identifiants incorrects";
        }
    }

    require("./views/login.php");
}

function HandleTransaction()
{
    $user_helper = new UserHelper();
    $token_helper = new TokenHelper();
    if ($user_helper->check_login()) {
        
        $amount = $_POST['amount'];
        $action = $_POST['action'];
        $name = $_POST['name'];
        $token_id = $token_helper->get_token_by_name($name)['id'];

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            if (!$_POST['amount'] or !$_POST['action']){
                DisplayHome($token_id,15,"Veuillez saisir un montant" );
                return;
            }

            $token_helper = new TokenHelper();
            if ($amount != 0 && $action == "buy") {
                if ($token_helper->buy_token($amount, $name ) == false ) {
                    DisplayHome($token_id,15,"Solde insuffisant pour acheter." );
                    return;
                }
            };
            if ($amount != 0 && $action == "sell") {
                if ($token_helper->sell_token($amount, $name ) == false ) {
                    DisplayHome($token_id,15,"Solde insuffisant pour vendre." );
                    return;
                }
            };
        }
        header("Location: /?page=$name&option=15");
    } else {
        require("./views/login.php");
      
    }
    
}

function HandleRegister()
{
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $user_helper = new UserHelper();
        if ($user_helper->register($username, $password)) {
            header("Location: /?page=login");
            exit;
        } else {
            $error = "Ce nom d'utilisateur est déjà pris.";
        }
    }

    require("./views/register.php");
}

function DisplayWallet()
{
    $user_helper = new UserHelper();
    if (!$user_helper->check_login()) {
        HandleLogin();
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
        $token['price'] = $currentPrice ? $currentPrice['price'] : 'N/A'; 
        
    }
    
    unset($token);
    
    require("./views/cryptocurrencies.php");
}

function DisplayLogin()
{
    require('./views/login.php');
}

function DisplayRegister()
{
    require('./views/register.php');
}

function Display404()
{
    require("./views/404.php");
}
?>