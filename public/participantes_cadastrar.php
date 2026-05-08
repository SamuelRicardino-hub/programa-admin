<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../layout/admin_header.php';

auth();

// Buscar turmas ativas
$stmt = $pdo->query("SELECT id, nome FROM turmas WHERE status = 'ativa' ORDER BY nome");
$turmas = $pdo->query("SELECT id, nome FROM turmas ORDER BY nome ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<h2 class="mb-4">Nov Participante</h2>

<form action="participantes_salvar.php" method="POST">
    <div class="mb-3">
        <label>Nome Completo</label>
        <input type="text" name="nome" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Número do Processo</label>
        <input type="text" name="numero_processo" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Vincular à Turma</label>
        <select name="turma_id" class="form-select" required>
            <option value="">Selecione uma turma...</option>
            <?php foreach ($turmas as $t): ?>
                <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['nome']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label>Observações</label>
        <textarea name="observacoes" class="form-control"></textarea>
    </div>

    <div class="d-flex justify-content-between">
        <a href="participantes_lista.php" class="btn btn-secondary">
            Cancelar
        </a>

        <button type="submit" class="btn btn-primary">Salvar Participante</button>
    </div>


</form>

</div>

<?php require_once __DIR__ . '/../layout/admin_footer.php'; ?>