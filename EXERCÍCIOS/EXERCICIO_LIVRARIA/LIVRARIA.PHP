<?php
// Comunicação com Banco de Dados

$mysqli = mysqli_connect('localhost', 'root', 'senaisp', 'LIVRARIA');

// Segurança em buscar valores no Banco

$columns = array('titulo', 'genero', 'preco');

// Trazer conteúdo do banco

$column = isset($_GET['column']) && in_array($_GET['column'], ($columns)) ? $_GET['column'] : $columns [0];

// Trazer dados em ordem e decrescente

$sort_order = isset($_GET['order']) && strtolower ($_GET['order']) == 'desc' ? 'DESC' : 'ASC';

// Verificar dados no Banco

if ($result = $mysqli->query('SELECT * FROM LIVROS ORDER BY ' , $column . '' . $sort_order)) {
    //Várias para a tabela
    $up_or_down = str_replace(array('ASC', 'DESC'), array('up', 'down'), $sort_order) == 'ASC' ? 'desc' : 'asc';
    $add_class = 'class="highlight';
}
?>
   <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Livros</title>
        <style>
            table, th, td {
                border: 1px solid black;
                border-collapse: collapse;
                padding: 8px;
            }
            th a {
                text-decoration: none;
                color: black;
            }
        </style>
    </head>
    <body>
        <table>
            <tr>
                <th><a href="index.php?column=titulo&order=<?php echo $asc_or_desc; ?>">Título <?php echo $column == 'titulo' ? $up_or_down : ''; ?></a></th>
                <th><a href="index.php?column=quantidade&order=<?php echo $asc_or_desc; ?>">Quantidade <?php echo $column == 'quantidade' ? $up_or_down : ''; ?></a></th>
                <th><a href="index.php?column=genero&order=<?php echo $asc_or_desc; ?>">Gênero <?php echo $column == 'genero' ? $up_or_down : ''; ?></a></th>
                <th><a href="index.php?column=preco&order=<?php echo $asc_or_desc; ?>">Preço <?php echo $column == 'preco' ? $up_or_down : ''; ?></a></th>
                <th><a href="index.php?column=id_autor&order=<?php echo $asc_or_desc; ?>">Autor <?php echo $column == 'id_autor' ? $up_or_down : ''; ?></a></th>
            </tr>

            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['titulo']; ?></td>
                    <td><?php echo $row['quantidade']; ?></td>
                    <td><?php echo $row['genero']; ?></td>
                    <td><?php echo $row['preco']; ?></td>
                    <td><?php echo $row['id_autor']; ?></td>
                </tr>
            <?php } ?>
        </table>
    </body>
    </html>
<?php
?>