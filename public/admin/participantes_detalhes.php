<?php
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../config/auth.php';

auth();
canAny(['admin', 'atendente']);

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: participantes_lista.php");
    exit;
}

// 👤 PARTICIPANTE + TURMA
$sql = $pdo->prepare("
    SELECT p.*, t.nome AS turma_nome, t.responsavel
    FROM participantes p
    LEFT JOIN turmas t ON t.id = p.turma_id
    WHERE p.id = ?
");
$sql->execute([$id]);
$participante = $sql->fetch(PDO::FETCH_ASSOC);

if (!$participante) {
    die("Participante não encontrado");
}

$stmt = $pdo->prepare("
    SELECT 
        l.acao,
        l.tipo,
        l.data,
        u.nome as usuario_nome
    FROM logs l
    LEFT JOIN usuarios u ON u.id = l.usuario_id
    WHERE l.entidade = 'participantes'
    AND l.entidade_id = ?
    ORDER BY l.data DESC
");
$stmt->execute([$id]);
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/../../layout/admin_header.php';
?>

<div class="container mt-4">

    <h3 class="mb-4">Detalhes do Participante</h3>

    <!-- 👤 DADOS PESSOAIS -->
    <div class="card mb-3 shadow-sm">
        <div class="card-body">
            <h5 class="mb-3">Dados Pessoais</h5>

            <div class="row">
                <div class="col-md-6">
                    <p><strong>Nome:</strong> <?= htmlspecialchars($participante['nome']) ?></p>
                    <p><strong>CPF:</strong> <?= htmlspecialchars($participante['cpf']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($participante['email']) ?></p>
                </div>

                <div class="col-md-6">
                    <p><strong>Telefone:</strong> <?= htmlspecialchars($participante['telefone']) ?></p>
                    <p><strong>Data de Nascimento:</strong>
                        <?= date('d/m/Y', strtotime($participante['data_nascimento'])) ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- 📍 ENDEREÇO -->
    <div class="card mb-3 shadow-sm">
        <div class="card-body">
            <h5 class="mb-3">Endereço</h5>

            <p><strong>Endereço:</strong> <?= htmlspecialchars($participante['endereco']) ?></p>
            <p><strong>Bairro:</strong> <?= htmlspecialchars($participante['bairro']) ?></p>
        </div>
    </div>

    <!-- 🏫 TURMA -->
    <div class="card mb-3 shadow-sm">
        <div class="card-body">
            <h5 class="mb-3">Informações da Turma</h5>

            <p><strong>Turma:</strong> <?= htmlspecialchars($participante['turma_nome'] ?? 'Não vinculada') ?></p>
            <p><strong>Responsável:</strong> <?= htmlspecialchars($participante['responsavel'] ?? '-') ?></p>
        </div>
    </div>

    <!-- 📝 OBSERVAÇÕES -->
    <?php if (!empty($participante['observacoes'])): ?>
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <h5 class="mb-3">Observações</h5>

                <p><?= nl2br(htmlspecialchars($participante['observacoes'])) ?></p>
            </div>
        </div>
    <?php endif; ?>

    <!-- 📜 HISTÓRICO -->
    <div class="card mb-3 shadow-sm">
        <div class="card-body">

            <h5 class="mb-3">Histórico</h5>

            <?php if (empty($logs)): ?>
                <p class="text-muted">Nenhuma ação registrada.</p>
            <?php else: ?>

                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Data</th>
                            <th>Usuário</th>
                            <th>Tipo</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php foreach ($logs as $log): ?>
                            <tr>
                                <td><?= date('d/m/Y H:i', strtotime($log['data'])) ?></td>

                                <td>
                                    <?= htmlspecialchars($log['usuario_nome'] ?? 'Sistema') ?>
                                </td>

                                <td>
                                    <span class="badge 
                                    <?= $log['tipo'] == 'CREATE' ? 'bg-success' : '' ?>
                                    <?= $log['tipo'] == 'UPDATE' ? 'bg-warning text-dark' : '' ?>
                                    <?= $log['tipo'] == 'DELETE' ? 'bg-danger' : '' ?>">

                                        <?= htmlspecialchars($log['tipo']) ?>
                                    </span>
                                </td>

                                <td><?= htmlspecialchars($log['acao']) ?></td>
                            </tr>
                        <?php endforeach; ?>

                    </tbody>
                </table>

            <?php endif; ?>

        </div>
    </div>

    <a href="relatorio_participantes.php?id=<?= $participante['id'] ?>"
        class="btn btn-danger"
        target="_blank">
        Gerar PDF
    </a>

    <a href="participantes_lista.php" class="btn btn-secondary">
        Voltar
    </a>

</div>

<?php require_once __DIR__ . '/../../layout/admin_footer.php'; ?>