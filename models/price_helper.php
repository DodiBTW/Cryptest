<?php
require_once 'json_helper.php';
class PriceHelper{
    private $pricesFile = './db/prices.json';

    public function get_all_prices(int $limit = 50): array {
        $json_helper = new JsonHelper();
        $prices = $json_helper->read_json_file($this->pricesFile);
        usort($prices, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
        return array_slice($prices, 0, $limit);
    }

    public function get_current_price(int $id): ?array {
        $json_helper = new JsonHelper();
        $prices = $json_helper->read_json_file($this->pricesFile);
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

    public function get_prices_by_token_id(int $id, int $limit = 10): array {
        $json_helper = new JsonHelper();
        $prices = $json_helper->read_json_file($this->pricesFile);
        $filtered = array_filter($prices, function($price) use ($id) {
            return $price['token_id'] == $id;
        });
        usort($filtered, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
        return array_slice($filtered, 0, $limit);
    }

    public function get_price_by_id(int $id): ?array {
        $json_helper = new JsonHelper();
        $prices = $json_helper->read_json_file($this->pricesFile);
        foreach ($prices as $price) {
            if ($price['id'] == $id) {
                return $price;
            }
        }
        return null;
    }

    public function get_prices_by_date_range(string $start, string $end): array {
        $json_helper = new JsonHelper();
        $prices = $json_helper->read_json_file($this->pricesFile);
        $filtered = array_filter($prices, function($price) use ($start, $end) {
            return $price['date'] >= $start && $price['date'] <= $end;
        });
        usort($filtered, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
        return $filtered;
    }
}