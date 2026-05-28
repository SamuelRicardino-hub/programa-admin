<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../layout/admin_header.php';

auth();
canAny(['admin', 'atendente']);

// Buscar turmas (Priorizando as ativas no topo, mas listando todas)
$turmas = $pdo->query("SELECT id, nome, status FROM turmas ORDER BY status ASC, nome ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0 text-dark">
                <i class="bi bi-person-plus-fill me-2" style="color: var(--ser-orange);"></i>Novo Participante
            </h3>
            <p class="text-muted small mb-0">Preencha os dados abaixo para vincular um novo integrante ao projeto.</p>
        </div>
        <a href="participantes_lista.php" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Voltar à Lista
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="participantes_salvar.php" method="POST">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary">Nome Completo</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="bi bi-person text-primary"></i></span>
                                <input type="text" name="nome" class="form-control border-start-0" placeholder="Ex: João Silva Sauro" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-secondary">Número do Processo</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-hash text-primary"></i></span>
                                    <input type="text" name="numero_processo" class="form-control border-start-0" placeholder="0000000-00.202X..." required>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-secondary">Vincular à Turma</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-mortarboard text-primary"></i></span>
                                    <select name="turma_id" class="form-select border-start-0" required>
                                        <option value="">Selecione...</option>
                                        <?php foreach ($turmas as $t): ?>
                                            <option value="<?= $t['id'] ?>">
                                                <?= htmlspecialchars($t['nome']) ?> <?= (strtolower($t['status']) !== 'ativa' && strtolower($t['status']) !== 'ativo') ? '(Inativa)' : '' ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary">Observações / Histórico</label>
                            <textarea name="observacoes" class="form-control" rows="4" placeholder="Informações relevantes sobre o participante..."></textarea>
                        </div>

                        <div class="d-flex justify-content-between align-items-center bg-light p-3 rounded border">
                            <a href="participantes_lista.php" class="text-decoration-none text-muted fw-bold small">
                                <i class="bi bi-x-circle me-1"></i> Descartar Alterações
                            </a>

                            <button type="submit" class="btn px-5 shadow-sm" style="background-color: var(--ser-blue); color: white;">
                                <i class="bi bi-check2-circle me-2"></i>Salvar Participante
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/admin_footer.php'; ?>