<?php
$nome = $_POST["nome"];
$email = $_POST["email"];

$conn = new mysqli("localhost", "root", "senaisp", "livraria");

if ($conn->connect_error) {
    die("Erro: " . $conn->connect_error);
}

$sql = "INSERT INTO usuarios (nome, email) VALUES ('$nome', '$email')";
$conn->query($sql);
$conn->close();

header("Location: listar.php");
exit;
?>