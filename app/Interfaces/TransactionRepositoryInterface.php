<?php

namespace App\Interfaces;
use App\Models\Transaction; // <<< PASTIKAN BARIS INI ADA
// Jika Anda punya model Room, BoardingHouse, User, dll. yang digunakan di sini,
// maka mereka juga perlu diimpor. Tapi untuk metode yang Anda berikan, cukup Transaction.

interface TransactionRepositoryInterface
{
    public function getTransactionDataFromSession(): ?array; // Menambahkan return type, bisa null
    public function saveTransactionDataToSession(array $data): void; // Menambahkan type hint parameter dan return type

    // <<< UBAH BARIS INI AGAR SESUAI DENGAN IMPLEMENTASI DI REPOSITORY >>>
    public function saveTransaction(array $data): Transaction;

    public function getTransactionByCode(string $code): ?Transaction; // Menambahkan type hint parameter dan return type
    public function getTransactionByCodeEmailPhone(string $code, string $email, string $phone): ?Transaction; // Menambahkan type hint parameter dan return type
}