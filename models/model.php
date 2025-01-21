<?php

class Model
{
    private $pricesFile = 'prices.json';
    private $walletsFile = 'wallets.json';
    private $tokensFile = 'tokens.json';
    private $transactionsFile = 'transactions.json';
    private $usersFile = 'users.json';

    private function readJsonFile($file) {
        if (!file_exists($file)) {
            return [];
        }
        $json = file_get_contents($file);
        return json_decode($json, true);
    }

    private function writeJsonFile($file, $data) {
        $json = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents($file, $json);
    }

    public function get_date_now(){
        return date('Y-m-d H:i:s');
    }

    public function get_all_prices($limit){
        $prices = $this->readJsonFile($this->pricesFile);
        usort($prices, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
        return array_slice($prices, 0, $limit);
    }

    public function get_prices_by_token_id($id, $limit){
        $prices = $this->readJsonFile($this->pricesFile);
        $filtered = array_filter($prices, function($price) use ($id) {
            return $price['token_id'] == $id;
        });
        usort($filtered, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
        return array_slice($filtered, 0, $limit);
    }

    public function get_price_by_id($id){
        $prices = $this->readJsonFile($this->pricesFile);
        foreach ($prices as $price) {
            if ($price['id'] == $id) {
                return $price;
            }
        }
        return null;
    }

    public function get_prices_by_date_range($start, $end){
        $prices = $this->readJsonFile($this->pricesFile);
        $filtered = array_filter($prices, function($price) use ($start, $end) {
            return $price['date'] >= $start && $price['date'] <= $end;
        });
        usort($filtered, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
        return $filtered;
    }

    public function get_all_tokens(){
        return $this->readJsonFile($this->tokensFile);
    }

    public function get_token_by_id($id){
        $tokens = $this->readJsonFile($this->tokensFile);
        foreach ($tokens as $token) {
            if ($token['id'] == $id) {
                return $token;
            }
        }
        return null;
    }

    public function get_token_by_name($name){
        $tokens = $this->readJsonFile($this->tokensFile);
        foreach ($tokens as $token) {
            if ($token['name'] == $name) {
                return $token;
            }
        }
        return null;
    }

    public function get_transactions(){
        return $this->readJsonFile($this->transactionsFile);
    }

    public function get_transactions_by_token_id($id){
        $transactions = $this->readJsonFile($this->transactionsFile);
        $filtered = array_filter($transactions, function($transaction) use ($id) {
            return $transaction['token_id'] == $id;
        });
        return $filtered;
    }

    public function get_current_price($id){
        $prices = $this->readJsonFile($this->pricesFile);
        usort($prices, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
        foreach ($prices as $price) {
            if ($price['token_id'] == $id) {
                return $price;
            }
        }
        return null;
    }

    public function get_wallet_worth($user_id){
        $wallets = $this->readJsonFile($this->walletsFile);
        $token_prices = [];
        foreach ($wallets as $wallet) {
            if ($wallet['user_id'] == $user_id) {
                foreach ($wallet['tokens'] as $token) {
                    $token_id = $token['token_id'];
                    $token_price = $this->get_current_price($token_id);
                    if ($token_price) {
                        $token_prices[$token_id] = $token_price['price'] * $token['amount'];
                    }
                }
                break;
            }
        }
        return $token_prices;
    }

    public function get_token_wallet_amount($user_id, $token_id){
        $wallets = $this->readJsonFile($this->walletsFile);
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

    public function check_login(){
        session_start();
        return isset($_SESSION['user']);
    }
    public function get_user_id(){
        session_start();
        return $_SESSION['user'];
    }
    public function login($username, $password){
        $users = $this->readJsonFile($this->usersFile);
        foreach ($users as $user) {
            if ($user['username'] == $username && password_verify($password, $user['hashed_password'])) {
                session_start();
                $_SESSION['user'] = $username;
                return true;
            }
        }
        return false;
    }

    public function register($username, $password){
        $users = $this->readJsonFile($this->usersFile);
        foreach ($users as $user) {
            if ($user['username'] == $username) {
                return false; // User already exists
            }
        }
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $users[] = ['username' => $username, 'hashed_password' => $hashed_password];
        $this->writeJsonFile($this->usersFile, $users);
        return true;
    }
}
?>