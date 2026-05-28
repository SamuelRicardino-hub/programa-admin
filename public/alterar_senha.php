<?php
// Ative o report de erros para ajudar no desenvolvimento
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/auth.php';


auth(); // Garante que o usuário está logado

// Captura o ID do usuário diretamente da sessão (Segurança: ele só altera a dele)
$usuario_id = $_SESSION['usuario_id'] ?? $_SESSION['usuario']['id'];

$erro = null;
$sucesso = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senha_atual = $_POST['senha_atual'] ?? '';
    $nova_senha  = $_POST['nova_senha'] ?? '';
    $confirma_senha = $_POST['confirma_senha'] ?? '';

    if (empty($senha_atual) || empty($nova_senha) || empty($confirma_senha)) {
        $erro = "Todos os campos são obrigatórios.";
    } elseif ($nova_senha !== $confirma_senha) {
        $erro = "A nova senha e a confirmação não conferem.";
    } elseif (strlen($nova_senha) < 6) {
        $erro = "A nova senha deve ter no mínimo 6 caracteres.";
    } else {
        try {
            // 1. Busca a senha atual no banco para conferir
            $stmt = $pdo->prepare("SELECT senha, nome FROM usuarios WHERE id = ?");
            $stmt->execute([$usuario_id]);
            $user = $stmt->fetch();

            if ($user && password_verify($senha_atual, $user['senha'])) {
                // 2. Criptografa a nova senha
                $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

                // 3. Atualiza no banco
                $update = $pdo->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
                $update->execute([$nova_senha_hash, $usuario_id]);

                // 4. Registra no Log do sistema
                registrarLog($pdo, 'UPDATE', 'usuarios', $usuario_id, "O usuário {$user['nome']} alterou a própria senha.");

                $sucesso = "Senha alterada com sucesso!";
            } else {
                $erro = "A senha atual informada está incorreta.";
            }
        } catch (PDOException $e) {
            $erro = "Erro no banco de dados: " . $e->getMessage();
        }
    }
}

require_once __DIR__ . '/../layout/admin_header.php';
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-shield-lock me-2 text-primary"></i>Alterar Minha Senha</h5>
                    <small class="text-muted">Mantenha seu acesso seguro atualizando sua senha periodicamente.</small>
                </div>
                <div class="card-body p-4">

                    <?php if ($erro): ?>
                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <div><?= htmlspecialchars($erro) ?></div>
                        </div>
                    <?php endif; ?>

                    <?php if ($sucesso): ?>
                        <div class="alert alert-success d-flex align-items-center" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <div><?= htmlspecialchars($sucesso) ?></div>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Senha Atual</label>
                            <input type="password" name="senha_atual" class="form-control" placeholder="Digite sua senha atual" required>
                        </div>
                        
                        <hr class="my-4 text-muted">

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nova Senha</label>
                            <input type="password" name="nova_senha" class="form-control" placeholder="Mínimo de 6 caracteres" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Confirmar Nova Senha</label>
                            <input type="password" name="confirma_senha" class="form-control" placeholder="Repita a nova senha" required>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-key-fill me-1"></i> Atualizar Senha
                            </button>
                            <a href="dashboard.php" class="btn btn-light border">Voltar ao Início</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/admin_footer.php'; ?>