<?php
require_once __DIR__ . "/../../config/protect.php";
require_once __DIR__ . "/../../config/conexao.php";
require_once __DIR__ . "/../../layout/admin_header.php";

$stmt = $pdo->query("SELECT id, nome, descricao FROM turmas ORDER BY id DESC");
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

                    <td><?= $t['nome'] ?></td>
                    <td><?= $t['responsavel'] ?></td>
                    <td><?= $t['data_inicio'] ?></td>
                    <td><?= $t['data_fim'] ?></td>
                    <td><?= $t['status'] ?></td>

                    <td>

                        <a href="turma_form.php?id=<?= $t['id'] ?>" class="btn btn-sm btn-primary">
                            Editar
                        </a>

                        <a href="turma_excluir.php?id=<?= $t['id'] ?>" class="btn btn-sm btn-danger">
                            Excluir
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