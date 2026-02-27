<?php 
require_once __DIR__ . "/../../config/protect.php";
require_once __DIR__ . "/../../config/conexao.php";
require_once __DIR__ . "/../../layout/admin_header.php";

// Buscar turmas no banco
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

<div class="card shadow-sm">
    <div class="card-body">

        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th width="180">Ações</th>
                </tr>
            </thead>
            <tbody>

                <?php if (!empty($turmas)): ?>
                    <?php foreach ($turmas as $t): ?>
                        <tr>
                            <td><?= $t['id'] ?></td>
                            <td><?= htmlspecialchars($t['nome']) ?></td>
                            <td><?= htmlspecialchars($t['descricao']) ?></td>
                            <td>
                                <a href="turmas_editar.php?id=<?= $t['id'] ?>" 
                                   class="btn btn-sm btn-primary">
                                    Editar
                                </a>

                                <a href="turmas_excluir.php?id=<?= $t['id'] ?>"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Deseja excluir?')">
                                    Excluir
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">
                            Nenhuma turma cadastrada.
                        </td>
                    </tr>
                <?php endif; ?>

            </tbody>
        </table>

    </div>
</div>

<a href="dashboard.php" class="btn btn-secondary mt-3">
    Voltar
</a>

<?php require_once __DIR__ . '/../../layout/admin_footer.php'; ?>
