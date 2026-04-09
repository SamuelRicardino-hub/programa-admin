<?php
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../config/auth.php';

auth();
canAny(['admin', 'atendente']);

$turma_id = $_GET['turma_id'] ?? null;

if (!$turma_id) {
    die("Turma não informada");
}

// Buscar turma
$stmt = $pdo->prepare("SELECT * FROM turmas WHERE id = ?");
$stmt->execute([$turma_id]);
$turma = $stmt->fetch();

if (!$turma) {
    die("Turma não encontrada");
}

// Buscar participantes da turma
$stmt = $pdo->prepare("
    SELECT * FROM participantes 
    WHERE turma_id = ? AND status = 'ativo'
    ORDER BY nome
");
$stmt->execute([$turma_id]);
$participantes = $stmt->fetchAll();

// ==============================
// 💾 SALVAR
// ==============================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = $_POST['data'] ?? null;
    $descricao = $_POST['descricao'] ?? null;

    if (!$data) {
        echo "<div class='alert alert-danger'>Informe a data</div>";
    } else {

        try {

            $pdo->beginTransaction();

            // 1️⃣ Criar sessão
            $stmt = $pdo->prepare("
                INSERT INTO turmas_sessoes (turma_id, data, descricao)
                VALUES (?, ?, ?)
            ");
            $stmt->execute([$turma_id, $data, $descricao]);

            $sessao_id = $pdo->lastInsertId();

            // 2️⃣ Presenças
            foreach ($participantes as $p) {

                $status = $_POST['presenca'][$p['id']] ?? 'falta';
                $obs = $_POST['observacao'][$p['id']] ?? null;

                $stmt = $pdo->prepare("
                    INSERT INTO presencas (
                        sessao_id,
                        participante_id,
                        status,
                        observacao
                    ) VALUES (?, ?, ?, ?)
                ");

                $stmt->execute([
                    $sessao_id,
                    $p['id'],
                    $status,
                    $obs
                ]);
            }

            $pdo->commit();

            echo "<div class='alert alert-success'>Sessão registrada com sucesso!</div>";

        } catch (Exception $e) {
            $pdo->rollBack();
            echo "<div class='alert alert-danger'>Erro: {$e->getMessage()}</div>";
        }
    }
}

require_once __DIR__ . '/../../layout/admin_header.php';
?>

<div class="container mt-4">

    <h3>Registrar Encontro - <?= htmlspecialchars($turma['nome']) ?></h3>

    <form method="POST">

        <!-- DATA -->
        <div class="mb-3">
            <label>Data do encontro</label>
            <input type="date" name="data" class="form-control" required>
        </div>

        <!-- DESCRIÇÃO -->
        <div class="mb-3">
            <label>Descrição</label>
            <textarea name="descricao" class="form-control"></textarea>
        </div>

        <!-- LISTA -->
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Nome</th>
                    <th>Status</th>
                    <th>Observação</th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($participantes as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['nome']) ?></td>

                        <td>
                            <select name="presenca[<?= $p['id'] ?>]" class="form-select">
                                <option value="presente">Presente</option>
                                <option value="falta" selected>Falta</option>
                                <option value="justificado">Justificado</option>
                            </select>
                        </td>

                        <td>
                            <input type="text"
                                name="observacao[<?= $p['id'] ?>]"
                                class="form-control">
                        </td>
                    </tr>
                <?php endforeach; ?>

            </tbody>
        </table>

        <button class="btn btn-success">Salvar Encontro</button>
        <a href="turmas_lista.php" class="btn btn-secondary">Voltar</a>

    </form>

</div>

<?php require_once __DIR__ . '/../../layout/admin_footer.php'; ?><?php
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../config/auth.php';

auth();
canAny(['admin', 'atendente']);

$turma_id = $_GET['turma_id'] ?? null;

if (!$turma_id) {
    die("Turma não informada");
}

// Buscar turma
$stmt = $pdo->prepare("SELECT * FROM turmas WHERE id = ?");
$stmt->execute([$turma_id]);
$turma = $stmt->fetch();

if (!$turma) {
    die("Turma não encontrada");
}

// Buscar participantes da turma
$stmt = $pdo->prepare("
    SELECT * FROM participantes 
    WHERE turma_id = ? AND status = 'ativo'
    ORDER BY nome
");
$stmt->execute([$turma_id]);
$participantes = $stmt->fetchAll();

// ==============================
// 💾 SALVAR
// ==============================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = $_POST['data'] ?? null;
    $descricao = $_POST['descricao'] ?? null;

    if (!$data) {
        echo "<div class='alert alert-danger'>Informe a data</div>";
    } else {

        try {

            $pdo->beginTransaction();

            // 1️⃣ Criar sessão
            $stmt = $pdo->prepare("
                INSERT INTO turmas_sessoes (turma_id, data, descricao)
                VALUES (?, ?, ?)
            ");
            $stmt->execute([$turma_id, $data, $descricao]);

            $sessao_id = $pdo->lastInsertId();

            // 2️⃣ Presenças
            foreach ($participantes as $p) {

                $status = $_POST['presenca'][$p['id']] ?? 'falta';
                $obs = $_POST['observacao'][$p['id']] ?? null;

                $stmt = $pdo->prepare("
                    INSERT INTO presencas (
                        sessao_id,
                        participante_id,
                        status,
                        observacao
                    ) VALUES (?, ?, ?, ?)
                ");

                $stmt->execute([
                    $sessao_id,
                    $p['id'],
                    $status,
                    $obs
                ]);
            }

            $pdo->commit();

            echo "<div class='alert alert-success'>Sessão registrada com sucesso!</div>";

        } catch (Exception $e) {
            $pdo->rollBack();
            echo "<div class='alert alert-danger'>Erro: {$e->getMessage()}</div>";
        }
    }
}

require_once __DIR__ . '/../../layout/admin_header.php';
?>

<div class="container mt-4">

    <h3>Registrar Encontro - <?= htmlspecialchars($turma['nome']) ?></h3>

    <form method="POST">

        <!-- DATA -->
        <div class="mb-3">
            <label>Data do encontro</label>
            <input type="date" name="data" class="form-control" required>
        </div>

        <!-- DESCRIÇÃO -->
        <div class="mb-3">
            <label>Descrição</label>
            <textarea name="descricao" class="form-control"></textarea>
        </div>

        <!-- LISTA -->
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Nome</th>
                    <th>Status</th>
                    <th>Observação</th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($participantes as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['nome']) ?></td>

                        <td>
                            <select name="presenca[<?= $p['id'] ?>]" class="form-select">
                                <option value="presente">Presente</option>
                                <option value="falta" selected>Falta</option>
                                <option value="justificado">Justificado</option>
                            </select>
                        </td>

                        <td>
                            <input type="text"
                                name="observacao[<?= $p['id'] ?>]"
                                class="form-control">
                        </td>
                    </tr>
                <?php endforeach; ?>

            </tbody>
        </table>

        <button class="btn btn-success">Salvar Encontro</button>
        <a href="turmas_lista.php" class="btn btn-secondary">Voltar</a>

    </form>

</div>

<?php require_once __DIR__ . '/../../layout/admin_footer.php'; ?>