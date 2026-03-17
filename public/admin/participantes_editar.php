<?php
require_once __DIR__ . "/../../config/conexao.php";
require_once __DIR__ . "/../../layout/admin_header.php";
require_once __DIR__ . '/../../config/auth.php';

auth();
canAny(['admin', 'atendente']);

$turma_id = $_GET['turma_id'] ?? null;

$titulo = "Editar Participante";

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: participantes_lista.php");
    exit;
}

$sql = $pdo->prepare("
    SELECT id, nome, cpf, data_nascimento, email, telefone, turma_id
    FROM participantes
    WHERE id = :id
");
$sql->bindParam(':id', $id, PDO::PARAM_INT);
$sql->execute();

$participante = $sql->fetch(PDO::FETCH_ASSOC);

if (!$participante) {
    header("Location: participantes_lista.php");
    exit;
}

$turmas = $pdo->query("SELECT id, nome FROM turmas ORDER BY nome")->fetchAll();
?>

<div class="card shadow p-4">

    <h3 class="mb-4">Editar Participante</h3>

    <form action="participantes_atualizar.php" method="post">

        <input type="hidden" name="turma_id" value="<?= $turma_id ?>">
        <input type="hidden" name="id" value="<?= $participante['id'] ?>">

        <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text"
                name="nome"
                class="form-control"
                value="<?= htmlspecialchars($participante['nome']) ?>"
                required>
        </div>
        <div class="mb-3">
            <label class="form-label">CPF</label>
            <input type="text"
                name="cpf"
                class="form-control"
                value="<?= htmlspecialchars($participante['cpf']) ?>"
                required>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Data de Nascimento</label>
            <input type="date"
                   name="data_nascimento"
                   class="form-control"
                   value="<?= htmlspecialchars($participante['data_nascimento']) ?>"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email"
                name="email"
                class="form-control"
                value="<?= htmlspecialchars($participante['email']) ?>"
                required>
        </div>

        <div class="mb-3">
            <label class="form-label">Endereco</label>
            <input type="text"
                name="endereco"
                class="form-control"
                value="<?= htmlspecialchars($participante['endereco']) ?>"
                required>
        </div>

        <div class="mb-3">
            <label class="form-label">Bairro</label>
            <input type="text"
                name="bairro"
                class="form-control"
                value="<?= htmlspecialchars($participante['bairro']) ?>"
                required>
        </div>

        <div class="mb-3">
            <label class="form-label">Telefone</label>
            <input type="text"
                name="telefone"
                class="form-control"
                value="<?= htmlspecialchars($participante['telefone']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Turma</label>
            <select name="turma_id" class="form-select" required>
                <?php foreach ($turmas as $t): ?>
                    <option value="<?= $t['id'] ?>"
                        <?= $t['id'] == $participante['turma_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($t['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="d-flex justify-content-between">
            <a href="participantes_lista.php?turma_id=<?= $turma_id ?>" class="btn btn-secondary">
                Voltar
            </a>

            <button type="submit" class="btn btn-success">
                Salvar Alterações
            </button>
        </div>

    </form>

</div>

<?php require_once __DIR__ . '/../../layout/admin_footer.php'; ?>