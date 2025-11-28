<?php
namespace App\Models;
use App\Core\Database;
use PDO;

abstract class BaseModel {
    protected static function pdo(): PDO { return Database::pdo(); }
}
