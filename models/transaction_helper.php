<?php
require_once 'json_helper.php';
require_once 'token_helper.php';
class TransactionHelper{
    private $transactionsFile = './db/transactions.json';
    
    public function get_date_now(): string {
        return date('Y-m-d H:i:s');
    }

    public function get_transactions(): array {
        $json_helper = new JsonHelper();
        return $json_helper->read_json_file($this->transactionsFile) ?? [];
    }

    public function get_transactions_by_token_id(int $id): array {
        $json_helper = new JsonHelper();
        $transactions = $json_helper->read_json_file($this->transactionsFile);
        $filtered = array_filter($transactions, function($transaction) use ($id) {
            return $transaction['token_id'] == $id;
        });
        return $filtered;
    }

    private function add_transaction($token_name, $type, $amount): bool{
        $json_helper = new JsonHelper();
        $token_helper = new TokenHelper();
        $price_helper = new PriceHelper();
        session_start();
        $user_id = $_SESSION['user'];
        if(!$user_id) {
            return false;
        }
        $random_id = rand(100000, 999999);
        $token = $token_helper->get_token_by_name($token_name);
        if (!$token) {
            return false;
        }
        $token_id = $token['id'];
        $token_price = $price_helper->get_current_price($token_id);
        if(!$token_price) {
            return false;
        }
        $date = $this->get_date_now();
        $transactions = $json_helper->read_json_file($this->transactionsFile);
        if(!$transactions) {
            $transactions = [];
        }
        $transactions[] = ['id' => $random_id, 'token_id' => $token_id, 'wallet_id' => $user_id, 'date' => $date, 'amount' => $amount, 'buy_price' => $token_price, 'type' => $type];
        $json_helper->write_json_file($this->transactionsFile, $transactions);
        return true;
    }
}