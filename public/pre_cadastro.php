<?php require_once __DIR__ . "/../layout/public_header.php"; ?>

<div class="row justify-content-center mt-5">
    <div class="col-md-6">

        <h3 class="mb-4 text-center">Pré-Cadastro</h3>

        <?php if (isset($_GET['erro'])): ?>
            <div class="alert alert-danger">
                CPF já cadastrado.
            </div>
        <?php endif; ?>

        <form method="POST" action="pre_cadastro_salvar.php">

            <div class="mb-3">
                <label class="form-label">Nome Completo</label>
                <input type="text" name="nome" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">CPF</label>
                <input type="text" name="cpf" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Idade</label>
                <input type="number" name="idade" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Naturalidade</label>
                <input type="text" name="naturalidade" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Telefone</label>
                <input type="text" name="telefone" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary w-100">
                Enviar Pré-Cadastro
            </button>

        </form>

    </div>
</div>

<?php require_once __DIR__ . "/../layout/public_footer.php"; ?>