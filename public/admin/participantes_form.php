<?php
require_once __DIR__ . "/../../config/protect.php";
require_once __DIR__ . "/../../config/conexao.php";
require_once __DIR__ . "/../../layout/admin_header.php";
$titulo = "Novo Participante";


// Busca turmas
$sql = $pdo->query("SELECT id, nome FROM turmas ORDER BY nome");
$turmas = $sql->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="card shadow p-4">

    <h3 class="mb-4">Cadastrar Novo Participante</h3>

    <?php if (isset($_GET['erro']) && $_GET['erro'] == 1): ?>
        <div class="alert alert-danger">
            Preencha todos os campos obrigatórios.
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['erro']) && $_GET['erro'] == 'email'): ?>
        <div class="alert alert-warning">
            Já existe um participante com esse email.
        </div>
    <?php endif; ?>

    <form action="participantes_salvar.php" method="post">

        <div class="mb-3">
            <label class="form-label">Nome completo</label>
            <input type="text" name="nome" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Telefone</label>
            <input type="text" name="telefone" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Turma</label>
            <select name="turma_id" class="form-select" required>
                <option value="">Selecione a turma</option>
                <?php foreach ($turmas as $t): ?>
                    <option value="<?= $t['id'] ?>">
                        <?= htmlspecialchars($t['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="d-flex justify-content-between">
            <a href="participantes_lista.php" class="btn btn-secondary">
                Voltar
            </a>

            <button type="submit" class="btn btn-primary">
                Salvar
            </button>
        </div>

    </form>

</div>

<?php require_once __DIR__ . '/../layout/admin_footer.php'; ?>
