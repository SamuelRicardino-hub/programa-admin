<?php
// 1. ATIVE O REPORT DE ERROS (Caso haja algum erro de banco, ele será impresso na tela)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/auth.php';

auth();
canAny(['admin', 'atendente']);

$sessao_id = $_GET['sessao_id'] ?? null;
if (!$sessao_id) die("Sessão não informada");

// =========================================================================
// CORREÇÃO CRÍTICA: PROCESSA E SALVA AS PRESENÇAS ANTES DE QUALQUER HTML
// =========================================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1.1. Buscar o turma_id da sessão atual para poder redirecionar corretamente depois
    $stmt_turma = $pdo->prepare("SELECT turma_id FROM turmas_sessoes WHERE id = ?");
    $stmt_turma->execute([$sessao_id]);
    $sessao_atual = $stmt_turma->fetch();
    $turma_id_redirecionar = $sessao_atual['turma_id'] ?? null;

    if (!$turma_id_redirecionar) {
        die("Erro: Não foi possível identificar a turma desta sessão.");
    }

    // 1.2. Processa o array de status enviado pelo formulário
    if (isset($_POST['status']) && is_array($_POST['status'])) {
        try {
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

            // Redirecionamento limpo e seguro (funciona 100% pois nenhum HTML foi carregado ainda)
            header("Location: sessoes_lista.php?turma_id=" . $turma_id_redirecionar . "&sucesso=1");
            exit;
        } catch (PDOException $e) {
            die("Erro ao salvar presenças no banco de dados: " . $e->getMessage());
        }
    } else {
        // Se a lista foi enviada vazia (sem alunos), apenas redireciona de volta
        header("Location: sessoes_lista.php?turma_id=" . $turma_id_redirecionar);
        exit;
    }
}

// ... logo após finalizar o laço (foreach/while) que salva as presenças no banco ...
require_once __DIR__ . '/../config/logs.php';

$stmt_sessao = $pdo->prepare("SELECT ts.descricao, t.nome FROM turmas_sessoes ts JOIN turmas t ON ts.turma_id = t.id WHERE ts.id = ?");
$stmt_sessao->execute([$sessao_id]);
$info_sessao = $stmt_sessao->fetch();

$msg_presenca = $info_sessao ? "Atualizou a lista de presença da sessão '" . $info_sessao['descricao'] . "' (" . $info_sessao['nome'] . ")" : "Atualizou a lista de presença da sessão ID " . $sessao_id;

registrarLog($pdo, 'UPDATE', 'presencas', $sessao_id, $msg_presenca);

$stmt = $pdo->prepare("
    SELECT ts.*, t.nome as turma_nome 
    FROM turmas_sessoes ts 
    JOIN turmas t ON t.id = ts.turma_id 
    WHERE ts.id = ?
");
$stmt->execute([$sessao_id]);
$sessao = $stmt->fetch();

if (!$sessao) die("Sessão não encontrada");

$turma_id = $sessao['turma_id'];

// Participantes vinculados a esta turma
$stmt = $pdo->prepare("
    SELECT p.id, p.nome 
    FROM participantes p
    JOIN turmas_participantes tp ON p.id = tp.participante_id
    WHERE tp.turma_id = ?
    ORDER BY p.nome ASC
");
$stmt->execute([$turma_id]);
$participantes = $stmt->fetchAll();

// Presenças já gravadas anteriormente (para carregar na edição)
$stmt = $pdo->prepare("SELECT * FROM presencas WHERE sessao_id = ?");
$stmt->execute([$sessao_id]);
$presencasDb = $stmt->fetchAll();

$mapPresencas = [];
foreach ($presencasDb as $p) {
    $mapPresencas[$p['participante_id']] = $p;
}

// =========================================================================
// 3. AGORA INCLUÍMOS O LAYOUT COM TOTAL SEGURANÇA
// =========================================================================
require_once __DIR__ . '/../layout/admin_header.php';
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold"><i class="bi bi-people me-2 text-primary"></i>Lista de Presença</h3>
                    <p class="text-muted">
                        Sessão: <strong><?= htmlspecialchars($sessao['descricao']) ?></strong> |
                        Data: <strong><?= date('d/m/Y', strtotime($sessao['data'])) ?></strong>
                    </p>
                </div>
                <a href="sessoes_lista.php?turma_id=<?= $turma_id ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>
            </div>

            <div class="row mb-4 g-3">
                <div class="col-md-3">
                    <div class="card bg-success text-white shadow-sm">
                        <div class="card-body py-2 text-center">
                            <small>Presentes</small>
                            <h4 class="mb-0" id="countPresente">0</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white shadow-sm">
                        <div class="card-body py-2 text-center">
                            <small>Faltas</small>
                            <h4 class="mb-0" id="countFalta">0</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-dark shadow-sm">
                        <div class="card-body py-2 text-center">
                            <small>Justificados</small>
                            <h4 class="mb-0" id="countJustificado">0</h4>
                        </div>
                    </div>
                </div>
            </div>

            <form method="POST">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">Participantes da Turma</h5>
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-outline-success" onclick="marcarTodos('presente')">✅ Todos Presentes</button>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="marcarTodos('falta')">❌ Todos Faltaram</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <?php if (empty($participantes)): ?>
                            <div class="text-center my-5 p-4">
                                <i class="bi bi-people text-muted fs-1"></i>
                                <h5 class="mt-3 fw-bold text-secondary">Nenhum participante nesta turma</h5>
                                <p class="text-muted small">Vincule alunos a esta turma para que eles apareçam na listagem.</p>
                            </div>
                        <?php else: ?>
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Nome do Participante</th>
                                        <th style="width: 250px;">Status de Presença</th>
                                        <th class="pe-4">Observações / Motivo da Falta</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($participantes as $p):
                                        $status = $mapPresencas[$p['id']]['status'] ?? 'falta';
                                        $obs = $mapPresencas[$p['id']]['observacao'] ?? '';
                                    ?>
                                        <tr id="row-<?= $p['id'] ?>" class="presenca-row">
                                            <td class="ps-4 fw-bold text-dark"><?= htmlspecialchars($p['nome']) ?></td>
                                            <td>
                                                <select name="status[<?= $p['id'] ?>]"
                                                    class="form-select status-select shadow-none"
                                                    onchange="atualizarEstiloLinha(<?= $p['id'] ?>)">
                                                    <option value="presente" <?= $status == 'presente' ? 'selected' : '' ?>>✅ Presente</option>
                                                    <option value="falta" <?= $status == 'falta' ? 'selected' : '' ?>>❌ Falta</option>
                                                    <option value="justificado" <?= $status == 'justificado' ? 'selected' : '' ?>>⚠️ Justificado</option>
                                                </select>
                                            </td>
                                            <td class="pe-4">
                                                <input type="text" name="observacao[<?= $p['id'] ?>]"
                                                    class="form-control form-control-sm"
                                                    placeholder="Opcional..."
                                                    value="<?= htmlspecialchars($obs) ?>">
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>

                    <div class="card-footer bg-white py-3">
                        <button type="submit" class="btn btn-primary px-5 shadow">
                            <i class="bi bi-cloud-check me-2"></i>Finalizar e Salvar Chamada
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function atualizarEstiloLinha(pid) {
        const row = document.getElementById('row-' + pid);
        if (!row) return;
        const select = row.querySelector('.status-select');

        row.classList.remove('table-success', 'table-danger', 'table-warning');

        if (select.value === 'presente') row.classList.add('table-success');
        else if (select.value === 'falta') row.classList.add('table-danger');
        else if (select.value === 'justificado') row.classList.add('table-warning');

        atualizarContadores();
    }

    function marcarTodos(tipo) {
        document.querySelectorAll('.status-select').forEach(select => {
            select.value = tipo;
            const pid = select.name.match(/\[(\d+)\]/)[1];
            atualizarEstiloLinha(pid);
        });
    }

    function atualizarContadores() {
        let p = 0,
            f = 0,
            j = 0;
        document.querySelectorAll('.status-select').forEach(select => {
            if (select.value === 'presente') p++;
            else if (select.value === 'falta') f++;
            else if (select.value === 'justificado') j++;
        });

        const cp = document.getElementById('countPresente');
        const cf = document.getElementById('countFalta');
        const cj = document.getElementById('countJustificado');

        if (cp) cp.innerText = p;
        if (cf) cf.innerText = f;
        if (cj) cj.innerText = j;
    }

    window.onload = () => {
        document.querySelectorAll('.status-select').forEach(select => {
            const pid = select.name.match(/\[(\d+)\]/)[1];
            atualizarEstiloLinha(pid);
        });
    };
</script>

<?php require_once __DIR__ . '/../layout/admin_footer.php'; ?>