<?php require_once __DIR__ . "/../layout/public_header.php"; ?>

<?php if (isset($_GET['sucesso'])): ?>
    <div class="alert alert-success mt-4 text-center">
        Pré-cadastro enviado com sucesso!
    </div>
<?php endif; ?>

<div class="hero">
    <h1 class="mb-4">Bem-vindo a Assistência Social</h1>

    <p class="lead mb-4">
        Faça seu pré-cadastro para participar do programa.
    </p>

    <a href="pre_cadastro.php" class="btn btn-primary btn-lg">
        Fazer Pré-Cadastro
    </a>
</div>

<?php require_once __DIR__ . "/../layout/public_footer.php"; ?>