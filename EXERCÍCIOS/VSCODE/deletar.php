<?php
$conn = new mysqli("localhost", "root","senaisp", "livraria");
$id = $_GET["id"];

$stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();
$conn->close();

header("Location: listar.php");
exit;
?>
