<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../config/conexao.php";
require_once __DIR__ . "/../config/auth.php";
require_once __DIR__ . "/../layout/admin_header.php";
require_once __DIR__ . "/../config/logs.php";


auth();
can('admin'); 

// Filtros
$busca = isset($_GET['busca']) ? trim($_GET['busca']) : '';
$filtro_tipo = isset($_GET['tipo']) ? trim($_GET['tipo']) : '';

// Paginação
$pagina = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($pagina < 1) $pagina = 1;
$limite = 30; 
$offset = ($pagina - 1) * $limite;

// Monta filtros baseados na estrutura real do seu banco
$condicoes = [];
$params = [];

if (!empty($busca)) {
    $condicoes[] = "(l.acao LIKE ? OR u.nome LIKE ?)";
    $params[] = "%$busca%";
    $params[] = "%$busca%";
}

if (!empty($filtro_tipo)) {
    $condicoes[] = "l.tipo = ?";
    $params[] = $filtro_tipo;
}

$where_sql = !empty($condicoes) ? "WHERE " . implode(" AND ", $condicoes) : "";

// Conta total
$stmt_total = $pdo->prepare("SELECT COUNT(*) FROM logs l LEFT JOIN usuarios u ON l.usuario_id = u.id $where_sql");
$stmt_total->execute($params);
$total_registros = $stmt_total->fetchColumn();
$total_paginas = ceil($total_registros / $limite);

// Query otimizada juntando os dados com a tabela de usuários do seu SQL
$sql_text = "SELECT l.*, u.nome AS funcionario_nome 
             FROM logs l 
             LEFT JOIN usuarios u ON l.usuario_id = u.id 
             $where_sql 
             ORDER BY l.data DESC LIMIT ? OFFSET ?";

$stmt_logs = $pdo->prepare($sql_text);

$idx = 1;
foreach ($params as $p) {
    $stmt_logs->bindValue($idx++, $p);
}
$stmt_logs->bindValue($idx++, $limite, PDO::PARAM_INT);
$stmt_logs->bindValue($idx++, $offset, PDO::PARAM_INT);
$stmt_logs->execute();
$logs = $stmt_logs->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container py-4">
    <div class="mb-4">
        <h3 class="fw-bold text-dark mb-1">
            <i class="bi bi-clock-history text-primary me-2"></i>Histórico de Atividades
        </h3>
        <p class="text-muted mb-0 small">Painel amigável de auditoria do sistema.</p>
    </div>

    <div class="card border-0 shadow-sm mb-4 bg-light">
        <div class="card-body p-3">
            <form method="GET" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="busca" class="form-control border-start-0" 
                               placeholder="Buscar por funcionário ou atividade..." value="<?= htmlspecialchars($busca) ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <select name="tipo" class="form-select form-select-sm">
                        <option value="">Todas as atividades</option>
                        <option value="CREATE" <?= $filtro_tipo === 'CREATE' ? 'selected' : '' ?>>Cadastros (Novos itens)</option>
                        <option value="UPDATE" <?= $filtro_tipo === 'UPDATE' ? 'selected' : '' ?>>Alterações (Edições)</option>
                        <option value="DELETE" <?= $filtro_tipo === 'DELETE' ? 'selected' : '' ?>>Exclusões (Apagados)</option>
                        <option value="APROVACAO" <?= $filtro_tipo === 'APROVACAO' ? 'selected' : '' ?>>Aprovações</option>
                    </select>
                </div>
                <div class="col-md-3 d-grid gap-2 d-md-flex">
                    <button type="submit" class="btn btn-primary btn-sm px-3 fw-bold">Filtrar</button>
                    <?php if(!empty($busca) || !empty($filtro_tipo)): ?>
                        <a href="logs_lista.php" class="btn btn-outline-secondary btn-sm">Limpar</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light border-bottom text-uppercase fs-7 text-muted">
                        <tr>
                            <th class="ps-4 py-3" style="width: 150px;">Data/Hora</th>
                            <th style="width: 200px;">Quem Fez</th>
                            <th class="text-center" style="width: 140px;">Operação</th>
                            <th>Descrição do que aconteceu</th>
                        </tr>
                    </thead>
                    <tbody class="fs-6">
                        <?php if (empty($logs)): ?>
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">Nenhuma atividade encontrada.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($logs as $l): 
                                $quem_fez = $l['funcionario_nome'] ?? 'Sistema / ID: '.$l['usuario_id'];
                                $tipo_acao = strtoupper($l['tipo'] ?? 'UPDATE');
                                $descricao_evento = $l['acao'] ?? 'Ação não descrita';
                                $modulo = $l['entidade'] ?? '';

                                // Tradução dos termos técnicos para o usuário leigo
                                $acao_traduzida = "Alterou";
                                $badge_class = "bg-warning-subtle text-warning-emphasis border border-warning-subtle";
                                $icone = "bi-pencil-square";

                                if ($tipo_acao === 'CREATE') {
                                    $acao_traduzida = "Cadastrou";
                                    $badge_class = "bg-success-subtle text-success border border-success-subtle";
                                    $icone = "bi-plus-circle-fill";
                                } elseif ($tipo_acao === 'DELETE') {
                                    $acao_traduzida = "Excluiu";
                                    $badge_class = "bg-danger-subtle text-danger border border-danger-subtle";
                                    $icone = "bi-trash3-fill";
                                } elseif ($tipo_acao === 'APROVACAO') {
                                    $acao_traduzida = "Aprovou";
                                    $badge_class = "bg-info-subtle text-info-emphasis border border-info-subtle";
                                    $icone = "bi-check-circle-fill";
                                }
                            ?>
                            <tr class="border-bottom">
                                <td class="ps-4 text-muted small">
                                    <i class="bi bi-calendar3 me-1"></i> <?= date('d/m/Y', strtotime($l['data'])) ?><br>
                                    <i class="bi bi-clock text-muted me-1"></i> <?= date('H:i:s', strtotime($l['data'])) ?>
                                </td>
                                <td>
                                    <span class="fw-semibold text-dark"><?= htmlspecialchars($quem_fez) ?></span>
                                </td>
                                <td class="text-center">
                                    <span class="badge <?= $badge_class ?> px-2.5 py-1.5 rounded-pill w-100 fw-medium small">
                                        <i class="bi <?= $icone ?> me-1"></i> <?= $acao_traduzida ?>
                                    </span>
                                </td>
                                <td class="text-dark py-3">
                                    <div class="mb-0 text-wrap" style="max-width: 600px;">
                                        <?= htmlspecialchars($descricao_evento) ?>
                                    </div>
                                    <?php if(!empty($modulo)): ?>
                                        <small class="text-muted d-block mt-1" style="font-size: 0.73rem;">
                                            Setor: <span class="bg-light px-1 rounded border text-uppercase"><?= htmlspecialchars($modulo) ?></span>
                                        </small>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php if ($total_paginas > 1): ?>
        <nav class="mt-4">
            <ul class="pagination justify-content-center pagination-sm">
                <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                    <li class="page-item <?= $pagina == $i ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>&busca=<?= urlencode($busca) ?>&tipo=<?= urlencode($filtro_tipo) ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layout/admin_footer.php'; ?>