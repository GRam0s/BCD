<?php
$conn = new mysqli("localhost", "root","senaisp", "livraria");
$id = $_GET['id'];
$result = $conn->query("SELECT * FROM usuarios WHERE id = $id");
$row = $result->fetch_assoc();
?>
<head>
    <link rel="stylesheet" href="style.css">
</head>
<h2>Editar Usuário</h2>

<form action="atualizar.php" method="POST">
    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

    Nome: <input type="text" name="nome" value="<?php echo $row['nome']; ?>"><br><br>
    Email: <input type="email" name="email" value="<?php echo $row['email']; ?>"><br><br>

    <button type="submit">Salvar</button>
</form>

<br>
<a href="listar.php"><button>Cancelar</button></a>
