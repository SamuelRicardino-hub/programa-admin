<?php
require_once __DIR__ . "/../config/conexao.php";
require_once __DIR__ . "/../layout/admin_header.php";
require_once __DIR__ . '/../config/auth.php';

auth();
can('admin');

$usuarios = $pdo->query("
    SELECT id, nome, email 
    FROM usuarios 
    ORDER BY id DESC
")->fetchAll(PDO::FETCH_ASSOC);

$titulo = "Usuários";
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Usuários do Sistema</h4>

    <a href="usuarios_form.php" class="btn btn-success">
        + Novo Usuário
    </a>
</div>

<?php if (isset($_GET['sucesso'])): ?>
    <div class="alert alert-success">
        Usuário excluído com sucesso.
    </div>
<?php endif; ?>

<div class="table-responsive">
    <table class="table table-striped table-bordered align-middle">
        <thead class="table-dark">
            <tr>
                <th width="80">ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th width="180" class="text-center">Ações</th>
            </tr>
        </thead>
        <tbody>

        <?php foreach ($usuarios as $u): ?>
            <tr>
                <td><?= $u['id'] ?></td>
                <td><?= htmlspecialchars($u['nome']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td class="text-center">

                    <a href="usuarios_form.php?id=<?= $u['id'] ?>"
                       class="btn btn-sm btn-warning">
                        Editar
                    </a>

                    <a href="usuarios_excluir.php?id=<?= $u['id'] ?>"
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Deseja excluir este usuário?')">
                        Excluir
                    </a>

                </td>
            </tr>
        <?php endforeach; ?>

        <?php if (count($usuarios) === 0): ?>
            <tr>
                <td colspan="4" class="text-center text-muted">
                    Nenhum usuário cadastrado.
                </td>
            </tr>
        <?php endif; ?>

        </tbody>
    </table>
</div>

<a href="dashboard.php" class="btn btn-secondary mt-3">
    Voltar ao Dashboard
</a>

<?php require_once __DIR__ . '/../layout/admin_footer.php'; ?>
