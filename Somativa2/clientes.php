<?php include 'config.php'; ?>

<?php
$mensagem = '';

// Modo de edição
$editando = false;
$cliente_edit = null;

if(isset($_GET['editar'])) {
    $editando = true;
    $stmt = $pdo->prepare("SELECT * FROM clientes WHERE id_cliente = ?");
    $stmt->execute([$_GET['editar']]);
    $cliente_edit = $stmt->fetch();
}

// CRUD Operations
if($_POST){
    try {
        if(isset($_POST['adicionar'])){
            $stmt = $pdo->prepare("INSERT INTO clientes (nome, telefone, email) VALUES (?, ?, ?)");
            $stmt->execute([
                $_POST['nome'], 
                $_POST['telefone'], 
                $_POST['email']
            ]);
            $mensagem = mostrarMensagem('success', 'Cliente adicionado com sucesso!');
        }
        
        if(isset($_POST['editar'])){
            $stmt = $pdo->prepare("UPDATE clientes SET nome=?, telefone=?, email=? WHERE id_cliente=?");
            $stmt->execute([
                $_POST['nome'], 
                $_POST['telefone'], 
                $_POST['email'],
                $_POST['id_cliente']
            ]);
            $mensagem = mostrarMensagem('success', 'Cliente atualizado com sucesso!');
            $editando = false;
            $cliente_edit = null;
        }
        
        if(isset($_POST['cancelar_edicao'])){
            $editando = false;
            $cliente_edit = null;
        }
        
        if(isset($_POST['excluir'])){
            // Verificar se o cliente tem veículos antes de excluir
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM veiculos WHERE id_cliente = ?");
            $stmt->execute([$_POST['id_cliente']]);
            $tem_veiculos = $stmt->fetchColumn();
            
            if($tem_veiculos > 0) {
                $mensagem = mostrarMensagem('error', 'Não é possível excluir cliente que possui veículos cadastrados. Exclua os veículos primeiro.');
            } else {
                $stmt = $pdo->prepare("DELETE FROM clientes WHERE id_cliente = ?");
                $stmt->execute([$_POST['id_cliente']]);
                $mensagem = mostrarMensagem('success', 'Cliente excluído com sucesso!');
            }
        }
    } catch(Exception $e) {
        $mensagem = mostrarMensagem('error', 'Erro: ' . $e->getMessage());
    }
}

// Buscar clientes
$busca = '';
if(isset($_GET['busca'])){
    $busca = $_GET['busca'];
    $stmt = $pdo->prepare("SELECT * FROM clientes WHERE nome LIKE ? OR email LIKE ? ORDER BY nome");
    $stmt->execute(["%$busca%", "%$busca%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM clientes ORDER BY nome");
}
$clientes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Clientes - Oficina Mecânica</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Oficina Mecânica - Clientes</h1>
        
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
            <div class="header-actions">
                <h2><?php echo $editando ? 'Editar Cliente' : 'Adicionar Cliente'; ?></h2>
            </div>
            <form method="POST" class="form-inline">
                <?php if($editando): ?>
                    <input type="hidden" name="id_cliente" value="<?php echo $cliente_edit['id_cliente']; ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <input type="text" name="nome" placeholder="Nome completo" required
                           value="<?php echo $editando ? htmlspecialchars($cliente_edit['nome']) : ''; ?>">
                </div>
                <div class="form-group">
                    <input type="text" name="telefone" placeholder="Telefone"
                           value="<?php echo $editando ? htmlspecialchars($cliente_edit['telefone']) : ''; ?>">
                </div>
                <div class="form-group">
                    <input type="email" name="email" placeholder="E-mail"
                           value="<?php echo $editando ? htmlspecialchars($cliente_edit['email']) : ''; ?>">
                </div>
                <div class="form-group">
                    <?php if($editando): ?>
                        <button type="submit" name="editar" class="btn btn-success">Atualizar Cliente</button>
                        <button type="submit" name="cancelar_edicao" class="btn btn-danger">Cancelar</button>
                    <?php else: ?>
                        <button type="submit" name="adicionar" class="btn btn-success">Adicionar Cliente</button>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="card">
            <div class="header-actions">
                <h2>Lista de Clientes</h2>
                <form method="GET" class="form-inline">
                    <div class="form-group">
                        <input type="text" name="busca" placeholder="Buscar cliente..." value="<?php echo htmlspecialchars($busca); ?>">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn">Buscar</button>
                        <?php if($busca): ?>
                            <a href="clientes.php" class="btn btn-danger">Limpar</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <?php if(count($clientes) > 0): ?>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Telefone</th>
                            <th>Email</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($clientes as $cliente): ?>
                        <tr>
                            <td><?php echo $cliente['id_cliente']; ?></td>
                            <td><?php echo htmlspecialchars($cliente['nome']); ?></td>
                            <td><?php echo htmlspecialchars($cliente['telefone']); ?></td>
                            <td><?php echo htmlspecialchars($cliente['email']); ?></td>
                            <td class="action-buttons">
                                <a href="?editar=<?php echo $cliente['id_cliente']; ?>" class="btn btn-sm">Editar</a>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="id_cliente" value="<?php echo $cliente['id_cliente']; ?>">
                                    <button type="submit" name="excluir" class="btn btn-danger btn-sm" 
                                            onclick="return confirm('Tem certeza que deseja excluir o cliente <?php echo htmlspecialchars($cliente['nome']); ?>?')">
                                        Excluir
                                    </button>
                                </form>
                                <a href="veiculos.php?cliente=<?php echo $cliente['id_cliente']; ?>" class="btn btn-sm">Ver Veículos</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
                <p>Nenhum cliente encontrado.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>