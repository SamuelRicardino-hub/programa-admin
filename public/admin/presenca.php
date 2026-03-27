<?php
require_once __DIR__ . "/../../config/conexao.php";
require_once __DIR__ . "/../../config/auth.php";
require_once __DIR__ . "/../../config/logs.php";
require_once __DIR__ ."/../../layout/admin_header.php";

$turma_id = $_GET['turma_id'] ?? null;
$data = $_GET['data'] ?? date('Y-m-d');

if (!$turma_id) die("Turma não informada");

// Buscar turma
$stmt = $pdo->prepare("SELECT * FROM turmas WHERE id = ?");
$stmt->execute([$turma_id]);
$turma = $stmt->fetch();

if (!$turma) die("Turma não encontrada");

// Buscar ou criar sessão
$stmt = $pdo->prepare("SELECT * FROM turmas_sessoes WHERE turma_id = ? AND data = ?");
$stmt->execute([$turma_id, $data]);
$sessao = $stmt->fetch();

if (!$sessao) {
    $stmt = $pdo->prepare("INSERT INTO turmas_sessoes (turma_id, data) VALUES (?, ?)");
    $stmt->execute([$turma_id, $data]);
    $sessao_id = $pdo->lastInsertId();
} else {
    $sessao_id = $sessao['id'];
}

// SALVAR
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['presencas'])) {

    foreach ($_POST['presencas'] as $participante_id => $status) {

        $stmt = $pdo->prepare("
            INSERT INTO presencas (sessao_id, participante_id, status)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE status = VALUES(status)
        ");

        $stmt->execute([$sessao_id, $participante_id, $status]);
    }

    
}
// Participantes
$stmt = $pdo->prepare("SELECT id, nome FROM participantes WHERE turma_id = ?");
$stmt->execute([$turma_id]);
$participantes = $stmt->fetchAll();

// Presenças existentes
$stmt = $pdo->prepare("SELECT participante_id, status FROM presencas WHERE sessao_id = ?");
$stmt->execute([$sessao_id]);

$presencas_db = [];
foreach ($stmt->fetchAll() as $p) {
    $presencas_db[$p['participante_id']] = $p['status'];
}
?>

<div class="container mt-4">

    <h2>Lista de Presença</h2>
    <p><strong>Turma:</strong> <?= $turma['nome'] ?></p>

    <form method="GET" class="mb-3">
        <input type="hidden" name="turma_id" value="<?= $turma_id ?>">
        <input type="date" name="data" value="<?= $data ?>" required>
        <button type="submit" class="btn btn-primary">Selecionar</button>
    </form>

    <?php if (isset($_GET['sucesso'])): ?>
        <div class="alert alert-success">Presença salva com sucesso!</div>
    <?php endif; ?>

    <form method="POST">

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($participantes as $p): 
                    $status = $presencas_db[$p['id']] ?? 'falta';
                ?>
                <tr>
                    <td><?= $p['nome'] ?></td>
                    <td>
                        <select name="presencas[<?= $p['id'] ?>]" class="form-select">
                            <option value="presente" <?= $status == 'presente' ? 'selected' : '' ?>>Presente</option>
                            <option value="falta" <?= $status == 'falta' ? 'selected' : '' ?>>Falta</option>
                            <option value="justificado" <?= $status == 'justificado' ? 'selected' : '' ?>>Justificado</option>
                        </select>
                    </td>
                </tr>
                <?php endforeach; ?>

            </tbody>
        </table>

        <button class="btn btn-success">Salvar Presença</button>
    </form>

    <br>

    <a href="presenca_relatorio.php?turma_id=<?= $turma_id ?>" class="btn btn-secondary">
        Ver Relatório
    </a>

</div>

<?php
// 🔗 FOOTER DO SISTEMA
require_once __DIR__ ."/../../layout/admin_footer.php";
?>