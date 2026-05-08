<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../layout/admin_header.php';

auth();
canAny(['admin', 'atendente']);

$sessao_id = $_GET['sessao_id'] ?? null;

if (!$sessao_id) {
    die("Sessão não informada");
}

// Buscar sessão para descobrir a turma
$stmt = $pdo->prepare("SELECT * FROM turmas_sessoes WHERE id = ?");
$stmt->execute([$sessao_id]);
$sessao = $stmt->fetch();

if (!$sessao) {
    die("Sessão não encontrada");
}

$turma_id = $sessao['turma_id'];

// Participantes
$stmt = $pdo->prepare("
    SELECT * FROM participantes
    WHERE turma_id = ?
");
$stmt->execute([$sessao['turma_id']]);
$participantes = $stmt->fetchAll();

// Presenças já registradas
$stmt = $pdo->prepare("
    SELECT * FROM presencas WHERE sessao_id = ?
");
$stmt->execute([$sessao_id]);
$presencas = $stmt->fetchAll();

$mapPresencas = [];
foreach ($presencas as $p) {
    $mapPresencas[$p['participante_id']] = $p;
}

// SALVAR
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    foreach ($_POST['status'] as $pid => $status) {

        $obs = $_POST['observacao'][$pid] ?? null;

        $stmt = $pdo->prepare("
            INSERT INTO presencas (sessao_id, participante_id, status, observacao)
            VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE 
                status = VALUES(status),
                observacao = VALUES(observacao)
        ");

        $stmt->execute([$sessao_id, $pid, $status, $obs]);
    }

    header("Location: sessoes_lista.php?turma_id=" . $turma_id . "&sucesso=1");
    exit;
}

// ==============================
// 📊 ESTATÍSTICAS
// ==============================

$total = count($participantes);
$presentes = 0;
$faltas = 0;
$justificados = 0;

foreach ($mapPresencas as $p) {
    if ($p['status'] == 'presente') $presentes++;
    if ($p['status'] == 'falta') $faltas++;
    if ($p['status'] == 'justificado') $justificados++;
}

$percentual = $total > 0 ? round(($presentes / $total) * 100) : 0;
?>

<div class="container mt-4">

    <h3>Presença da Sessão</h3>
    <p><strong>Data:</strong> <?= date('d/m/Y', strtotime($sessao['data'])) ?></p>


    <!-- 📊 RESUMO -->
    <div class="row mb-3">

        <div class="alert alert-info">
            Presença geral: <strong><?= $percentual ?>%</strong>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">

            <h4>📅 Registro de Presença</h4>

            <div>
                <button type="button" class="btn btn-success btn-sm" onclick="marcarTodos('presente')">
                    ✅ Todos Presentes
                </button>

                <button type="button" class="btn btn-danger btn-sm" onclick="marcarTodos('falta')">
                    ❌ Todos Faltaram
                </button>

                <button type="button" class="btn btn-warning btn-sm" onclick="marcarTodos('justificado')">
                    ⚠️ Justificar Todos
                </button>
            </div>

        </div>


        <!-- 🧾 LISTA -->
        <form method="POST">

            <div class="mb-3">
                <span class="badge bg-success" id="countPresente">0 Presentes</span>
                <span class="badge bg-danger" id="countFalta">0 Faltas</span>
                <span class="badge bg-warning text-dark" id="countJustificado">0 Justificados</span>
            </div>

            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Nome</th>
                        <th>Status</th>
                        <th>Observação</th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($participantes as $p):

                        $presenca = $presencasExistentes[$p['id']] ?? null;
                        $status = $presenca['status'] ?? 'falta';
                        $obs = $presenca['observacao'] ?? '';
                    ?>

                        <tr class="<?=
                                    $status == 'presente' ? 'table-success' : ($status == 'falta' ? 'table-danger' : 'table-warning')
                                    ?>">

                            <td><?= htmlspecialchars($p['nome']) ?></td>

                            <td style="width: 200px;">
                                <select name="status[<?= $p['id'] ?>]"
                                    class="form-select form-select-sm status-select"
                                    data-id="<?= $p['id'] ?>">

                                    <option value="presente" <?= $status == 'presente' ? 'selected' : '' ?>>Presente</option>
                                    <option value="falta" <?= $status == 'falta' ? 'selected' : '' ?>>Falta</option>
                                    <option value="justificado" <?= $status == 'justificado' ? 'selected' : '' ?>>Justificado</option>

                                </select>
                            </td>

                            <td>
                                <input type="text"
                                    name="observacao[<?= $p['id'] ?>]"
                                    class="form-control form-control-sm"
                                    value="<?= htmlspecialchars($obs) ?>">
                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>
            </table>

            <button class="btn btn-success">Salvar Presença</button>

        </form>

        <script>
            function marcarTodos(tipo) {
                document.querySelectorAll('.status-select').forEach(select => {
                    select.value = tipo;
                    atualizarCorLinha(select);
                });
            }

            function atualizarCorLinha(select) {
                let row = select.closest('tr');

                row.classList.remove('table-success', 'table-danger', 'table-warning');

                if (select.value === 'presente') row.classList.add('table-success');
                if (select.value === 'falta') row.classList.add('table-danger');
                if (select.value === 'justificado') row.classList.add('table-warning');
            }

            document.querySelectorAll('.status-select').forEach(select => {
                select.addEventListener('change', function() {
                    atualizarCorLinha(this);
                    atualizarResumo();
                });
            });
        </script>

        <script>
            function atualizarResumo() {
                let presentes = 0,
                    faltas = 0,
                    justificados = 0;

                document.querySelectorAll('.status-select').forEach(select => {
                    if (select.value === 'presente') presentes++;
                    if (select.value === 'falta') faltas++;
                    if (select.value === 'justificado') justificados++;
                });

                document.getElementById('countPresente').innerText = presentes + " Presentes";
                document.getElementById('countFalta').innerText = faltas + " Faltas";
                document.getElementById('countJustificado').innerText = justificados + " Justificados";
            }

            atualizarResumo();
        </script>

    </div>

    <?php require_once __DIR__ . '/../../layout/admin_footer.php'; ?>