<?php
$conn = new mysqli("localhost", "root","senaisp", "livraria");
$result = $conn->query("SELECT * FROM usuarios");
?>

<head>
    <link rel="stylesheet" href="style.css">
</head>

<h2>Usuários Cadastrados</h2>

<table cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Email</th>
        <th>Ações</th>
    </tr>

<?php
while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['nome']}</td>
            <td>{$row['email']}</td>
            <td>
                <a href='editar.php?id={$row['id']}'>Editar</a> |
                <a href='deletar.php?id={$row['id']}'>Excluir</a>
            </td>
         </tr>";
}
$conn->close();
?>

</table>

<br>
<a href="index.html"><button>Voltar</button></a>