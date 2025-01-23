<?php
require_once 'json_helper.php';
require_once 'price_helper.php';
class WalletHelper{
    private $walletsFile = './db/wallets.json';

    public function get_wallet_worth(int $user_id): array {
        // Returns worth of all tokens in a user's wallet

        $json_helper = new JsonHelper();
        $price_helper = new PriceHelper();
        $wallets = $json_helper->read_json_file($this->walletsFile);
        $token_prices = [];
        foreach ($wallets as $wallet) {
            if ($wallet['user_id'] == $user_id) {
                foreach ($wallet['tokens'] as $token) {
                    $token_id = $token['token_id'];
                    $token_price = $price_helper->get_current_price($token_id);
                    if ($token_price) {
                        $token_prices[$token_id] = $token_price['price'] * $token['amount'];
                    }
                }
                break;
            }
        }
        return $token_prices;
    }

    public function get_token_wallet_amount(int $user_id, int $token_id): ?float {
        // Returns the amount of this token in the user's wallet
        $wallets = $this->read_json_file($this->walletsFile);
        foreach ($wallets as $wallet) {
            if ($wallet['user_id'] == $user_id) {
                foreach ($wallet['tokens'] as $token) {
                    if ($token['token_id'] == $token_id) {
                        return $token['amount'];
                    }
                }
            }
        }
        return null;
    }
}