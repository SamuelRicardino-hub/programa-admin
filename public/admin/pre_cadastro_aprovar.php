<?php
require_once __DIR__ . "/../../config/protect.php";
require_once __DIR__ . "/../../config/conexao.php";
require_once __DIR__ . "/../../layout/admin_header.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

$id = (int) $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM pre_cadastros WHERE id = ?");
$stmt->execute([$id]);
$pre = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pre) {
    die("Pré-cadastro não encontrado.");
}

if ($pre['status'] !== 'pendente') {
    die("Este pré-cadastro já foi processado.");
}

$turmas = $pdo->query("SELECT id, nome FROM turmas WHERE status = 'ativa' ORDER BY nome")
              ->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $turma_id = (int) $_POST['turma_id'];

    try {

        $pdo->beginTransaction();

        $check = $pdo->prepare("SELECT id FROM participantes WHERE cpf = ?");
        $check->execute([$pre['cpf']]);

        if ($check->rowCount() > 0) {
            throw new Exception("Participante já cadastrado.");
        }

        $insert = $pdo->prepare("
            INSERT INTO participantes
            (nome, cpf, data_nascimento, telefone, email, turma_id)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $insert->execute([
            $pre['nome'],
            $pre['cpf'],
            $pre['data_nascimento'],
            $pre['telefone'],
            $pre['email'],
            $turma_id
        ]);

        $update = $pdo->prepare("
            UPDATE pre_cadastros
            SET status = 'aprovado'
            WHERE id = ?
        ");
        $update->execute([$id]);

        $pdo->commit();

        header("Location: pre_cadastros.php?msg=aprovado");
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        $erro = $e->getMessage();
    }
}
?>

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Confirmar Aprovação de Pré-Cadastro</h5>
        </div>

        <div class="card-body">

            <?php if (!empty($erro)): ?>
                <div class="alert alert-danger"><?= $erro ?></div>
            <?php endif; ?>

            <div class="mb-3">
                <strong>Nome:</strong> <?= htmlspecialchars($pre['nome']) ?>
            </div>

            <div class="mb-3">
                <strong>CPF:</strong> <?= htmlspecialchars($pre['cpf']) ?>
            </div>

            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">Selecionar Turma</label>
                    <select name="turma_id" class="form-select" required>
                        <option value="">Selecione</option>
                        <?php foreach ($turmas as $t): ?>
                            <option value="<?= $t['id'] ?>">
                                <?= htmlspecialchars($t['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="pre_cadastros.php" class="btn btn-secondary">
                        Cancelar
                    </a>

                    <button type="submit" class="btn btn-success">
                        Confirmar Aprovação
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<?php require_once __DIR__ . "/../../layout/admin_footer.php"; ?>