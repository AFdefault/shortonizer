<?php

namespace App\Core;

use SQLite3;

final class SqliteDB extends SQLite3
{
    public function __construct(
       private string $dbFile = 'sqlite.db',
       private string $tableName = 'short_links'
    ) {
        parent::__construct('./App/'.$this->dbFile);

        $table = $this->query("SELECT name FROM sqlite_master WHERE type='table' AND name='$this->tableName'");

        if(!$table->fetchArray()) {
            $this->exec("CREATE TABLE $this->tableName (id  INTEGER PRIMARY KEY AUTOINCREMENT, url TEXT)");
        }

        return $this;
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }

}