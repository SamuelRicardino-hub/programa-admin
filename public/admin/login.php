<?php
require_once __DIR__ . "/../../config/protect.php";
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - Sistema</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center vh-100">

    <div class="card shadow p-4" style="width: 100%; max-width: 400px;">

        <h3 class="text-center mb-4">Login do Sistema</h3>

        <?php if (isset($_GET['erro'])): ?>
            <div class="alert alert-danger">
                Email ou senha inválidos
            </div>
        <?php endif; ?>

        <form action="autenticar.php" method="post">

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Senha</label>
                <input type="password" name="senha" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                Entrar
            </button>

        </form>

    </div>

</div>

</body>
</html>
