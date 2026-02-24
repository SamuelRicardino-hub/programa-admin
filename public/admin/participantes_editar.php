<?php
require_once __DIR__ . "/../../config/protect.php";
require_once __DIR__ .'../config/conexao.php';

$titulo = "Editar Participante";
require_once __DIR__ . '/../layout/header.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: participantes_lista.php");
    exit;
}

/* Busca participante */
$sql = $pdo->prepare("
    SELECT id, nome, email, telefone, turma_id
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

/* Busca turmas */
$turmas = $pdo->query("SELECT id, nome FROM turmas ORDER BY nome")->fetchAll();
?>

<div class="card shadow p-4">

    <h3 class="mb-4">Editar Participante</h3>

    <form action="participantes_atualizar.php" method="post">

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
            <label class="form-label">Email</label>
            <input type="email"
                   name="email"
                   class="form-control"
                   value="<?= htmlspecialchars($participante['email']) ?>"
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
            <a href="participantes_lista.php" class="btn btn-secondary">
                Voltar
            </a>

            <button type="submit" class="btn btn-success">
                Salvar Alterações
            </button>
        </div>

    </form>

</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
