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

    public function buy_token($amount, $token_name){
        session_start();
        $json_helper = new JsonHelper();
        $balance_helper = new BalanceHelper();
        $wallet_helper = new WalletHelper();
        $token_helper = new TokenHelper();
        $transaction_helper = new TransactionHelper();
        $user_id = $_SESSION['user'];
        $balance = $wallet_helper->get_wallet_balance();
        $token = $token_helper->get_token_by_name($token_name);
        $token_price = $token['price'];
        $token_id = $token['id'];
        $total_price = $amount * $token_price;
        if ($balance < $total_price) {
            return false;
        }
        $wallets = $json_helper->read_json_file($this->walletsFile);
        $found = false;
        foreach ($wallets as $wallet) {
            if ($wallet['user_id'] != $user_id) {
                continue;
            }
            foreach ($wallet['tokens'] as $token) {
                if ($token['token_id'] == $token_id) {
                    $token['amount'] += $amount;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $wallet['tokens'][] = ['token_id' => $token_id, 'amount' => $amount];
            }
            break;
        }
        $json_helper->write_json_file($this->walletsFile, $wallets);
        $balance_helper->withdraw_from_balance($total_price);
        $transaction_helper->add_transaction($token_name, 'buy', $amount);
        return true;
    }
    public function sell_token($amount, $token_name){
        $json_helper = new JsonHelper();
        $balance_helper = new BalanceHelper();
        $wallet_helper = new WalletHelper();
        $token_helper = new TokenHelper();
        $transaction_helper = new TransactionHelper();
        session_start();
        $user_id = $_SESSION['user'];
        $token = $this->get_token_by_name($token_name);
        $token_price = $token['price'];
        $token_id = $token['id'];
        $total_price = $amount * $token_price;
        $wallets = $json_helper->read_json_file($this->walletsFile);
        foreach ($wallets as $wallet) {
            if ($wallet['user_id'] != $user_id) {
                continue;
            }
            foreach ($wallet['tokens'] as $token) {
                if ($token['token_id'] == $token_id && $token['amount'] >= $amount) {
                    $token['amount'] -= $amount;
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