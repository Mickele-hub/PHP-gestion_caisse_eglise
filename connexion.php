<?php
class Connect extends PDO {
    const HOST = "localhost";
    const DB = "projetphp";
    const USER = "root";
    const PASSWORD = "";

    public function __construct() {
        try {
            $dsn = "mysql:dbname=".self::DB.";host=".self::HOST;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            parent::__construct($dsn, self::USER, self::PASSWORD, $options);

            // Spécifier l'encodage UTF-8
            $this->exec("set names utf8");
        } catch (PDOException $e) {
            die("Connection échouer: " . $e->getMessage());
        }
    }
}
