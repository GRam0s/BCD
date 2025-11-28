<?php include 'config.php'; ?>

<?php
$mensagem = '';

// Modo de edição
$editando = false;
$veiculo_edit = null;

if(isset($_GET['editar'])) {
    $editando = true;
    $stmt = $pdo->prepare("SELECT v.*, c.nome as cliente_nome FROM veiculos v 
                          JOIN clientes c ON v.id_cliente = c.id_cliente 
                          WHERE v.id_veiculo = ?");
    $stmt->execute([$_GET['editar']]);
    $veiculo_edit = $stmt->fetch();
}

if($_POST){
    try {
        if(isset($_POST['adicionar'])){
            // Verificar se placa já existe
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM veiculos WHERE placa = ?");
            $stmt->execute([$_POST['placa']]);
            if($stmt->fetchColumn() > 0){
                $mensagem = mostrarMensagem('error', 'Placa já cadastrada!');
            } else {
                $stmt = $pdo->prepare("INSERT INTO veiculos (id_cliente, marca, modelo, ano, placa) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$_POST['id_cliente'], $_POST['marca'], $_POST['modelo'], $_POST['ano'], $_POST['placa']]);
                $mensagem = mostrarMensagem('success', 'Veículo adicionado com sucesso!');
            }
        }
        
        if(isset($_POST['editar'])){
            // Verificar se placa já existe (excluindo o veículo atual)
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM veiculos WHERE placa = ? AND id_veiculo != ?");
            $stmt->execute([$_POST['placa'], $_POST['id_veiculo']]);
            if($stmt->fetchColumn() > 0){
                $mensagem = mostrarMensagem('error', 'Placa já cadastrada em outro veículo!');
            } else {
                $stmt = $pdo->prepare("UPDATE veiculos SET id_cliente=?, marca=?, modelo=?, ano=?, placa=? WHERE id_veiculo=?");
                $stmt->execute([$_POST['id_cliente'], $_POST['marca'], $_POST['modelo'], $_POST['ano'], $_POST['placa'], $_POST['id_veiculo']]);
                $mensagem = mostrarMensagem('success', 'Veículo atualizado com sucesso!');
                $editando = false;
                $veiculo_edit = null;
            }
        }
        
        if(isset($_POST['cancelar_edicao'])){
            $editando = false;
            $veiculo_edit = null;
        }
        
        if(isset($_POST['excluir'])){
            // Verificar se veículo tem ordens de serviço
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM ordens_servico WHERE id_veiculo = ?");
            $stmt->execute([$_POST['id_veiculo']]);
            if($stmt->fetchColumn() > 0){
                $mensagem = mostrarMensagem('error', 'Não é possível excluir veículo com ordens de serviço!');
            } else {
                $stmt = $pdo->prepare("DELETE FROM veiculos WHERE id_veiculo = ?");
                $stmt->execute([$_POST['id_veiculo']]);
                $mensagem = mostrarMensagem('success', 'Veículo excluído com sucesso!');
            }
        }
    } catch(Exception $e) {
        $mensagem = mostrarMensagem('error', 'Erro: ' . $e->getMessage());
    }
}

// Buscar veículos
$busca = '';
$cliente_filtro = '';
if(isset($_GET['busca'])){
    $busca = $_GET['busca'];
    $sql = "SELECT v.*, c.nome as cliente FROM veiculos v 
            JOIN clientes c ON v.id_cliente = c.id_cliente 
            WHERE v.placa LIKE ? OR v.marca LIKE ? OR v.modelo LIKE ? OR c.nome LIKE ?
            ORDER BY c.nome, v.marca";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["%$busca%", "%$busca%", "%$busca%", "%$busca%"]);
} elseif(isset($_GET['cliente'])) {
    $cliente_filtro = $_GET['cliente'];
    $sql = "SELECT v.*, c.nome as cliente FROM veiculos v 
            JOIN clientes c ON v.id_cliente = c.id_cliente 
            WHERE v.id_cliente = ?
            ORDER BY v.marca";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$cliente_filtro]);
} else {
    $stmt = $pdo->query("SELECT v.*, c.nome as cliente FROM veiculos v JOIN clientes c ON v.id_cliente = c.id_cliente ORDER BY c.nome, v.marca");
}
$veiculos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Veículos - Oficina Mecânica</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Veículos - Oficina Mecânica</h1>
        
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
            <h2><?php echo $editando ? 'Editar Veículo' : 'Adicionar Veículo'; ?></h2>
            <form method="POST">
                <?php if($editando): ?>
                    <input type="hidden" name="id_veiculo" value="<?php echo $veiculo_edit['id_veiculo']; ?>">
                <?php endif; ?>
                
                <div class="form-inline">
                    <div class="form-group">
                        <label>Cliente</label>
                        <select name="id_cliente" required>
                            <option value="">Selecione o cliente</option>
                            <?php
                            $clientes = $pdo->query("SELECT * FROM clientes ORDER BY nome");
                            while($cliente = $clientes->fetch()){
                                $selected = $editando && $veiculo_edit['id_cliente'] == $cliente['id_cliente'] ? 'selected' : '';
                                echo "<option value='{$cliente['id_cliente']}' $selected>{$cliente['nome']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Marca</label>
                        <input type="text" name="marca" placeholder="Marca" required
                               value="<?php echo $editando ? htmlspecialchars($veiculo_edit['marca']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Modelo</label>
                        <input type="text" name="modelo" placeholder="Modelo" required
                               value="<?php echo $editando ? htmlspecialchars($veiculo_edit['modelo']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Ano</label>
                        <input type="number" name="ano" placeholder="Ano" min="1900" max="2030"
                               value="<?php echo $editando ? $veiculo_edit['ano'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Placa</label>
                        <input type="text" name="placa" placeholder="Placa" required style="text-transform: uppercase;"
                               value="<?php echo $editando ? htmlspecialchars($veiculo_edit['placa']) : ''; ?>">
                    </div>
                </div>
                <div class="form-group" style="margin-top: 15px;">
                    <?php if($editando): ?>
                        <button type="submit" name="editar" class="btn btn-success">Atualizar Veículo</button>
                        <button type="submit" name="cancelar_edicao" class="btn btn-danger">Cancelar</button>
                    <?php else: ?>
                        <button type="submit" name="adicionar" class="btn btn-success">Adicionar Veículo</button>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="card">
            <div class="header-actions">
                <h2>Lista de Veículos</h2>
                <form method="GET" class="form-inline">
                    <div class="form-group">
                        <input type="text" name="busca" placeholder="Buscar veículo..." value="<?php echo htmlspecialchars($busca); ?>">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn">Buscar</button>
                        <?php if($busca || $cliente_filtro): ?>
                            <a href="veiculos.php" class="btn btn-danger">Limpar</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <?php if(count($veiculos) > 0): ?>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Marca/Modelo</th>
                            <th>Ano</th>
                            <th>Placa</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($veiculos as $veiculo): ?>
                        <tr>
                            <td><?php echo $veiculo['id_veiculo']; ?></td>
                            <td><?php echo htmlspecialchars($veiculo['cliente']); ?></td>
                            <td><?php echo htmlspecialchars($veiculo['marca'] . ' ' . $veiculo['modelo']); ?></td>
                            <td><?php echo $veiculo['ano']; ?></td>
                            <td><?php echo $veiculo['placa']; ?></td>
                            <td class="action-buttons">
                                <a href="?editar=<?php echo $veiculo['id_veiculo']; ?>" class="btn btn-sm">Editar</a>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="id_veiculo" value="<?php echo $veiculo['id_veiculo']; ?>">
                                    <button type="submit" name="excluir" class="btn btn-danger btn-sm" 
                                            onclick="return confirm('Tem certeza que deseja excluir o veículo <?php echo htmlspecialchars($veiculo['marca'] . ' ' . $veiculo['modelo']); ?>?')">
                                        Excluir
                                    </button>
                                </form>
                                <a href="ordens.php?veiculo=<?php echo $veiculo['id_veiculo']; ?>" class="btn btn-sm">Nova OS</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
                <p>Nenhum veículo encontrado.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>