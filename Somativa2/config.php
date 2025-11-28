<?php
session_start();

$host = 'localhost';
$dbname = 'oficina_mecanica';
$username = 'root';
$password = 'senaisp';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->exec("SET NAMES utf8");
} catch(PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}

// Funções úteis
function formatarData($data) {
    return $data ? date('d/m/Y', strtotime($data)) : '';
}

function formatarMoeda($valor) {
    return 'R$ ' . number_format($valor, 2, ',', '.');
}

// Incluir funções auxiliares
include 'funcoes.php';
?>