<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/logs.php';
require_once __DIR__ . '/../layout/admin_header.php';

auth();
canAny(['admin','atendente']);

$id = $_GET['id'] ?? null;

if (!$id) {
    die("Sessão não informada");
}

// Buscar sessão
$stmt = $pdo->prepare("
    SELECT ts.*, t.nome as turma_nome
    FROM turmas_sessoes ts
    JOIN turmas t ON t.id = ts.turma_id
    WHERE ts.id = ?
");
$stmt->execute([$id]);
$sessao = $stmt->fetch();

if (!$sessao) {
    die("Sessão não encontrada");
}

// ==============================
// 💾 SALVAR
// ==============================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = $_POST['data'] ?? null;
    $descricao = $_POST['descricao'] ?? null;

    if (!$data) {
        echo "<div class='alert alert-danger'>Data obrigatória</div>";
    } else {

        $stmt = $pdo->prepare("
            UPDATE turmas_sessoes
            SET data = ?, descricao = ?
            WHERE id = ?
        ");
        $stmt->execute([$data, $descricao, $id]);

        // LOG
        registrarLog(
            $pdo,
            'UPDATE',
            'turmas_sessoes',
            $id,
            "Editou sessão ID $id",
            $_SESSION['usuario']['id']
        );

        header("Location: sessoes_lista.php?turma_id=" . $sessao['turma_id']);
        exit;
    }
}
?>

<div class="container mt-4">

    <h3>Editar Sessão</h3>

    <div class="card p-3">

        <p><strong>Turma:</strong> <?= $sessao['turma_nome'] ?></p>

        <form method="POST">

            <div class="mb-3">
                <label>Data</label>
                <input type="date" name="data" class="form-control"
                    value="<?= $sessao['data'] ?>" required>
            </div>

            <div class="mb-3">
                <label>Descrição</label>
                <textarea name="descricao" class="form-control"><?= $sessao['descricao'] ?></textarea>
            </div>

            <button class="btn btn-success">Salvar</button>

            <a href="sessoes_lista.php?turma_id=<?= $sessao['turma_id'] ?>"
                class="btn btn-secondary">
                Voltar
            </a>

        </form>

    </div>

</div>

<?php require_once __DIR__ . '/../layout/admin_footer.php'; ?>