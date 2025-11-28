<?php include 'config.php'; ?>

<?php
$mensagem = '';

// Modo de edição
$editando = false;
$servico_edit = null;

if(isset($_GET['editar'])) {
    $editando = true;
    $stmt = $pdo->prepare("SELECT * FROM servicos WHERE id_servico = ?");
    $stmt->execute([$_GET['editar']]);
    $servico_edit = $stmt->fetch();
}

if($_POST){
    try {
        if(isset($_POST['adicionar'])){
            $stmt = $pdo->prepare("INSERT INTO servicos (nome_servico, descricao, preco_mao_obra, tempo_estimado) VALUES (?, ?, ?, ?)");
            $stmt->execute([$_POST['nome_servico'], $_POST['descricao'], $_POST['preco_mao_obra'], $_POST['tempo_estimado']]);
            $mensagem = mostrarMensagem('success', 'Serviço adicionado com sucesso!');
        }
        
        if(isset($_POST['editar'])){
            $stmt = $pdo->prepare("UPDATE servicos SET nome_servico=?, descricao=?, preco_mao_obra=?, tempo_estimado=? WHERE id_servico=?");
            $stmt->execute([$_POST['nome_servico'], $_POST['descricao'], $_POST['preco_mao_obra'], $_POST['tempo_estimado'], $_POST['id_servico']]);
            $mensagem = mostrarMensagem('success', 'Serviço atualizado com sucesso!');
            $editando = false;
            $servico_edit = null;
        }
        
        if(isset($_POST['cancelar_edicao'])){
            $editando = false;
            $servico_edit = null;
        }
        
        if(isset($_POST['excluir'])){
            // Verificar se serviço está em alguma OS
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM os_servicos WHERE id_servico = ?");
            $stmt->execute([$_POST['id_servico']]);
            if($stmt->fetchColumn() > 0){
                $mensagem = mostrarMensagem('error', 'Serviço está vinculado a ordens de serviço!');
            } else {
                $stmt = $pdo->prepare("DELETE FROM servicos WHERE id_servico = ?");
                $stmt->execute([$_POST['id_servico']]);
                $mensagem = mostrarMensagem('success', 'Serviço excluído com sucesso!');
            }
        }
    } catch(Exception $e) {
        $mensagem = mostrarMensagem('error', 'Erro: ' . $e->getMessage());
    }
}

$servicos = $pdo->query("SELECT * FROM servicos ORDER BY nome_servico")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Serviços - Oficina Mecânica</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Serviços - Oficina Mecânica</h1>
        
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
            <h2><?php echo $editando ? 'Editar Serviço' : 'Adicionar Novo Serviço'; ?></h2>
            <form method="POST">
                <?php if($editando): ?>
                    <input type="hidden" name="id_servico" value="<?php echo $servico_edit['id_servico']; ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <input type="text" name="nome_servico" placeholder="Nome do serviço" required
                           value="<?php echo $editando ? htmlspecialchars($servico_edit['nome_servico']) : ''; ?>">
                </div>
                <div class="form-group">
                    <textarea name="descricao" placeholder="Descrição do serviço" rows="3"><?php echo $editando ? htmlspecialchars($servico_edit['descricao']) : ''; ?></textarea>
                </div>
                <div class="form-inline">
                    <div class="form-group">
                        <input type="number" name="preco_mao_obra" placeholder="Preço mão de obra" step="0.01" min="0" required
                               value="<?php echo $editando ? $servico_edit['preco_mao_obra'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <input type="text" name="tempo_estimado" placeholder="Tempo estimado (ex: 1 hora)" required
                               value="<?php echo $editando ? htmlspecialchars($servico_edit['tempo_estimado']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <?php if($editando): ?>
                            <button type="submit" name="editar" class="btn btn-success">Atualizar Serviço</button>
                            <button type="submit" name="cancelar_edicao" class="btn btn-danger">Cancelar</button>
                        <?php else: ?>
                            <button type="submit" name="adicionar" class="btn btn-success">Adicionar Serviço</button>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>

        <div class="card">
            <h2>Serviços Disponíveis</h2>
            <?php if(count($servicos) > 0): ?>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Serviço</th>
                            <th>Descrição</th>
                            <th>Preço</th>
                            <th>Tempo</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($servicos as $servico): ?>
                        <tr>
                            <td><?php echo $servico['id_servico']; ?></td>
                            <td><strong><?php echo htmlspecialchars($servico['nome_servico']); ?></strong></td>
                            <td><?php echo htmlspecialchars($servico['descricao']); ?></td>
                            <td><?php echo formatarMoeda($servico['preco_mao_obra']); ?></td>
                            <td><?php echo htmlspecialchars($servico['tempo_estimado']); ?></td>
                            <td class="action-buttons">
                                <a href="?editar=<?php echo $servico['id_servico']; ?>" class="btn btn-sm">Editar</a>
                                <form method="POST">
                                    <input type="hidden" name="id_servico" value="<?php echo $servico['id_servico']; ?>">
                                    <button type="submit" name="excluir" class="btn btn-danger btn-sm" 
                                            onclick="return confirm('Tem certeza que deseja excluir o serviço <?php echo htmlspecialchars($servico['nome_servico']); ?>?')">
                                        Excluir
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
                <p>Nenhum serviço cadastrado.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>