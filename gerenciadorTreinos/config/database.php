<?php
$host = 'localhost';
$name = 'gerenciadorTreinos';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

function getDB(): PDO {
    static $pdo = null;

    if ($pdo === null) {
        $dsn = "mysql:host=" . $host . ";dbname=" . $name . ";charset=" . $charset;

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            http_response_code(500);
            die(json_encode(['erro' => 'Falha na conexão com o banco de dados.']));
        }
    }

    return $pdo;
}
