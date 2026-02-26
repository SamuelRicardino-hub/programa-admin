<?php
require_once __DIR__ . "/../../config/protect.php";
require_once __DIR__ . "/../../config/conexao.php";
require_once __DIR__ . "/../../layout/admin_header.php";

$id = $_GET['id'] ?? null;
$usuario = null;

if ($id) {
    $sql = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
    $sql->execute([$id]);
    $usuario = $sql->fetch(PDO::FETCH_ASSOC);
}

$titulo = $usuario ? "Editar Usuário" : "Novo Usuário";
require_once __DIR__ . '/../layout/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">

        <div class="card shadow">
            <div class="card-body">

                <h4 class="mb-4">
                    <?= $usuario ? 'Editar Usuário' : 'Novo Usuário' ?>
                </h4>

                <form action="usuarios_salvar.php" method="post">

                    <input type="hidden" name="id"
                           value="<?= $usuario['id'] ?? '' ?>">

                    <div class="mb-3">
                        <label class="form-label">Nome</label>
                        <input type="text"
                               name="nome"
                               class="form-control"
                               value="<?= htmlspecialchars($usuario['nome'] ?? '') ?>"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Login</label>
                        <input type="text"
                               name="login"
                               class="form-control"
                               value="<?= htmlspecialchars($usuario['login'] ?? '') ?>"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Senha</label>
                        <input type="password"
                               name="senha"
                               class="form-control"
                               placeholder="<?= $usuario ? 'Nova senha (opcional)' : 'Senha' ?>"
                               <?= $usuario ? '' : 'required' ?>>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="usuarios_lista.php" class="btn btn-secondary">
                            Voltar
                        </a>

                        <button type="submit" class="btn btn-primary">
                            Salvar
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>

<?php require_once __DIR__ . '/../layout/admin_footer.php'; ?>
