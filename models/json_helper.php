<?php

class JsonHelper{
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
}
