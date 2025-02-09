<?php
require_once('json_helper.php');
require_once('wallet_helper.php');
class UserHelper{
    private $usersFile = './db/users.json';
    
    public function check_login() : bool {
        session_start();
        return isset($_SESSION['user']);
    }
    public function get_user_id() : ?string {
        session_start();
        if (!isset($_SESSION['user'])) {
            return null;
        }
        return $_SESSION['user'];
    }
    public function login($username, $password) : bool {
        $json_helper = new JsonHelper();
        $users = $json_helper->read_json_file($this->usersFile);
        $filtered = array_filter($users, function($user) use ($username, $password) {
            return $user['username'] == $username && password_verify($password, $user['hashed_password']);
        });
        if(empty($filtered)){
            return false;
        }
        session_start();
        $_SESSION['user'] = $username;
        return true;
    }

    public function register($username, $password) : bool {
        $json_helper = new JsonHelper();
        $wallet_helper = new WalletHelper();
        $users = $json_helper->read_json_file($this->usersFile);
        $filtered = array_filter($users, function($user) use ($username) {
            return $user['username'] == $username;
        });
        if (!empty($filtered)) {
            return false;
        }
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $id = uniqid();
        $users[] = ['id' => $id, 'username' => $username, 'hashed_password' => $hashed_password];
        $json_helper->write_json_file($this->usersFile, $users);
        $wallet_helper->add_wallet_balance($username);
        return true;
    }
    public function logout(){
        session_start();
        session_destroy();
    }
}