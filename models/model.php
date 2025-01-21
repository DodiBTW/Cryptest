<?php
require_once 'db_connect.php';
class Model
{
    protected $db;

    public function __construct()
    {
        $this->db = get_db_connection();
    }
    public function __destruct()
    {
        $this->db->close();
    }
    public function get_date_now(){
        return date('Y-m-d H:i:s');
    }

    public function get_all_prices($limit){
        $stmt = $this->db->prepare("SELECT * FROM prices ORDER BY date DESC LIMIT :limit");
        $stmt->bindValue(':limit', $limit, SQLITE3_INTEGER);
        $result = $stmt->execute();
        $prices = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $prices[] = $row;
        }
        return $prices;
    }
    public function get_prices_by_token_id($id, $limit){
        $stmt = $this->db->prepare("SELECT * FROM prices WHERE token_id = :id LIMIT :limit");
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $stmt->bindValue(':limit', $limit, SQLITE3_INTEGER);
        $result = $stmt->execute();
        $prices = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $prices[] = $row;
        }
        return $prices;
    }
    public function get_price_by_id($id){
        $stmt = $this->db->prepare("SELECT * FROM prices WHERE id = :id");
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $result = $stmt->execute();
        return $result->fetchArray(SQLITE3_ASSOC);
    }
    public function get_prices_by_date_range($start, $end){
        $stmt = $this->db->prepare("SELECT * FROM prices WHERE date >= :start AND date <= :end");
        $stmt->bindValue(':start', $start, SQLITE3_TEXT);
        $stmt->bindValue(':end', $end, SQLITE3_TEXT);
        $result = $stmt->execute();
        $prices = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $prices[] = $row;
        }
        return $prices;
    }
    public function get_all_tokens(){
        $result = $this->db->query("SELECT * FROM tokens");
        $tokens = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $tokens[] = $row;
        }
        return $tokens;
    }
    public function get_token_by_id($id){
        $stmt = $this->db->prepare("SELECT * FROM tokens WHERE id = :id");
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $result = $stmt->execute();
        return $result->fetchArray(SQLITE3_ASSOC);
    }
    public function get_token_by_name($name){
        $stmt = $this->db->prepare("SELECT * FROM tokens WHERE name = :name");
        $stmt->bindValue(':name', $name, SQLITE3_TEXT);
        $result = $stmt->execute();
        return $result->fetchArray(SQLITE3_ASSOC);
    }
    public function get_transactions(){
        $stmt = $this->db->prepare("SELECT * FROM transactions");
        $result = $stmt->execute();
        $transactions = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $transactions[] = $row;
        }
        return $transactions;
    }
    public function get_transactions_by_token_id($id){
        $stmt = $this->db->prepare("SELECT * FROM transactions INNER JOIN tokens ON prices.id = price_id WHERE prices.token_id = :id");
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $result = $stmt->execute();
        $transactions = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $transactions[] = $row;
        }
        return $transactions;
    }
    public function get_current_price($id){
        $stmt = $this->db->prepare("SELECT * FROM prices WHERE token_id = :id ORDER BY date DESC LIMIT 1");
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $result = $stmt->execute();
        return $result->fetchArray(SQLITE3_ASSOC);
    }
    public function get_wallet_worth(){
        $stmt = $this->db->prepare("SELECT * FROM wallets");
        $result = $stmt->execute();
        $wallets = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $token_id = $row['token_id'];
            $token_price = $this->get_current_price($token_id);
            if ($token_price) {
                $token_prices[$token_id] = $token_price['price'];
            }
        }
        return $token_prices;
    }
}