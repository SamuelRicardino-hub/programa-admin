<?php
require_once __DIR__ . "/../../config/conexao.php";
require_once __DIR__ . "/../../layout/admin_header.php";
require_once __DIR__ . '/../../config/auth.php';


auth();
canAny(['admin', 'atendente']);

$titulo = "Editar Turma";


$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: turmas_lista.php");
    exit;
}

$sql = $pdo->prepare("SELECT id, nome, descricao, responsavel, data_inicio, data_fim, status FROM turmas WHERE id = :id");
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

            <div class="mb-3">
                <label class="form-label">Responsável</label>
                <input type="text"
                       name="responsavel"
                       class="form-control"
                       value="<?= htmlspecialchars($turma['responsavel']) ?>"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">Data Início</label>
                <input type="date"
                       name="data_inicio"
                       class="form-control"
                       value="<?= htmlspecialchars($turma['data_inicio']) ?>"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">Data Fim</label>
                <input type="date"
        |               name="data_fim"
                       class="form-control"
                       value="<?= htmlspecialchars($turma['data_fim']) ?>"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="ativa">Ativa</option>
                    <option value="inativa">Inativa</option>
                </select>
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
