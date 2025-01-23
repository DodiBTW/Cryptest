<?php

class BalanceHelper{
    private $balanceFile = './db/balance.json';

    public function get_wallet_balance(){
        session_start();
        $user_id = $_SESSION['user'];
        $balance = $this->read_json_file($this->balanceFile);
        foreach ($balance as $bal) {
            if ($bal['user_id'] == $user_id) {
                return $bal['balance'];
            }
        }
        return null;
    }
    public function add_wallet_balance($user_id){
        $balance = $this->read_json_file($this->balanceFile);
        $balance[] = ['user_id' => $user_id, 'balance' => 1000];
        $this->write_json_file($this->balanceFile, $balance);
    }
    public function add_to_balance($amount){
        session_start();
        $user_id = $_SESSION['user'];
        $balance = $this->read_json_file($this->balanceFile);
        foreach ($balance as $bal) {
            if ($bal['user_id'] == $user_id) {
                $bal['balance'] += $amount;
                $this->write_json_file($this->balanceFile, $balance);
                return $bal['balance'];
            }
        }
        return null;
    }
    public function withdraw_from_balance($amount){
        session_start();
        $user_id = $_SESSION['user'];
        $balance = $this->read_json_file($this->balanceFile);
        foreach ($balance as $bal) {
            if ($bal['user_id'] != $user_id) {
                continue;
            }
            $bal['balance'] -= $amount;
            $this->write_json_file($this->balanceFile, $balance);
            return $bal['balance'];
        }
        return null;
    }
}