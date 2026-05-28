<?php
require_once __DIR__ . "/../config/conexao.php";
require_once __DIR__ . '/../config/auth.php';
auth();
can('admin');

require_once __DIR__ . "/../layout/admin_header.php";

$usuarios = $pdo->query("
    SELECT id, nome, email 
    FROM usuarios 
    ORDER BY id DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    .table-responsive { overflow: visible !important; }
    .avatar-circle {
        width: 35px;
        height: 35px;
        background-color: var(--ser-orange);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-weight: bold;
        font-size: 0.85rem;
    }
    .user-email { color: #6c757d; font-size: 0.9rem; }
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0 text-dark">
                <i class="bi bi-person-gear me-2" style="color: var(--ser-blue);"></i>Controle de Usuários
            </h3>
            <p class="text-muted small mb-0">Gerencie quem tem acesso ao painel administrativo.</p>
        </div>
        <a href="usuarios_form.php" class="btn btn-success shadow-sm px-4">
            <i class="bi bi-person-plus-fill me-2"></i>Novo Usuário
        </a>
    </div>

    <?php if (isset($_GET['sucesso'])): ?>
        <div class="alert alert-success border-0 shadow-sm border-start border-4 border-success">
            <i class="bi bi-check-circle-fill me-2"></i> Usuário removido do sistema com sucesso.
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3" width="100">ID</th>
                            <th>Usuário</th>
                            <th>E-mail Acadêmico / Profissional</th>
                            <th class="text-end pe-4">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $u): ?>
                        <tr>
                            <td class="ps-4">
                                <span class="text-muted fw-bold">#<?= $u['id'] ?></span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-3">
                                        <?= strtoupper(substr($u['nome'], 0, 1)) ?>
                                    </div>
                                    <span class="fw-semibold text-dark"><?= htmlspecialchars($u['nome']) ?></span>
                                </div>
                            </td>
                            <td>
                                <span class="user-email">
                                    <i class="bi bi-envelope-at me-1 opacity-50"></i><?= htmlspecialchars($u['email']) ?>
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <a href="usuarios_form.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-outline-warning" title="Editar Usuário">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a href="usuarios_excluir.php?id=<?= $u['id'] ?>" 
                                       class="btn btn-sm btn-outline-danger" 
                                       onclick="return confirm('Atenção: Esta ação não pode ser desfeita. Excluir usuário?')" 
                                       title="Excluir Usuário">
                                        <i class="bi bi-trash3"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>

                        <?php if (count($usuarios) === 0): ?>
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <i class="bi bi-people mb-2 d-block fs-2 opacity-25"></i>
                                <span class="text-muted">Nenhum administrador cadastrado.</span>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="dashboard.php" class="btn btn-link text-decoration-none p-0 text-secondary small">
            <i class="bi bi-arrow-left me-1"></i>Voltar ao Painel Principal
        </a>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/admin_footer.php'; ?>