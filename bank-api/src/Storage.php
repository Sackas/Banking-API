<?php

class Storage {
    private static string $file = __DIR__ . '/../data/accounts.json';

    public static function reset(): void {
        file_put_contents(self::$file, json_encode([]));
    }

    public static function getAccounts(): array {
        if (!file_exists(self::$file)) {
            return [];
        }
        return json_decode(file_get_contents(self::$file), true);
    }

    public static function saveAccounts(array $accounts): void {
        file_put_contents(self::$file, json_encode($accounts));
    }
}
