<?php
require_once 'json_helper.php';
class BalanceHelper{
    private $balanceFile = './db/balance.json';

    public function get_wallet_balance(){
        $json_helper = new JsonHelper();
        session_start();
        $user_id = $_SESSION['user'];
        $balance = $json_helper->read_json_file($this->balanceFile);
        foreach ($balance as $bal) {
            if ($bal['user_id'] == $user_id) {
                return $bal['balance'];
            }
        }
        return null;
    }
    public function add_wallet_balance($user_id){
        $balance = $json_helper->read_json_file($this->balanceFile);
        $balance[] = ['user_id' => $user_id, 'balance' => 1000];
        $json_helper->write_json_file($this->balanceFile, $balance);
    }
    public function add_to_balance($amount){
        $json_helper = new JsonHelper();
        session_start();
        $user_id = $_SESSION['user'];
        $balance = $json_helper->read_json_file($this->balanceFile);
        foreach ($balance as $bal) {
            if ($bal['user_id'] == $user_id) {
                $bal['balance'] += $amount;
                $json_helper->write_json_file($this->balanceFile, $balance);
                return $bal['balance'];
            }
        }
        return null;
    }
    public function withdraw_from_balance($amount){
        $json_helper = new JsonHelper();
        session_start();
        $user_id = $_SESSION['user'];
        $balance = $json_helper->read_json_file($this->balanceFile);
        foreach ($balance as $bal) {
            if ($bal['user_id'] != $user_id) {
                continue;
            }
            $bal['balance'] -= $amount;
            $json_helper->write_json_file($this->balanceFile, $balance);
            return $bal['balance'];
        }
        return null;
    }
}