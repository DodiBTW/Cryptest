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
        return $json_helper->read_json_file($this->transactionsFile);
    }

    public function get_transactions_by_token_id(int $id): array {
        $json_helper = new JsonHelper();
        $transactions = $json_helper->read_json_file($this->transactionsFile);
        $filtered = array_filter($transactions, function($transaction) use ($id) {
            return $transaction['token_id'] == $id;
        });
        return $filtered;
    }
    private function add_transaction($token_name, $type, $amount): void{
        $json_helper = new JsonHelper();
        $token_helper = new TokenHelper();
        session_start();
        $user_id = $_SESSION['user'];
        $random_id = rand(100000, 999999);
        $token_id = $token_helper->get_token_by_name($token_name)['id'];
        $date = $this->get_date_now();
        $transactions = $json_helper->read_json_file($this->transactionsFile);
        $transactions[] = ['id' => $random_id, 'token_id' => $token_id, 'wallet_id' => $user_id, 'date' => $date, 'amount' => $amount, 'type' => $type];
        $json_helper->write_json_file($this->transactionsFile, $transactions);
    }
}