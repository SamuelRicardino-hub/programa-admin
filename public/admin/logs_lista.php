<?php
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../layout/admin_header.php';

auth();
canAny(['admin']); // 🔐 só admin

// Buscar logs com nome do usuário
$logs = $pdo->query("
    SELECT 
        l.*, 
        u.nome AS usuario_nome
    FROM logs l
    LEFT JOIN usuarios u ON u.id = l.usuario_id
    ORDER BY l.data DESC
    LIMIT 100
")->fetchAll(PDO::FETCH_ASSOC);
?>

<h2 class="mb-4">Histórico de Ações</h2>

<div class="card shadow-sm">
    <div class="card-body">

        <table class="table table-striped table-hover">

            <thead>
                <tr>
                    <th>Usuário</th>
                    <th>Tipo</th>
                    <th>Entidade</th>
                    <th>Ação</th>
                    <th>Data</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($logs as $log): ?>
                    <tr>
                        <td><?= htmlspecialchars($log['usuario_nome'] ?? 'Sistema') ?></td>

                        <td>
                            <span class="badge bg-secondary">
                                <?= htmlspecialchars($log['tipo']) ?>
                            </span>
                        </td>

                        <td><?= htmlspecialchars($log['entidade']) ?></td>

                        <td><?= htmlspecialchars($log['acao']) ?></td>

                        <td><?= date('d/m/Y H:i', strtotime($log['data'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>

    </div>
</div>

<?php require_once __DIR__ . '/../../layout/admin_footer.php'; ?>