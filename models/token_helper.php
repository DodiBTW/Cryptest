<?php
require_once 'json_helper.php';
require_once 'wallet_helper.php';
require_once 'balance_helper.php';
require_once 'transaction_helper.php';
class TokenHelper{
    private $tokensFile = './db/tokens.json';
    private $walletsFile = './db/wallets.json';

    public function get_all_tokens(): array {
        $json_helper = new JsonHelper();
        return $json_helper->read_json_file($this->tokensFile);
    }

    public function get_token_by_id(int $id): ?array {
        $json_helper = new JsonHelper();
        $tokens = $json_helper->read_json_file($this->tokensFile);
        foreach ($tokens as $token) {
            if ($token['id'] == $id) {
                return $token;
            }
        }
        return null;
    }
    
    public function get_token_by_name(string $name): ?array {
        $json_helper = new JsonHelper();
        $tokens = $json_helper->read_json_file($this->tokensFile);
        foreach ($tokens as $token) {
            if ($token['name'] == $name) {
                return $token;
            }
        }
        return null;
    }

    public function buy_token($amount, $token_name) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $json_helper = new JsonHelper();
        $balance_helper = new BalanceHelper();
        $price_helper = new PriceHelper();
        $token_helper = new TokenHelper();
        $transaction_helper = new TransactionHelper();
        $user_helper = new UserHelper();
        
        $user_id = (int)$user_helper->get_user_id();
        if (!$user_id) {
            return false;
        }
    
        $balance = $balance_helper->get_wallet_balance();
        $token = $token_helper->get_token_by_name(ucfirst($token_name));
    
        if (!$token) {
            return false;
        }
    
        $token_price = $price_helper->get_current_price($token['id'])['price'];
        $token_id = $token['id'];
        $total_price = $amount * $token_price;
    
        if ($balance < $total_price) {
            return false;
        }
    
        $wallets = $json_helper->read_json_file($this->walletsFile);
        $found = false;
    
        foreach ($wallets as &$wallet) {
            if ($wallet['user_id'] == $user_id) {
                if ($wallet['token_id'] == $token_id) {
                    $wallet['amount'] += $amount;
                    $found = true;
                    break;
                }
    
            }
        }
    
        if (!$found) {
            $wallets[] = [
                'user_id' => $user_id,
                'token_id' => $token_id,
                 'amount' => $amount
                ];
        }
    
        $json_helper->write_json_file($this->walletsFile, $wallets);
        $balance_helper->withdraw_from_balance($total_price);
        $transaction_helper->add_transaction($token_name, 'buy', $amount);
    
        return true;
    }
    
    public function sell_token($amount, $token_name){
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $json_helper = new JsonHelper();
        $balance_helper = new BalanceHelper();
        $wallet_helper = new WalletHelper();
        $token_helper = new TokenHelper();
        $price_helper = new PriceHelper();
        $user_helper = new UserHelper();
        $transaction_helper = new TransactionHelper();

        $user_id = (int)$user_helper->get_user_id();
        $token = $this->get_token_by_name(ucfirst($token_name))['id'];
        var_dump($token);

        $token_price = $price_helper->get_current_price($token)['price'];
        $token_id = $token;
        $total_price = $amount * $token_price;
        
        $wallets = $json_helper->read_json_file($this->walletsFile);
        foreach ($wallets as &$wallet) {
            if ($wallet['user_id'] == $user_id) {
                if ($wallet['token_id'] == $token_id && $wallet['amount'] >= $amount) {
                    $wallet['amount'] -= $amount;
                    $balance_helper->add_to_balance($total_price);
                    $json_helper->write_json_file($this->walletsFile, $wallets);
                    $transaction_helper->add_transaction($token_name, 'sell', $amount);
                    return true;
                }
            }
        }
        return false;
    }
}