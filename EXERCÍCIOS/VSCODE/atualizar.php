<?php
$conn = new mysqli("localhost", "root","senaisp", "livraria");

$id = $_POST['id'];
$nome = $_POST['nome'];
$email = $_POST['email'];

$sql = "UPDATE usuarios SET nome='$nome', email='$email' WHERE id=$id";
$conn->query($sql);
$conn->close();

header("Location: listar.php");
exit;
?>
