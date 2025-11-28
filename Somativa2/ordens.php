<?php include 'config.php'; ?>

<?php
$mensagem = '';

if($_POST){
    try {
        if(isset($_POST['adicionar'])){
            // Criar a OS
            $stmt = $pdo->prepare("INSERT INTO ordens_servico (id_veiculo, data_abertura, observacoes) VALUES (?, CURDATE(), ?)");
            $stmt->execute([$_POST['id_veiculo'], $_POST['observacoes']]);
            $id_os = $pdo->lastInsertId();
            
            // Adicionar serviços selecionados
            if(isset($_POST['servicos'])){
                foreach($_POST['servicos'] as $servico_id){
                    $stmt = $pdo->prepare("INSERT INTO os_servicos (id_os, id_servico) VALUES (?, ?)");
                    $stmt->execute([$id_os, $servico_id]);
                }
            }
            
            // Adicionar mecânicos
            if(isset($_POST['mecanicos'])){
                foreach($_POST['mecanicos'] as $mecanico_id){
                    $stmt = $pdo->prepare("INSERT INTO os_mecanicos (id_os, id_mecanico) VALUES (?, ?)");
                    $stmt->execute([$id_os, $mecanico_id]);
                }
            }
            
            $mensagem = mostrarMensagem('success', "OS criada com sucesso! Número: #$id_os");
        }
        
        if(isset($_POST['concluir'])){
            $stmt = $pdo->prepare("UPDATE ordens_servico SET status = 'Concluída' WHERE id_os = ?");
            $stmt->execute([$_POST['id_os']]);
            $mensagem = mostrarMensagem('success', "OS #{$_POST['id_os']} concluída!");
        }
        
        if(isset($_POST['cancelar'])){
            $stmt = $pdo->prepare("UPDATE ordens_servico SET status = 'Cancelada' WHERE id_os = ?");
            $stmt->execute([$_POST['id_os']]);
            $mensagem = mostrarMensagem('success', "OS #{$_POST['id_os']} cancelada!");
        }
    } catch(Exception $e) {
        $mensagem = mostrarMensagem('error', 'Erro: ' . $e->getMessage());
    }
}

// Filtro por status
$status_filtro = '';
$where = '';
if(isset($_GET['status']) && $_GET['status'] != '') {
    $status_filtro = $_GET['status'];
    $where = "WHERE os.status = ?";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ordens de Serviço - Oficina Mecânica</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Ordens de Serviço - Oficina Mecânica</h1>
        
        <nav>
            <a href="index.php">Início</a>
            <a href="clientes.php">Clientes</a>
            <a href="veiculos.php">Veículos</a>
            <a href="ordens.php">Ordens de Serviço</a>
            <a href="mecanicos.php">Mecânicos</a>
            <a href="servicos.php">Serviços</a>
        </nav>

        <?php echo $mensagem; ?>

        <div class="card">
            <h2>Nova Ordem de Serviço</h2>
            <form method="POST">
                <div class="form-group">
                    <label>Selecionar Veículo</label>
                    <select name="id_veiculo" required>
                        <option value="">Selecione o veículo</option>
                        <?php
                        $sql = "SELECT v.*, c.nome FROM veiculos v JOIN clientes c ON v.id_cliente = c.id_cliente";
                        $veiculos = $pdo->query($sql);
                        while($veiculo = $veiculos->fetch()){
                            echo "<option value='{$veiculo['id_veiculo']}'>{$veiculo['nome']} - {$veiculo['marca']} {$veiculo['modelo']} ({$veiculo['placa']})</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Selecionar Serviços</label>
                    <div style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd; padding: 10px;">
                        <?php
                        $servicos = $pdo->query("SELECT * FROM servicos ORDER BY nome_servico");
                        while($servico = $servicos->fetch()):
                        ?>
                        <div class="service-item">
                            <label style="display: block; margin-bottom: 5px;">
                                <input type="checkbox" name="servicos[]" value="<?php echo $servico['id_servico']; ?>">
                                <strong><?php echo $servico['nome_servico']; ?></strong>
                            </label>
                            <div class="service-info">
                                <span><?php echo $servico['descricao']; ?></span>
                                <span class="service-price"><?php echo formatarMoeda($servico['preco_mao_obra']); ?></span>
                            </div>
                            <small>Tempo estimado: <?php echo $servico['tempo_estimado']; ?></small>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label>Atribuir Mecânicos</label>
                    <div style="border: 1px solid #ddd; padding: 10px;">
                        <?php
                        $mecanicos = $pdo->query("SELECT * FROM mecanicos ORDER BY nome");
                        while($mecanico = $mecanicos->fetch()){
                            echo "<label style='display: block; margin: 5px 0;'>
                                    <input type='checkbox' name='mecanicos[]' value='{$mecanico['id_mecanico']}'>
                                    {$mecanico['nome']} - {$mecanico['especialidade']}
                                  </label>";
                        }
                        ?>
                    </div>
                </div>

                <div class="form-group">
                    <label>Observações</label>
                    <textarea name="observacoes" placeholder="Descreva o problema ou observações adicionais..." rows="4"></textarea>
                </div>
                
                <button type="submit" name="adicionar" class="btn btn-success">Criar Ordem de Serviço</button>
            </form>
        </div>

        <div class="card">
            <div class="header-actions">
                <h2>Ordens de Serviço</h2>
                <form method="GET" class="form-inline">
                    <div class="form-group">
                        <select name="status">
                            <option value="">Todos os status</option>
                            <option value="Aberta" <?php echo $status_filtro == 'Aberta' ? 'selected' : ''; ?>>Aberta</option>
                            <option value="Em Andamento" <?php echo $status_filtro == 'Em Andamento' ? 'selected' : ''; ?>>Em Andamento</option>
                            <option value="Concluída" <?php echo $status_filtro == 'Concluída' ? 'selected' : ''; ?>>Concluída</option>
                            <option value="Cancelada" <?php echo $status_filtro == 'Cancelada' ? 'selected' : ''; ?>>Cancelada</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn">Filtrar</button>
                        <?php if($status_filtro): ?>
                            <a href="ordens.php" class="btn btn-danger">Limpar</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>OS</th>
                            <th>Veículo</th>
                            <th>Cliente</th>
                            <th>Data</th>
                            <th>Status</th>
                            <th>Serviços</th>
                            <th>Mecânicos</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT os.*, v.marca, v.modelo, v.placa, c.nome as cliente,
                                GROUP_CONCAT(DISTINCT s.nome_servico SEPARATOR ', ') as servicos,
                                GROUP_CONCAT(DISTINCT m.nome SEPARATOR ', ') as mecanicos
                                FROM ordens_servico os
                                JOIN veiculos v ON os.id_veiculo = v.id_veiculo
                                JOIN clientes c ON v.id_cliente = c.id_cliente
                                LEFT JOIN os_servicos oss ON os.id_os = oss.id_os
                                LEFT JOIN servicos s ON oss.id_servico = s.id_servico
                                LEFT JOIN os_mecanicos om ON os.id_os = om.id_os
                                LEFT JOIN mecanicos m ON om.id_mecanico = m.id_mecanico
                                $where
                                GROUP BY os.id_os
                                ORDER BY os.id_os DESC";
                        
                        if($status_filtro) {
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([$status_filtro]);
                        } else {
                            $stmt = $pdo->query($sql);
                        }
                        
                        while($os = $stmt->fetch()):
                        ?>
                        <tr>
                            <td>#<?php echo $os['id_os']; ?></td>
                            <td><?php echo $os['marca'] . ' ' . $os['modelo'] . ' (' . $os['placa'] . ')'; ?></td>
                            <td><?php echo $os['cliente']; ?></td>
                            <td><?php echo formatarData($os['data_abertura']); ?></td>
                            <td><?php echo getStatusBadge($os['status']); ?></td>
                            <td><?php echo $os['servicos'] ?: 'Nenhum serviço'; ?></td>
                            <td><?php echo $os['mecanicos'] ?: 'Nenhum mecânico'; ?></td>
                            <td class="action-buttons">
                                <?php if($os['status'] == 'Aberta' || $os['status'] == 'Em Andamento'): ?>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="id_os" value="<?php echo $os['id_os']; ?>">
                                        <button type="submit" name="concluir" class="btn btn-success btn-sm">Concluir</button>
                                    </form>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="id_os" value="<?php echo $os['id_os']; ?>">
                                        <button type="submit" name="cancelar" class="btn btn-danger btn-sm" 
                                                onclick="return confirm('Tem certeza que deseja cancelar a OS #<?php echo $os['id_os']; ?>?')">
                                            Cancelar
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>