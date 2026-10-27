<?php

namespace App\Core;

use PDO;

/**
 * Basis semua model. Satu-satunya lapisan yang boleh menulis SQL.
 */
abstract class Model
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::pdo();
    }

    protected function satu(string $sql, array $param = []): ?array
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($param);
        $baris = $stmt->fetch();

        return $baris === false ? null : $baris;
    }

    protected function semua(string $sql, array $param = []): array
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($param);

        return $stmt->fetchAll();
    }

    protected function nilai(string $sql, array $param = []): mixed
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($param);

        return $stmt->fetchColumn();
    }

    protected function jalankan(string $sql, array $param = []): int
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($param);

        return $stmt->rowCount();
    }

    protected function sisip(string $sql, array $param = []): int
    {
        $this->jalankan($sql, $param);

        return (int) $this->db->lastInsertId();
    }
}
