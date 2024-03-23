<?php

namespace App\Models;

use App\Core\SqliteDB;

final class ShortUrl
{
    private SqliteDB $db;
    private string $tableName;
    public function __construct()
    {
        $this->db = new SqliteDB();
        $this->tableName = $this->db->getTableName();
    }

    public function create(string $LongURL): int
    {
        $this->db->exec("INSERT INTO $this->tableName (url) VALUES ('$LongURL')");
        return $this->db->lastInsertRowID();
    }

    public function getById($id): array|bool
    {
        return $this->db->query("SELECT url FROM $this->tableName WHERE id = '$id'")->fetchArray();
    }

    public function getByUrl($url): array|bool
    {
        return $this->db->query("SELECT id FROM $this->tableName WHERE url = '$url'")->fetchArray();
    }

}