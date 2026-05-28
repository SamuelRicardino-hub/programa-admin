<?php
// ATIVAÇÃO DE DIAGNÓSTICO: Mantém ativo para monitorar o banco
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../config/conexao.php";
require_once __DIR__ . "/../config/auth.php";
require_once __DIR__ . "/../config/logs.php"; 
require_once __DIR__ . "/../layout/admin_header.php";

auth();
can('admin'); 

// Paginação básica
$pagina = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($pagina < 1) $pagina = 1;
$limite = 50; 
$offset = ($pagina - 1) * $limite;

// Busca o total para a paginação
$total_registros = $pdo->query("SELECT COUNT(*) FROM logs")->fetchColumn();
$total_paginas = ceil($total_registros / $limite);

// Determina qual coluna de data existe na tabela (criado_em ou outra encontrada no erro anterior)
$coluna_data = 'criado_em';
try {
    $pdo->query("SELECT criado_em FROM logs LIMIT 1");
} catch (PDOException $e) {
    // Se der erro, tenta usar outra coluna de data comum (como data ou data_hora)
    $coluna_data = 'data'; 
}

// Busca os logs trazendo os mais recentes primeiro
$sql = $pdo->prepare("SELECT * FROM logs ORDER BY {$coluna_data} DESC LIMIT ? OFFSET ?");
$sql->bindValue(1, $limite, PDO::PARAM_INT);
$sql->bindValue(2, $offset, PDO::PARAM_INT);
$sql->execute();
$logs = $sql->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid py-4">
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h3 class="fw-bold text-dark mb-1">
                <i class="bi bi-journal-text text-secondary me-2"></i>Logs de Auditoria
            </h3>
            <p class="text-muted mb-0 small">Histórico em tempo real de todas as ações realizadas no sistema.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3" style="width: 15%;">Data/Hora</th>
                            <th style="width: 15%;">Usuário / Atividade</th>
                            <th class="text-center" style="width: 10%;">Ação</th>
                            <th style="width: 15%;">Módulo/Tabela</th>
                            <th>Descrição da Atividade</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($logs)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Nenhum log registrado até o momento.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($logs as $l): 
                                // Captura os dados tratando chaves que podem não existir no banco antigo
                                $usuario_nome = $l['usuario_nome'] ?? $l['usuario'] ?? 'Sistema/Anônimo';
                                $usuario_id   = $l['usuario_id'] ?? '';
                                $acao         = $l['acao'] ?? 'INFO';
                                $tabela       = $l['tabela'] ?? 'geral';
                                $descricao    = $l['descricao'] ?? '';
                                $registro_id  = $l['registro_id'] ?? null;
                                $data_log     = $l[$coluna_data] ?? 'now';

                                // Define a cor do badge com base na ação
                                $badge_class = 'bg-secondary';
                                if ($acao === 'CREATE') $badge_class = 'bg-success';
                                if ($acao === 'UPDATE') $badge_class = 'bg-warning text-dark';
                                if ($acao === 'DELETE') $badge_class = 'bg-danger';
                                if ($acao === 'LOGIN')  $badge_class = 'bg-info text-dark';
                            ?>
                            <tr>
                                <td class="ps-4 text-muted small">
                                    <?= date('d/m/Y H:i:s', strtotime($data_log)) ?>
                                </td>
                                <td>
                                    <span class="fw-bold text-dark"><?= htmlspecialchars($usuario_nome, ENT_QUOTES, 'UTF-8') ?></span>
                                    <?php if (!empty($usuario_id)): ?>
                                        <br><small class="text-muted">ID: <?= htmlspecialchars($usuario_id) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge <?= $badge_class ?> px-2.5 py-1.5 small font-monospace">
                                        <?= htmlspecialchars($acao, ENT_QUOTES, 'UTF-8') ?>
                                    </span>
                                </td>
                                <td class="text-secondary fw-semibold">
                                    <i class="bi bi-folder2-open me-1"></i><?= htmlspecialchars($tabela, ENT_QUOTES, 'UTF-8') ?>
                                </td>
                                <td class="text-dark">
                                    <?= !empty($descricao) ? htmlspecialchars($descricao, ENT_QUOTES, 'UTF-8') : htmlspecialchars($usuario_nome, ENT_QUOTES, 'UTF-8') ?>
                                    
                                    <?php if ($registro_id): ?>
                                        <span class="badge bg-light text-muted border ms-1">ID Ref: <?= htmlspecialchars($registro_id) ?></span>
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
                <li class="page-item <?= $pagina <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $pagina - 1 ?>">Anterior</a>
                </li>
                <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                    <li class="page-item <?= $pagina == $i ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= $pagina >= $total_paginas ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $pagina + 1 ?>">Próximo</a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layout/admin_footer.php'; ?>