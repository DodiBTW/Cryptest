<?php
require_once("controllers/controller.php");
require_once("models/token_helper.php");
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
        HandleLogin();
    } elseif ($page == "wallet") {
        DisplayWallet();
    } elseif ($page == "cryptocurrencies") {
        DisplayCrypto();
    } elseif ($page == "register") {
        HandleRegister();
    } elseif ($page == "transaction") {
        HandleTransaction();
    } elseif ($page == "logout") {
        $user_helper = new UserHelper;
        $user_helper->logout();
        DisplayHome(3,15);
    } else {
        Display404();
    }
} else {
    $defaultOption = 15;
    DisplayHome(3, $defaultOption);
}
?>
