<?php
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../layout/admin_header.php';

auth();
canAny(['admin', 'atendente']);

$turma_id = $_GET['turma_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $stmt = $pdo->prepare("
        INSERT INTO turmas_sessoes (turma_id, data, descricao)
        VALUES (?, ?, ?)
    ");

    $stmt->execute([
        $_POST['turma_id'],
        $_POST['data'],
        $_POST['descricao']
    ]);

    header("Location: sessoes_lista.php?turma_id=" . $_POST['turma_id']);
    exit;
}
?>

<div class="container mt-4">

    <h3>Nova Sessão</h3>

    <form method="POST">

        <input type="hidden" name="turma_id" value="<?= $turma_id ?>">

        <div class="mb-3">
            <label>Data</label>
            <input type="date" name="data" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Descrição</label>
            <input type="text" name="descricao" class="form-control">
        </div>

        <button class="btn btn-success">Salvar</button>

    </form>

</div>