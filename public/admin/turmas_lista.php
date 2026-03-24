<?php
require_once __DIR__ . "/../../config/conexao.php";
require_once __DIR__ . "/../../layout/admin_header.php";
require_once __DIR__ . '/../../config/auth.php';

auth();
canAny(['admin', 'atendente']);


$stmt = $pdo->query("
    SELECT id, nome, responsavel, data_inicio, data_fim, status
    FROM turmas
    ORDER BY id DESC
");
$turmas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2 class="mb-4">Turmas</h2>

<?php if (isset($_GET['erro']) && $_GET['erro'] === 'turma_em_uso'): ?>
    <div class="alert alert-danger">
        Não é possível excluir uma turma com participantes.
    </div>
<?php endif; ?>

<?php if (isset($_GET['excluido'])): ?>
    <div class="alert alert-success">
        Turma excluída com sucesso.
    </div>
<?php endif; ?>

<div class="mb-3">
    <a href="turmas_novo.php" class="btn btn-success">
        + Cadastrar Turma
    </a>
</div>
<?php if (isset($_GET['msg']) && $_GET['msg'] === 'criado'): ?>
    <div class="alert alert-success">
        Turma criada com sucesso!
    </div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">

        <table class="table table-striped">

            <tr>
                <th>Nome</th>
                <th>Responsável</th>
                <th>Início</th>
                <th>Fim</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>

            <?php foreach ($turmas as $t): ?>

                <tr>

                    <td><?= htmlspecialchars($t['nome']) ?></td>
                    <td><?= htmlspecialchars($t['responsavel']) ?></td>
                    <td><?= htmlspecialchars($t['data_inicio']) ?></td>
                    <td><?= htmlspecialchars($t['data_fim']) ?></td>
                    <td><?= htmlspecialchars($t['status']) ?></td>

                    <td>

                        <a href="turmas_editar.php?id=<?= $t['id'] ?>" class="btn btn-sm btn-warning">
                            ✏️ Editar
                        </a>

                        <a href="turmas_excluir.php?id=<?= $t['id'] ?>" class="btn btn-sm btn-danger">
                            ❌ Excluir
                        </a>

                        <a href="participantes_lista.php?turma_id=<?= $t['id'] ?>" class="btn btn-sm btn-dark">
                            👥 Ver Participantes
                        </a>

                        <a href="relatorio_turma.php?turma_id=<?= $t['id'] ?>"
                            class="btn btn-sm btn-primary" target="_blank">
                            📄 PDF
                        </a>

                        <a href="exportar_participantes_excel.php?turma_id=<?= $t['id'] ?>"
                            class="btn btn-sm btn-success">
                            📥 Excel
                        </a>



                    </td>

                </tr>

            <?php endforeach; ?>

        </table>

    </div>
</div>

<a href="dashboard.php" class="btn btn-secondary mt-3">
    Voltar
</a>

<?php require_once __DIR__ . '/../../layout/admin_footer.php'; ?>