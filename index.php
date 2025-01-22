<?php
require("controllers/controller.php");

$url = "$_SERVER[REQUEST_URI]";

if (isset($_GET["page"]) && !empty($_GET["page"])) {
    $page = htmlspecialchars($_GET["page"]);

    $model = new Model();
    if ($model->get_token_by_name($page)) {
        $tokenData = $model->get_token_by_name($page);
        $token = htmlspecialchars($tokenData['id'], ENT_QUOTES, 'UTF-8');
        DisplayHome($token);

    } else if ($page == "home") {
        DisplayHome(3);
    } else if ($page == "login") {
        DisplayLogin();
    } else if ($page == "wallet") {
        DisplayWallet();
    } else if ($page == "cryptocurrencies") {
        DisplayCrypto();
    } else {
        Display404();
    }
} else {
    DisplayHome(3);
}