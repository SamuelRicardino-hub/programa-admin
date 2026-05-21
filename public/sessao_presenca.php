    <?php
    require_once __DIR__ . '/../config/conexao.php';
    require_once __DIR__ . '/../config/auth.php';
    require_once __DIR__ . '/../layout/admin_header.php';

    auth();
    canAny(['admin', 'atendente']);

    $sessao_id = $_GET['sessao_id'] ?? null;
    if (!$sessao_id) die("Sessão não informada");

    // 1. Buscar detalhes da sessão e da turma
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

    // 2. Buscar participantes vinculados a ESTA turma
try {
    // Certifique-se de que os nomes das colunas batem com o seu banco (ex: turma_id ou id_turma)
    $stmt = $pdo->prepare("
        SELECT p.id, p.nome 
        FROM participantes p
        JOIN turmas_participantes tp ON p.id = tp.participante_id
        WHERE tp.turma_id = ?
        ORDER BY p.nome ASC
    ");
    $stmt->execute([$turma_id]);
    $participantes = $stmt->fetchAll();
    
    // DEBUG TEMPORÁRIO: Se continuar vazio, vamos testar se existem registros na tabela pivô
    if (empty($participantes)) {
        $test_stmt = $pdo->prepare("SELECT COUNT(*) FROM turmas_participantes WHERE turma_id = ?");
        $test_stmt->execute([$turma_id]);
        $total_na_pivo = $test_stmt->fetchColumn();
        
        if ($total_na_pivo == 0) {
            // Isso confirma o Motivo 1: Não há ninguém matriculado nesta turma na tabela 'turmas_participantes'
            echo "<div class='alert alert-warning m-3'><i class='bi bi-exclamation-triangle'></i> <strong>Aviso de Sistema:</strong> Nenhum participante está vinculado ao ID desta turma ($turma_id) na tabela 'turmas_participantes'. Vincule os alunos à turma primeiro.</div>";
        }
    }

} catch (PDOException $e) {
    die("Erro na query de participantes: " . $e->getMessage());
}
    // 3. Buscar presenças já gravadas
    $stmt = $pdo->prepare("SELECT * FROM presencas WHERE sessao_id = ?");
    $stmt->execute([$sessao_id]);
    $presencasDb = $stmt->fetchAll();

    $mapPresencas = [];
    foreach ($presencasDb as $p) {
        $mapPresencas[$p['participante_id']] = $p;
    }

    // 4. Salvar Presenças
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
    // Atualiza a cor de fundo da linha com base no status selecionado
    function atualizarEstiloLinha(pid) {
        const row = document.getElementById('row-' + pid);
        const select = row.querySelector('.status-select');
        
        // Remove classes anteriores
        row.classList.remove('table-success', 'table-danger', 'table-warning');
        
        // Adiciona a nova classe
        if (select.value === 'presente') row.classList.add('table-success');
        else if (select.value === 'falta') row.classList.add('table-danger');
        else if (select.value === 'justificado') row.classList.add('table-warning');
        
        atualizarContadores();
    }

    // Botão "Marcar Todos"
    function marcarTodos(tipo) {
        document.querySelectorAll('.status-select').forEach(select => {
            select.value = tipo;
            const pid = select.name.match(/\[(\d+)\]/)[1];
            atualizarEstiloLinha(pid);
        });
    }

    // Atualiza os números nos cards do topo
    function atualizarContadores() {
        let p = 0, f = 0, j = 0;
        document.querySelectorAll('.status-select').forEach(select => {
            if (select.value === 'presente') p++;
            else if (select.value === 'falta') f++;
            else if (select.value === 'justificado') j++;
        });
        
        document.getElementById('countPresente').innerText = p;
        document.getElementById('countFalta').innerText = f;
        document.getElementById('countJustificado').innerText = j;
    }

    // Inicializa o estilo das linhas ao carregar a página
    window.onload = () => {
        document.querySelectorAll('.status-select').forEach(select => {
            const pid = select.name.match(/\[(\d+)\]/)[1];
            atualizarEstiloLinha(pid);
        });
    };
    </script>

    <?php require_once __DIR__. '/../layout/admin_footer.php';