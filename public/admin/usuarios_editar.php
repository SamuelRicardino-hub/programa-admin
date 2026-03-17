<?php
require_once __DIR__ . "/../../config/conexao.php";
require_once __DIR__ . "/../../layout/admin_header.php";
require_once __DIR__ .'/../../config/auth.php';

auth();
can('admin');

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: usuarios_lista.php");
    exit;
}

$sql = $pdo->prepare("SELECT id, nome, email FROM usuarios WHERE id = :id");
$sql->bindParam(':id', $id, PDO::PARAM_INT);
$sql->execute();

$usuario = $sql->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    header("Location: usuarios_lista.php");
    exit;
}

$titulo = "Editar Usuário";
require_once __DIR__ . '/../layout/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">

        <div class="card shadow">
            <div class="card-body">

                <h4 class="mb-4">Editar Usuário</h4>

                <form action="usuarios_atualizar.php" method="post">

                    <input type="hidden" name="id" value="<?= $usuario['id'] ?>">

                    <div class="mb-3">
                        <label class="form-label">Nome</label>
                        <input type="text"
                               name="nome"
                               class="form-control"
                               value="<?= htmlspecialchars($usuario['nome']) ?>"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="text"
                               name="email"
                               class="form-control"
                               value="<?= htmlspecialchars($usuario['email']) ?>"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nova Senha</label>
                        <input type="password"
                               name="senha"
                               class="form-control"
                               placeholder="Deixe em branco para manter a senha atual">
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="usuarios_lista.php" class="btn btn-secondary">
                            Voltar
                        </a>

                        <button type="submit" class="btn btn-primary">
                            Salvar Alterações
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>

<?php require_once __DIR__ . '/../layout/admin_footer.php'; ?>
