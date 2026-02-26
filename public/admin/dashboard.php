<?php
require_once __DIR__ . "/../../config/protect.php";
require_once __DIR__ . "/../../config/conexao.php";
require_once __DIR__ . "/../../layout/admin_header.php";

// Total usuários
$stmt = $pdo->query("SELECT COUNT(*) FROM usuarios");
$totalUsuarios = $stmt->fetchColumn();

// Total participantes
$stmt = $pdo->query("SELECT COUNT(*) FROM participantes");
$totalParticipantes = $stmt->fetchColumn();

// Total turmas
$stmt = $pdo->query("SELECT COUNT(*) FROM turmas");
$totalTurmas = $stmt->fetchColumn();

// Total pré-cadastros pendentes
$stmt = $pdo->query("SELECT COUNT(*) FROM pre_cadastros WHERE status = 'pendente'");
$totalPre = $stmt->fetchColumn();
?>

<h2 class="mb-4">Dashboard</h2>

<div class="row g-4">

    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <h6 class="text-muted">Usuários</h6>
                <h3 class="fw-bold"><?= $totalUsuarios ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <h6 class="text-muted">Participantes</h6>
                <h3 class="fw-bold"><?= $totalParticipantes ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <h6 class="text-muted">Turmas</h6>
                <h3 class="fw-bold"><?= $totalTurmas ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0 bg-warning bg-opacity-10">
            <div class="card-body text-center">
                <h6 class="text-muted">Pré-Cadastros Pendentes</h6>
                <h3 class="fw-bold text-warning"><?= $totalPre ?></h3>
            </div>
        </div>
    </div>

</div>

<?php require_once __DIR__ . "/../../layout/admin_footer.php"; ?>