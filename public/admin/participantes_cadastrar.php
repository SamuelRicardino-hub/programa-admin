<?php
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../layout/admin_header.php';

auth();

// Buscar turmas ativas
$stmt = $pdo->query("SELECT id, nome FROM turmas WHERE status = 'ativa' ORDER BY nome");
$turmas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">

    <h3>➕ Cadastrar Participante</h3>

    <form method="POST" action="participantes_salvar.php">

        <div class="row">

            <!-- NOME -->
            <div class="col-md-6 mb-3">
                <label class="form-label">Nome Completo *</label>
                <input type="text" name="nome" class="form-control" required>
            </div>

            <!-- PROCESSO -->
            <div class="col-md-6 mb-3">
                <label class="form-label">Número do Processo *</label>
                <input type="text" name="numero_processo" class="form-control" required>
            </div>

            <!-- TURMA -->
            <div class="col-md-4 mb-3">
                <label class="form-label">Turma *</label>
                <select name="turma_id" class="form-select" required>
                    <option value="">Selecione...</option>
                    <?php foreach ($turmas as $t): ?>
                        <option value="<?= $t['id'] ?>">
                            <?= htmlspecialchars($t['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- PASSAGENS -->
            <div class="col-md-4 mb-3">
                <label class="form-label">Total de Passagens</label>
                <input type="number" name="total_passagens" class="form-control" value="0" min="0">
            </div>

            <!-- OBSERVAÇÕES -->
            <div class="col-md-12 mb-3">
                <label class="form-label">Observações</label>
                <textarea name="observacoes" class="form-control" rows="3"></textarea>
            </div>

        </div>

        <button type="submit" class="btn btn-success w-100">
            Salvar Participante
        </button>

    </form>

</div>

<?php require_once __DIR__ . '/../../layout/admin_footer.php'; ?>