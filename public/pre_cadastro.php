<?php require_once __DIR__ . "/../layout/public_header.php"; ?>

<div class="row justify-content-center mt-5">
    <div class="col-md-6">

        <h3 class="mb-4 text-center">Pré-Cadastro</h3>

        <?php if (isset($_GET['erro'])): ?>
            <div class="alert alert-danger">
                <?php
                switch ($_GET['erro']) {
                    case 'cpf':
                        echo "CPF já cadastrado.";
                        break;
                    case 'cpf_invalido':
                        echo "CPF inválido.";
                        break;
                    case 'campos':
                        echo "Preencha todos os campos obrigatórios.";
                        break;
                    case 'email':
                        echo "Email inválido.";
                        break;
                    default:
                        echo "Erro ao enviar pré-cadastro.";
                }
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['sucesso'])): ?>
            <div class="alert alert-success">
                Pré-cadastro enviado com sucesso!
            </div>
        <?php endif; ?>
        
        <form method="POST" action="pre_cadastro_salvar.php">

            <div class="mb-3">
                <label class="form-label">Nome Completo</label>
                <input type="text" name="nome" id="nome" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">CPF</label>
                <input type="text" name="cpf" id="cpf" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Data de Nascimento</label>
                <input type="date" name="data_nascimento" id="data_nascimento" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Telefone</label>
                <input type="text" name="telefone" id="telefone" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Endereço</label>
                <input type="text" name="endereco" id="endereco" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Bairro</label>
                <input type="text" name="bairro" id="bairro" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary w-100">
                Enviar Pré-Cadastro
            </button>

        </form>

    </div>
</div>

<!-- IMASK -->
<script src="https://unpkg.com/imask"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        // CPF
        IMask(document.getElementById('cpf'), {
            mask: '000.000.000-00'
        });

        // Telefone
        IMask(document.getElementById('telefone'), {
            mask: '(00) 00000-0000'
        });

    });
</script>

<?php require_once __DIR__ . "/../layout/public_footer.php"; ?>