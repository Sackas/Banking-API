<?php

class Bank {
    private array $accounts;

    public function __construct() {
        $this->accounts = Storage::getAccounts();
    }

    public function getBalance(string $accountId): ?int {
        return $this->accounts[$accountId] ?? null;
    }

    public function handleEvent(array $data): ?array {
        switch ($data['type']) {
            case 'deposit':
                return $this->deposit($data['destination'], $data['amount']);
            case 'withdraw':
                return $this->withdraw($data['origin'], $data['amount']);
            case 'transfer':
                return $this->transfer($data['origin'], $data['amount'], $data['destination']);
            default:
                return null;
        }
    }

    private function deposit(string $destination, int $amount): array {
        $this->accounts[$destination] = ($this->accounts[$destination] ?? 0) + $amount;
        Storage::saveAccounts($this->accounts);
        return ['destination' => ['id' => $destination, 'balance' => $this->accounts[$destination]]];
    }

    private function withdraw(string $origin, int $amount): ?array {
        if (!isset($this->accounts[$origin]) || $this->accounts[$origin] < $amount) {
            return null;
        }
        $this->accounts[$origin] -= $amount;
        Storage::saveAccounts($this->accounts);
        return ['origin' => ['id' => $origin, 'balance' => $this->accounts[$origin]]];
    }

    private function transfer(string $origin, int $amount, string $destination): ?array {
        if (!isset($this->accounts[$origin]) || $this->accounts[$origin] < $amount) {
            return null;
        }
        $this->accounts[$origin] -= $amount;
        $this->accounts[$destination] = ($this->accounts[$destination] ?? 0) + $amount;
        Storage::saveAccounts($this->accounts);
        return [
            'origin' => ['id' => $origin, 'balance' => $this->accounts[$origin]],
            'destination' => ['id' => $destination, 'balance' => $this->accounts[$destination]]
        ];
    }
}
