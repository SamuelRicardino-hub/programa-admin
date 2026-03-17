<?php
require_once __DIR__ . "/../../config/conexao.php";
require_once __DIR__ . "/../../layout/admin_header.php";
require_once __DIR__ . '/../../config/auth.php';

auth();
canAny(['admin', 'atendente']);

$erro = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $responsavel = trim($_POST['responsavel']?? '');
    $data_inicio = ($_POST['data_inicio']?? '');
    $data_fim = ($_POST['data_fim']?? '');
    $status = $_POST['status'] ?? 'ativa';

    if (empty($nome)) {
        $erro = "O nome da turma é obrigatório.";
    } else {

        // Verifica se já existe turma com mesmo nome
        $check = $pdo->prepare("SELECT id FROM turmas WHERE nome = ?");
        $check->execute([$nome]);

        if ($check->rowCount() > 0) {
            $erro = "Já existe uma turma com esse nome.";
        } else {

            $stmt = $pdo->prepare("
    INSERT INTO turmas (nome, descricao, responsavel, data_inicio, data_fim, status, criado_em)
    VALUES (?, ?, ?, ?, ?, ?, NOW())
");

            $stmt->execute([$nome, $descricao, $responsavel, $data_inicio, $data_fim, $status]);

            header("Location: turmas_lista.php?msg=criado");
            exit;
        }
    }
}
?>

<h2 class="mb-4">Nova Turma</h2>

<div class="card shadow-sm">
    <div class="card-body">

        <?php if ($erro): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($erro) ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <div class="mb-3">
                <label class="form-label">Nome da Turma</label>
                <input type="text"
                    name="nome"
                    class="form-control"
                    required>
            </div>

            <div class="mb-3">
                <label class="form-label">Descrição</label>
                <textarea name="descricao"
                    class="form-control"
                    rows="3"></textarea>
            </div>

            <div class="mb-3">
                <label>Responsável</label>
                <input type="text" name="responsavel" class="form-control"
                    value="<?= $turma['responsavel'] ?? '' ?>">
            </div>

            <div class="mb-3">
                <label>Data de Início</label>
                <input type="date" name="data_inicio" class="form-control"
                    value="<?= $turma['data_inicio'] ?? '' ?>">
            </div>

            <div class="mb-3">
                <label>Data de Fim</label>
                <input type="date" name="data_fim" class="form-control"
                    value="<?= $turma['data_fim'] ?? '' ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="ativa">Ativa</option>
                    <option value="inativa">Inativa</option>
                </select>
            </div>

            <div class="d-flex justify-content-between">
                <a href="turmas_lista.php" class="btn btn-secondary">
                    Voltar
                </a>

                <button type="submit" class="btn btn-primary">
                    Salvar Turma
                </button>
            </div>

        </form>

    </div>
</div>

<?php require_once __DIR__ . "/../../layout/admin_footer.php"; ?>