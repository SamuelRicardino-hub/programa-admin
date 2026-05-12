<?php
require_once __DIR__ . "/../config/conexao.php";
require_once __DIR__ . "/../config/auth.php";
require_once __DIR__ . "/../layout/admin_header.php";

auth();
can('admin');

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: usuarios_lista.php");
    exit;
}

$sql = $pdo->prepare("SELECT id, nome, email, nivel FROM usuarios WHERE id = :id");
$sql->bindParam(':id', $id, PDO::PARAM_INT);
$sql->execute();

$usuario = $sql->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    header("Location: usuarios_lista.php");
    exit;
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            
            <div class="mb-4 d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold text-dark mb-1">
                        <i class="bi bi-person-gear me-2 text-primary"></i>Editar Usuário
                    </h3>
                    <p class="text-muted mb-0">Gerencie as credenciais de acesso ao sistema.</p>
                </div>
                <a href="usuarios_lista.php" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form action="usuarios_atualizar.php" method="post">
                        <input type="hidden" name="id" value="<?= $usuario['id'] ?>">

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-uppercase">Nome Completo</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-person"></i></span>
                                <input type="text" name="nome" class="form-control" 
                                       value="<?= htmlspecialchars($usuario['nome']) ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-uppercase">E-mail de Acesso</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control" 
                                       value="<?= htmlspecialchars($usuario['email']) ?>" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase text-danger">Nova Senha</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-shield-lock"></i></span>
                                <input type="password" name="senha" class="form-control" 
                                       placeholder="••••••••">
                            </div>
                            <div class="form-text mt-2 text-muted italic">
                                <i class="bi bi-info-circle me-1"></i> Deixe em branco se não quiser alterar a senha atual.
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary py-2 fw-bold shadow-sm">
                                <i class="bi bi-save me-2"></i>Salvar Alterações
                            </button>
                            <a href="usuarios_lista.php" class="btn btn-light border py-2">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mt-4 card bg-light border-0 shadow-sm">
                <div class="card-body py-3 d-flex align-items-center small text-muted">
                    <i class="bi bi-shield-check fs-4 me-3 text-success"></i>
                    <div>
                        Certifique-se de que o e-mail inserido é único e que o usuário possui permissões adequadas para sua função.
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/admin_footer.php'; ?>