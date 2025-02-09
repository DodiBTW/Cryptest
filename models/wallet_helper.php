<?php
require_once 'json_helper.php';
require_once 'price_helper.php';
class WalletHelper{
    private $walletsFile = './db/wallets.json';

    public function get_wallet_tokens(int $user_id): array {
        // Returns all tokens in a user's wallet with user_id, token_id, and amount

        $json_helper = new JsonHelper();
        $wallets = $json_helper->read_json_file($this->walletsFile);
        $user_wallets = array_filter($wallets, function($wallet) use ($user_id) {
            return $wallet['user_id'] == $user_id;
        });
        $tokens = array_map(function($wallet) use ($token_helper){
            $token = $token_helper->get_token_by_id($wallet['token_id']);
            return [
                'token_id' => $wallet['token_id'],
                'name' => $token['name'],
                'symbol' => $token['symbol'],
                'address' => $token['address'],
                'amount' => $wallet['amount']
            ];
        }, $user_wallets);
        return $tokens;
    }
    public function get_wallet_worth(int $user_id):float{
        $user_tokens = $this->get_wallet_tokens($user_id);
        $price_helper = new PriceHelper();
        $worth = 0;
        array_map(function($token) use ($price_helper, &$worth){
            $worth += $price_helper->get_token_price($token['token_id']) * $token['amount'];
        }, $user_tokens);
        return $worth;
    }
    public function get_token_wallet_amount(int $user_id, int $token_id): ?float {
        $wallets = $this->read_json_file($this->walletsFile);
        $this->get_wallet_tokens($user_id);
        $filtered = array_filter($wallets, function($wallet) use ($user_id, $token_id) {
            $wallet['token_id'] == $token_id;
        });
        if (empty($filtered)) {
            return null;
        }
        return $filtered[0]['amount'];
    }
}