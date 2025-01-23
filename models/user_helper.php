<?php
require_once('json_helper.php');
require_once('wallet_helper.php');
class UserHelper{
    private $usersFile = './db/users.json';
    
    public function check_login(){
        session_start();
        return isset($_SESSION['user']);
    }
    public function get_user_id(){
        session_start();
        return $_SESSION['user'];
    }
    public function login($username, $password){
        $json_helper = new JsonHelper();
        $users = $json_helper->read_json_file($this->usersFile);
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
        $json_helper = new JsonHelper();
        $wallet_helper = new WalletHelper();
        $users = $json_helper->read_json_file($this->usersFile);
        foreach ($users as $user) {
            if ($user['username'] == $username) {
                return false;
            }
        }
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $users[] = ['username' => $username, 'hashed_password' => $hashed_password];
        $json_helper->write_json_file($this->usersFile, $users);
        $wallet_helper->add_wallet_balance($username);
        return true;
    }
    public function logout(){
        session_start();
        session_destroy();
    }
}