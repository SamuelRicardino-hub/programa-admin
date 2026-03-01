<?php
require_once __DIR__ . "/../../config/protect.php";
require_once __DIR__ . "/../../config/conexao.php";
require_once __DIR__ . "/../../layout/admin_header.php";

$titulo = "Editar Turma";


$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: turmas_lista.php");
    exit;
}

$sql = $pdo->prepare("SELECT id, nome, descricao FROM turmas WHERE id = :id");
$sql->bindParam(':id', $id, PDO::PARAM_INT);
$sql->execute();

$turma = $sql->fetch(PDO::FETCH_ASSOC);

if (!$turma) {
    header("Location: turmas_lista.php");
    exit;
}
?>

<h2 class="mb-4">Editar Turma</h2>

<div class="card shadow-sm">
    <div class="card-body">

        <form action="turmas_atualizar.php" method="post">

            <input type="hidden" name="id" value="<?= $turma['id'] ?>">

            <div class="mb-3">
                <label class="form-label">Nome da Turma</label>
                <input type="text"
                       name="nome"
                       class="form-control"
                       value="<?= htmlspecialchars($turma['nome']) ?>"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">Descrição</label>
                <textarea name="descricao"
                          class="form-control"
                          rows="3"><?= htmlspecialchars($turma['descricao']) ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">
                Salvar Alterações
            </button>

            <a href="turmas_lista.php" class="btn btn-secondary">
                Voltar
            </a>

        </form>

    </div>
</div>

<?php require_once __DIR__ . '/../../layout/admin_footer.php'; ?>
