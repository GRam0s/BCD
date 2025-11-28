<?php include 'config.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Oficina Mecânica</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Oficina Mecânica</h1>
        </header>
        
        <nav>
            <a href="index.php">Início</a>
            <a href="clientes.php">Clientes</a>
            <a href="veiculos.php">Veículos</a>
            <a href="ordens.php">Ordens de Serviço</a>
            <a href="mecanicos.php">Mecânicos</a>
            <a href="servicos.php">Serviços</a>
        </nav>

        <div class="grid">
            <div class="card card-stat">
                <h3>Total de Clientes</h3>
                <p><?php echo $pdo->query("SELECT COUNT(*) FROM clientes")->fetchColumn(); ?></p>
            </div>
            <div class="card card-stat">
                <h3>Total de Veículos</h3>
                <p><?php echo $pdo->query("SELECT COUNT(*) FROM veiculos")->fetchColumn(); ?></p>
            </div>
            <div class="card card-stat">
                <h3>Ordens Ativas</h3>
                <p><?php echo $pdo->query("SELECT COUNT(*) FROM ordens_servico WHERE status IN ('Aberta', 'Em Andamento')")->fetchColumn(); ?></p>
            </div>
            <div class="card card-stat">
                <h3>Total de OS</h3>
                <p><?php echo $pdo->query("SELECT COUNT(*) FROM ordens_servico")->fetchColumn(); ?></p>
            </div>
        </div>

        <div class="card">
            <h2>Últimas Ordens de Serviço</h2>
            <?php
            $sql = "SELECT os.id_os, c.nome, v.marca, v.modelo, os.status 
                    FROM ordens_servico os 
                    JOIN veiculos v ON os.id_veiculo = v.id_veiculo 
                    JOIN clientes c ON v.id_cliente = c.id_cliente 
                    ORDER BY os.id_os DESC LIMIT 10";
            $result = $pdo->query($sql);
            ?>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>OS</th>
                            <th>Cliente</th>
                            <th>Veículo</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch()): ?>
                        <tr>
                            <td>#<?php echo $row['id_os']; ?></td>
                            <td><?php echo htmlspecialchars($row['nome']); ?></td>
                            <td><?php echo htmlspecialchars($row['marca'] . ' ' . $row['modelo']); ?></td>
                            <td><?php echo getStatusBadge($row['status']); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>