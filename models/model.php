<?php

class Model
{
    private $pricesFile = './db/prices.json';
    private $walletsFile = './db/wallets.json';
    private $tokensFile = './db/tokens.json';
    private $transactionsFile = './db/transactions.json';
    private $usersFile = './db/users.json';
    private $balanceFile = './db/balance.json';
    private function read_json_file(string $file): array {
        if (!file_exists($file)) {
            return [];
        }
        $json = file_get_contents($file);
        return json_decode($json, true);
    }

    private function write_json_file(string $file, array $data): void {
        $json = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents($file, $json);
    }

    public function get_date_now(): string {
        return date('Y-m-d H:i:s');
    }

    public function get_all_prices(int $limit = 50): array {
        $prices = $this->read_json_file($this->pricesFile);
        usort($prices, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
        return array_slice($prices, 0, $limit);
    }

    public function get_prices_by_token_id(int $id, int $limit = 10): array {
        $prices = $this->read_json_file($this->pricesFile);
        $filtered = array_filter($prices, function($price) use ($id) {
            return $price['token_id'] == $id;
        });
        usort($filtered, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
        return array_slice($filtered, 0, $limit);
    }

    public function get_price_by_id(int $id): ?array {
        $prices = $this->read_json_file($this->pricesFile);
        foreach ($prices as $price) {
            if ($price['id'] == $id) {
                return $price;
            }
        }
        return null;
    }

    public function get_prices_by_date_range(string $start, string $end): array {
        $prices = $this->read_json_file($this->pricesFile);
        $filtered = array_filter($prices, function($price) use ($start, $end) {
            return $price['date'] >= $start && $price['date'] <= $end;
        });
        usort($filtered, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
        return $filtered;
    }

    public function get_all_tokens(): array {
        return $this->read_json_file($this->tokensFile);
    }

    public function get_token_by_id(int $id): ?array {
        $tokens = $this->read_json_file($this->tokensFile);
        foreach ($tokens as $token) {
            if ($token['id'] == $id) {
                return $token;
            }
        }
        return null;
    }

    public function get_token_by_name(string $name): ?array {
        $tokens = $this->read_json_file($this->tokensFile);
        foreach ($tokens as $token) {
            if ($token['name'] == $name) {
                return $token;
            }
        }
        return null;
    }

    public function get_transactions(): array {
        return $this->read_json_file($this->transactionsFile);
    }

    public function get_transactions_by_token_id(int $id): array {
        $transactions = $this->read_json_file($this->transactionsFile);
        $filtered = array_filter($transactions, function($transaction) use ($id) {
            return $transaction['token_id'] == $id;
        });
        return $filtered;
    }

    public function get_current_price(int $id): ?array {
        $prices = $this->read_json_file($this->pricesFile);
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

    public function get_wallet_worth(int $user_id): array {
        $wallets = $this->read_json_file($this->walletsFile);
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

    public function get_token_wallet_amount(int $user_id, int $token_id): ?float {
        $wallets = $this->read_json_file($this->walletsFile);
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
        $users = $this->read_json_file($this->usersFile);
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
        $users = $this->read_json_file($this->usersFile);
        foreach ($users as $user) {
            if ($user['username'] == $username) {
                return false; // User already exists
            }
        }
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $users[] = ['username' => $username, 'hashed_password' => $hashed_password];
        $this->write_json_file($this->usersFile, $users);
        $this->add_wallet_balance($username);
        return true;
    }
    private function add_wallet_balance($user_id){
        $balance = $this->read_json_file($this->balanceFile);
        $balance[] = ['user_id' => $user_id, 'balance' => 1000];
        $this->write_json_file($this->balanceFile, $balance);
    }
    private function add_transaction($token_name, $type, $amount){
        session_start();
        $user_id = $_SESSION['user'];
        $random_id = rand(100000, 999999);
        $token_id = get_token_by_name($token_name)['id'];
        $date = get_date_now();
        $transactions = $this->read_json_file($this->transactionsFile);
        $transactions[] = ['id' => $random_id, 'token_id' => $token_id, 'wallet_id' => $user_id, 'date' => $date, 'amount' => $amount, 'type' => $type];
        $this->write_json_file($this->transactionsFile, $transactions);
    }
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
    private function withdraw_from_balance($amount){
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
    public function buy_token($amount, $token_name){
        session_start();
        $user_id = $_SESSION['user'];
        $balance = $this->get_wallet_balance();
        $token = get_token_by_name($token_name);
        $token_price = $token['price'];
        $token_id = $token['id'];
        $total_price = $amount * $token_price;
        if ($balance < $total_price) {
            return false;
        }
        $wallets = $this->read_json_file($this->walletsFile);
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
        $this->write_json_file($this->walletsFile, $wallets);
        $this->withdraw_from_balance($total_price);
        $this->add_transaction($token_name, 'buy', $amount);
        return true;
    }
    public function sell_token($amount, $token_name){
        session_start();
        $user_id = $_SESSION['user'];
        $token = get_token_by_name($token_name);
        $token_price = $token['price'];
        $token_id = $token['id'];
        $total_price = $amount * $token_price;
        $wallets = $this->read_json_file($this->walletsFile);
        foreach ($wallets as $wallet) {
            if ($wallet['user_id'] != $user_id) {
                continue;
            }
            foreach ($wallet['tokens'] as $token) {
                if ($token['token_id'] == $token_id && $token['amount'] >= $amount) {
                    $token['amount'] -= $amount;
                    $this->add_to_balance($total_price);
                    $this->write_json_file($this->walletsFile, $wallets);
                    $this->add_transaction($token_name, 'sell', $amount);
                    return true;
                }
            }
        }
        return false;
    }
}
?>