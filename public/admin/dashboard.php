<?php
require_once __DIR__ . "/../../config/conexao.php";
require_once __DIR__ . "/../../layout/admin_header.php";


$recentes = $pdo->query("
SELECT nome, criado_em
FROM pre_cadastros
ORDER BY criado_em DESC
LIMIT 5
");
$turmas = $pdo->query("
SELECT 
t.nome,
COUNT(p.id) as total
FROM turmas t
LEFT JOIN participantes p
ON p.turma_id = t.id
GROUP BY t.id
");

$stmt = $pdo->query("SELECT COUNT(*) FROM usuarios");
$totalUsuarios = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM participantes");
$totalParticipantes = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM turmas");
$totalTurmas = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM pre_cadastros WHERE status = 'pendente'");
$totalPre = $stmt->fetchColumn();
?>

<h2 class="mb-4">Dashboard</h2>

<p>
    Bem vindo, <strong><?= $_SESSION['usuarios'] ?? '' ?></strong>
</p>

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

    </div>

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
                        <td><?= $r['nome'] ?></td>
                        <td><?= date('d/m/Y', strtotime($r['criado_em'])) ?></td>
                    </tr>

                <?php endforeach; ?>

            </table>

        </div>
    </div>

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
                        <td><?= $t['nome'] ?></td>
                        <td><?= $t['total'] ?></td>
                    </tr>

                <?php endforeach; ?>

            </table>

        </div>
    </div>

</div>

<?php require_once __DIR__ . "/../../layout/admin_footer.php"; ?>