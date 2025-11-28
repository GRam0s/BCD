<?php include 'config.php'; ?>

<?php
$mensagem = '';

// Modo de edição
$editando = false;
$mecanico_edit = null;

if(isset($_GET['editar'])) {
    $editando = true;
    $stmt = $pdo->prepare("SELECT * FROM mecanicos WHERE id_mecanico = ?");
    $stmt->execute([$_GET['editar']]);
    $mecanico_edit = $stmt->fetch();
}

if($_POST){
    try {
        if(isset($_POST['adicionar'])){
            $stmt = $pdo->prepare("INSERT INTO mecanicos (nome, especialidade) VALUES (?, ?)");
            $stmt->execute([$_POST['nome'], $_POST['especialidade']]);
            $mensagem = mostrarMensagem('success', 'Mecânico adicionado com sucesso!');
        }
        
        if(isset($_POST['editar'])){
            $stmt = $pdo->prepare("UPDATE mecanicos SET nome=?, especialidade=? WHERE id_mecanico=?");
            $stmt->execute([$_POST['nome'], $_POST['especialidade'], $_POST['id_mecanico']]);
            $mensagem = mostrarMensagem('success', 'Mecânico atualizado com sucesso!');
            $editando = false;
            $mecanico_edit = null;
        }
        
        if(isset($_POST['cancelar_edicao'])){
            $editando = false;
            $mecanico_edit = null;
        }
        
        if(isset($_POST['excluir'])){
            // Verificar se mecânico está em alguma OS
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM os_mecanicos WHERE id_mecanico = ?");
            $stmt->execute([$_POST['id_mecanico']]);
            if($stmt->fetchColumn() > 0){
                $mensagem = mostrarMensagem('error', 'Mecânico está vinculado a ordens de serviço!');
            } else {
                $stmt = $pdo->prepare("DELETE FROM mecanicos WHERE id_mecanico = ?");
                $stmt->execute([$_POST['id_mecanico']]);
                $mensagem = mostrarMensagem('success', 'Mecânico excluído com sucesso!');
            }
        }
    } catch(Exception $e) {
        $mensagem = mostrarMensagem('error', 'Erro: ' . $e->getMessage());
    }
}

$mecanicos = $pdo->query("SELECT * FROM mecanicos ORDER BY nome")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Mecânicos - Oficina Mecânica</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Mecânicos - Oficina Mecânica</h1>
        
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
            <h2><?php echo $editando ? 'Editar Mecânico' : 'Adicionar Mecânico'; ?></h2>
            <form method="POST" class="form-inline">
                <?php if($editando): ?>
                    <input type="hidden" name="id_mecanico" value="<?php echo $mecanico_edit['id_mecanico']; ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <input type="text" name="nome" placeholder="Nome" required
                           value="<?php echo $editando ? htmlspecialchars($mecanico_edit['nome']) : ''; ?>">
                </div>
                <div class="form-group">
                    <input type="text" name="especialidade" placeholder="Especialidade"
                           value="<?php echo $editando ? htmlspecialchars($mecanico_edit['especialidade']) : ''; ?>">
                </div>
                <div class="form-group">
                    <?php if($editando): ?>
                        <button type="submit" name="editar" class="btn btn-success">Atualizar Mecânico</button>
                        <button type="submit" name="cancelar_edicao" class="btn btn-danger">Cancelar</button>
                    <?php else: ?>
                        <button type="submit" name="adicionar" class="btn btn-success">Adicionar Mecânico</button>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="card">
            <h2>Lista de Mecânicos</h2>
            <?php if(count($mecanicos) > 0): ?>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Especialidade</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($mecanicos as $mecanico): ?>
                        <tr>
                            <td><?php echo $mecanico['id_mecanico']; ?></td>
                            <td><?php echo htmlspecialchars($mecanico['nome']); ?></td>
                            <td><?php echo htmlspecialchars($mecanico['especialidade']); ?></td>
                            <td class="action-buttons">
                                <a href="?editar=<?php echo $mecanico['id_mecanico']; ?>" class="btn btn-sm">Editar</a>
                                <form method="POST">
                                    <input type="hidden" name="id_mecanico" value="<?php echo $mecanico['id_mecanico']; ?>">
                                    <button type="submit" name="excluir" class="btn btn-danger btn-sm" 
                                            onclick="return confirm('Tem certeza que deseja excluir o mecânico <?php echo htmlspecialchars($mecanico['nome']); ?>?')">
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
                <p>Nenhum mecânico cadastrado.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>