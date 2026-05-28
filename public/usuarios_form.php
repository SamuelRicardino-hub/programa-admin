<?php
require_once __DIR__ . "/../config/conexao.php";
require_once __DIR__ . "/../config/auth.php";
require_once __DIR__ . "/../layout/admin_header.php";

auth();
can('admin');

$id = $_GET['id'] ?? null;
$usuario = null;

if ($id) {
    $sql = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
    $sql->execute([$id]);
    $usuario = $sql->fetch(PDO::FETCH_ASSOC);
}

// Título dinâmico para a aba e para o cabeçalho
$titulo_pagina = $usuario ? "Editar Usuário" : "Novo Usuário";
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            
            <div class="mb-4 d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold text-dark mb-1">
                        <i class="bi <?= $usuario ? 'bi-person-gear text-primary' : 'bi-person-plus text-success' ?> me-2"></i>
                        <?= $titulo_pagina ?>
                    </h3>
                    <p class="text-muted mb-0 small">
                        <?= $usuario ? "Alterando dados de: <strong>{$usuario['nome']}</strong>" : "Preencha os dados para cadastrar um novo colaborador." ?>
                    </p>
                </div>
                <a href="usuarios_lista.php" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form action="usuarios_salvar.php" method="post">
                        <input type="hidden" name="id" value="<?= $usuario['id'] ?? '' ?>">

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-uppercase">Nome Completo</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-person"></i></span>
                                <input type="text" name="nome" class="form-control" 
                                       value="<?= htmlspecialchars($usuario['nome'] ?? '') ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-uppercase">E-mail de Acesso</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control" 
                                       value="<?= htmlspecialchars($usuario['email'] ?? '') ?>" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase">Nível de Permissão</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-shield-lock"></i></span>
                                <select name="nivel" class="form-select" required>
                                    <option value="atendente" <?= ($usuario['nivel'] ?? '') == 'atendente' ? 'selected' : '' ?>>Atendente (Operacional)</option>
                                    <option value="admin" <?= ($usuario['nivel'] ?? '') == 'admin' ? 'selected' : '' ?>>Administrador (Total)</option>
                                </select>
                            </div>
                        </div>

                        <?php if (!$usuario): ?>
                            <div class="mb-4">
                                <label class="form-label fw-bold small text-uppercase text-success">
                                    Definir Senha Provisória
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted"><i class="bi bi-key"></i></span>
                                    <input type="password" name="senha" class="form-control" 
                                           placeholder="Digite a senha inicial para o primeiro acesso" required>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="mb-4 p-3 bg-light rounded border text-muted small">
                                <i class="bi bi-info-circle-fill me-1 text-primary"></i> 
                                Por motivos de segurança, senhas não podem ser alteradas por administradores. O colaborador deve redefinir sua própria senha no menu <strong>"Minha Conta"</strong>.
                            </div>
                        <?php endif; ?>

                        <hr class="my-4">

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn <?= $usuario ? 'btn-primary' : 'btn-success' ?> py-2 fw-bold shadow-sm">
                                <i class="bi bi-check-lg me-2"></i><?= $usuario ? 'Atualizar Dados' : 'Cadastrar Usuário' ?>
                            </button>
                            <a href="usuarios_lista.php" class="btn btn-light border py-2">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>

            <?php if(!$usuario): ?>
            <div class="mt-4 alert alert-info border-0 shadow-sm d-flex align-items-center">
                <i class="bi bi-info-circle fs-4 me-3"></i>
                <div class="small">
                    O novo usuário poderá acessar o sistema imediatamente após o cadastro usando o e-mail e a senha definida acima. Recomende a ele realizar a troca no primeiro acesso.
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/admin_footer.php'; ?>