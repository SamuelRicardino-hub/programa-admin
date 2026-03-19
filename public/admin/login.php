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

                        <form method="POST" action="autenticar.php">
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