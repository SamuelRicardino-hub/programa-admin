<?php
require_once __DIR__ . "/../config/conexao.php";
require_once __DIR__ . "/../layout/admin_header.php";
require_once __DIR__ . '/../config/auth.php';

auth();
canAny(['admin', 'atendente']);

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: turmas_lista.php");
    exit;
}

$sql = $pdo->prepare("SELECT id, nome, descricao, responsavel, data_inicio, data_fim, status FROM turmas WHERE id = :id");
$sql->bindParam(':id', $id, PDO::PARAM_INT);
$sql->execute();

$turma = $sql->fetch(PDO::FETCH_ASSOC);

if (!$turma) {
    header("Location: turmas_lista.php");
    exit;
}
?>

<div class="container py-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">
                <i class="bi bi-pencil-square me-2 text-primary"></i>Editar Turma
            </h2>
            <p class="text-muted">Atualize as informações da turma no sistema.</p>
        </div>
        <a href="turmas_lista.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    
                    <form action="turmas_atualizar.php" method="post">
                        <input type="hidden" name="id" value="<?= $turma['id'] ?>">

                        <div class="mb-4">
                            <label class="form-label fw-bold">Nome da Turma</label>
                            <input type="text" name="nome" class="form-control form-control-lg"
                                   value="<?= htmlspecialchars($turma['nome']) ?>" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Descrição / Objetivo</label>
                            <textarea name="descricao" class="form-control" rows="3" 
                                      placeholder="Ex: Grupo reflexivo focado em..."><?= htmlspecialchars($turma['descricao']) ?></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Responsável / Facilitador</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-person-badge"></i></span>
                                <input type="text" name="responsavel" class="form-control"
                                       value="<?= htmlspecialchars($turma['responsavel']) ?>" required>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Data de Início</label>
                                <input type="date" name="data_inicio" class="form-control"
                                       value="<?= $turma['data_inicio'] ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Previsão de Término</label>
                                <input type="date" name="data_fim" class="form-control"
                                       value="<?= $turma['data_fim'] ?>" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Status Atual</label>
                            <select name="status" class="form-select">
                                <option value="ativa" <?= $turma['status'] == 'ativa' ? 'selected' : '' ?>>🟢 Ativa</option>
                                <option value="inativa" <?= $turma['status'] == 'inativa' ? 'selected' : '' ?>>🔴 Inativa</option>
                            </select>
                            <div class="form-text">Turmas inativas não permitem novos registros de sessão.</div>
                        </div>

                        <hr class="my-4">

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="turmas_lista.php" class="btn btn-light px-4 border">Cancelar</a>
                            <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">
                                <i class="bi bi-check-circle me-2"></i>Salvar Alterações
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/admin_footer.php'; ?>