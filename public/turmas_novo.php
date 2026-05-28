<?php
require_once __DIR__ . "/../config/conexao.php";
require_once __DIR__ . "/../config/auth.php";
auth();
canAny(['admin', 'atendente']);

$erro = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $responsavel = trim($_POST['responsavel'] ?? '');
    $data_inicio = $_POST['data_inicio'] ?? null;
    $data_fim = $_POST['data_fim'] ?? null;
    $status = $_POST['status'] ?? 'ativa';
    
    // NOVA VARIÁVEL: Captura o ID do atendente selecionado no <select> do formulário
    // Se nenhum atendente foi selecionado, define como null
    $usuario_id = !empty($_POST['usuario_id']) ? $_POST['usuario_id'] : null;

    if (empty($nome)) {
        $erro = "O nome da turma é obrigatório.";
    } else {
        $check = $pdo->prepare("SELECT id FROM turmas WHERE nome = ?");
        $check->execute([$nome]);

        if ($check->rowCount() > 0) {
            $erro = "Já existe uma turma com esse nome.";
        } else {
            // Incluímos 'usuario_id' tanto nos campos quanto nos VALUES
            $stmt = $pdo->prepare("
                INSERT INTO turmas (nome, descricao, responsavel, usuario_id, data_inicio, data_fim, status, criado_em)
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
            ");
            
            // Adicionado o $usuario_id na ordem correta da execução
            $stmt->execute([$nome, $descricao, $responsavel, $usuario_id, $data_inicio, $data_fim, $status]);
            
            header("Location: turmas_lista.php?msg=criado");
            exit;
        }
    }
}

require_once __DIR__ . "/../layout/admin_header.php";
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0 text-dark">
                <i class="bi bi-mortarboard-fill me-2" style="color: var(--ser-orange);"></i>Nova Turma
            </h3>
            <p class="text-muted small mb-0">Configure um novo grupo de atividades para o projeto.</p>
        </div>
        <a href="turmas_lista.php" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Voltar
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">

                    <?php if ($erro): ?>
                        <div class="alert alert-danger border-0 border-start border-4 border-danger shadow-sm">
                            <i class="bi bi-exclamation-octagon-fill me-2"></i> <?= htmlspecialchars($erro) ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="row">
                            <div class="col-md-7 mb-3">
                                <label class="form-label fw-bold text-secondary">Nome da Turma</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-primary"><i class="bi bi-bookmark-star"></i></span>
                                    <input type="text" name="nome" class="form-control border-start-0" placeholder="Ex: Turma A - 2026" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Atendente Responsável</label>
                                <select name="usuario_id" class="form-select">
                                    <option value="">-- Sem atendente específico --</option>
                                    <?php
                                    // Busca apenas usuários com nível de atendente ou admin
                                    $atendentes = $pdo->query("SELECT id, nome FROM usuarios WHERE nivel = 'atendente' ORDER BY nome ASC")->fetchAll();
                                    foreach ($atendentes as $at) {
                                        $selected = ($at['id'] == ($turma['usuario_id'] ?? '')) ? 'selected' : '';
                                        echo "<option value='{$at['id']}' {$selected}>{$at['nome']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary">Descrição da Turma</label>
                            <textarea name="descricao" class="form-control" rows="3" placeholder="Detalhes sobre o objetivo ou local desta turma..."></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold text-secondary">Data de Início</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-primary"><i class="bi bi-calendar-event"></i></span>
                                    <input type="date" name="data_inicio" class="form-control border-start-0">
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold text-secondary">Data de Término</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-primary"><i class="bi bi-calendar-check"></i></span>
                                    <input type="date" name="data_fim" class="form-control border-start-0">
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold text-secondary">Status Inicial</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-primary"><i class="bi bi-toggle-on"></i></span>
                                    <select name="status" class="form-select border-start-0">
                                        <option value="ativa">Ativa</option>
                                        <option value="inativa">Inativa</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4 p-3 bg-light rounded border">
                            <a href="turmas_lista.php" class="text-secondary text-decoration-none small fw-bold">
                                <i class="bi bi-x-lg me-1"></i> Cancelar
                            </a>
                            <button type="submit" class="btn px-5 shadow-sm" style="background-color: var(--ser-blue); color: white;">
                                <i class="bi bi-save me-2"></i>Cadastrar Turma
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . "/../layout/admin_footer.php"; ?>