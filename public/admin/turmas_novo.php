<?php
require_once __DIR__ . "/../../config/protect.php";
require_once __DIR__ .'../config/conexao.php';
$titulo = "Nova Turma";
require_once __DIR__ . '/../layout/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">

        <div class="card shadow">
            <div class="card-body">

                <h4 class="mb-4">Nova Turma</h4>

                <form action="turmas_salvar.php" method="post">

                    <div class="mb-3">
                        <label class="form-label">Nome da Turma</label>
                        <input type="text"
                               name="nome"
                               class="form-control"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descrição</label>
                        <textarea name="descricao"
                                  class="form-control"
                                  rows="3"></textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="turmas_lista.php" class="btn btn-secondary">
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

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
