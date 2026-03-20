<?php
require_once __DIR__ . "/../../config/conexao.php";
require_once __DIR__ . "/../../config/auth.php";
require_once __DIR__ . "/../../layout/admin_header.php";

auth();
canAny(['admin','atendente']);

// 📊 MÉTRICAS
$totalUsuarios = $pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
$totalParticipantes = $pdo->query("SELECT COUNT(*) FROM participantes")->fetchColumn();
$totalTurmas = $pdo->query("SELECT COUNT(*) FROM turmas")->fetchColumn();
$totalPre = $pdo->query("SELECT COUNT(*) FROM pre_cadastros WHERE status = 'pendente'")->fetchColumn();

// 📋 ÚLTIMOS PRÉ-CADASTROS
$recentes = $pdo->query("
    SELECT nome, criado_em
    FROM pre_cadastros
    ORDER BY criado_em DESC
    LIMIT 5
");

// 📊 PARTICIPANTES POR TURMA
$turmas = $pdo->query("
    SELECT 
        t.nome,
        COUNT(p.id) as total
    FROM turmas t
    LEFT JOIN participantes p ON p.turma_id = t.id
    GROUP BY t.id
    ORDER BY total DESC
");
?>

<h2 class="mb-4">Dashboard</h2>

<p>
    Bem-vindo, <strong><?= htmlspecialchars($_SESSION['usuario']['nome']) ?></strong>
</p>

<p class="text-muted">
    Nível: <?= ucfirst($_SESSION['usuario']['nivel']) ?>
</p>

<!-- 📊 CARDS -->
<div class="row g-4">

    <div class="col-md-3">
        <div class="card shadow-sm border-0 text-center">
            <div class="card-body">
                <h6 class="text-muted">Usuários</h6>
                <h3 class="fw-bold"><?= $totalUsuarios ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0 text-center">
            <div class="card-body">
                <h6 class="text-muted">Participantes</h6>
                <h3 class="fw-bold"><?= $totalParticipantes ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0 text-center">
            <div class="card-body">
                <h6 class="text-muted">Turmas</h6>
                <h3 class="fw-bold"><?= $totalTurmas ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0 text-center bg-warning bg-opacity-10">
            <div class="card-body">
                <h6 class="text-muted">Pré-Cadastros Pendentes</h6>
                <h3 class="fw-bold text-warning"><?= $totalPre ?></h3>
            </div>
        </div>
    </div>

</div>

<!-- 🔘 AÇÕES RÁPIDAS -->
<div class="row mt-4">

    <div class="col-md-3">
        <a href="turmas_novo.php" class="btn btn-primary w-100">
            📚 Nova Turma
        </a>
    </div>

    <div class="col-md-3">
        <a href="pre_cadastros.php" class="btn btn-warning w-100">
            📋 Ver Pré-Cadastros
        </a>
    </div>

    <div class="col-md-3">
        <a href="participantes_lista.php" class="btn btn-dark w-100">
            👥 Ver Participantes
        </a>
    </div>

    <div class="col-md-3">
        <a href="turmas_lista.php" class="btn btn-dark w-100">
            📚 Ver Turmas
        </a>
    </div>

</div>

<!-- 📋 ÚLTIMOS PRÉ-CADASTROS -->
<div class="card mt-4">
    <div class="card-body">

        <h5>Últimos Pré-Cadastros</h5>

        <table class="table">
            <tr>
                <th>Nome</th>
                <th>Data</th>
            </tr>

            <?php foreach ($recentes as $r): ?>
                <tr>
                    <td><?= htmlspecialchars($r['nome']) ?></td>
                    <td><?= date('d/m/Y', strtotime($r['criado_em'])) ?></td>
                </tr>
            <?php endforeach; ?>

        </table>

    </div>
</div>

<!-- 📊 PARTICIPANTES POR TURMA -->
<div class="card mt-4">
    <div class="card-body">

        <h5>Participantes por Turma</h5>

        <table class="table">
            <tr>
                <th>Turma</th>
                <th>Participantes</th>
            </tr>

            <?php foreach ($turmas as $t): ?>
                <tr>
                    <td><?= htmlspecialchars($t['nome']) ?></td>
                    <td><?= $t['total'] ?></td>
                </tr>
            <?php endforeach; ?>

        </table>

    </div>
</div>

<?php require_once __DIR__ . "/../../layout/admin_footer.php"; ?>