<?php
require("controllers/controller.php");
require("models/token_helper.php");
$url = $_SERVER['REQUEST_URI'];

if (isset($_GET["page"]) && !empty($_GET["page"])) {
    $page = htmlspecialchars($_GET["page"]);
    $option = isset($_GET["option"]) ? htmlspecialchars($_GET["option"]) : null;

    $token_helper = new TokenHelper();
    $tokenData = $token_helper->get_token_by_name($page);

    if ($tokenData !== null) {
        $token = htmlspecialchars($tokenData['id'], ENT_QUOTES, 'UTF-8');
        if ($option > 3){
            DisplayHome($token, $option); 
        } else {
            DisplayHome($token, 15);
        }
    } elseif ($page == "home") {
        DisplayHome(3, 15);
    } elseif ($page == "login") {
        DisplayLogin();
    } elseif ($page == "wallet") {
        DisplayWallet();
    } elseif ($page == "cryptocurrencies") {
        DisplayCrypto();
    } else {
        Display404();
    }
} else {
    $defaultOption = 15;
    DisplayHome(3, $defaultOption);
}
?>
