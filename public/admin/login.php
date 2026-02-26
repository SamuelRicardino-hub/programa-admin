<?php
session_start();
require_once __DIR__ . "/../../config/conexao.php";

// Se já estiver logado, redireciona
if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit;
}

$erro = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"]);
    $senha = trim($_POST["senha"]);

    try {

        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senha, $usuario['senha'])) {

            $_SESSION['admin_id'] = $usuario['id'];
            $_SESSION['admin_nome'] = $usuario['nome'];

            header("Location: dashboard.php");
            exit;

        } else {
            $erro = "Email ou senha inválidos.";
        }

    } catch (PDOException $e) {
        $erro = "Erro interno.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login Administrativo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color:#f4f6f9;">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">

            <div class="card shadow">
                <div class="card-body">

                    <h4 class="text-center mb-4">Área Administrativa</h4>

                    <?php if ($erro): ?>
                        <div class="alert alert-danger">
                            <?= $erro ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">

                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Senha</label>
                            <input type="password" name="senha" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Entrar
                        </button>

                    </form>

                    <div class="text-center mt-3">
                        <a href="/programa-admin/public" class="small">
                            Voltar ao site
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>