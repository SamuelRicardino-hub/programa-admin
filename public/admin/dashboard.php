<?php require_once __DIR__ . "/../../layout/admin_header.php"; ?>
<?php require_once __DIR__ . "/../../config/conexao.php"; ?>

<h1>Dashboard</h1>

<?php
$totalParticipantes = $conn->query("SELECT COUNT(*) as total FROM participantes")->fetch_assoc()['total'];
$totalPre = $conn->query("SELECT COUNT(*) as total FROM pre_cadastros WHERE status='pendente'")->fetch_assoc()['total'];
?>

<div class="card">
    <p><strong>Participantes:</strong> <?= $totalParticipantes ?></p>
    <p><strong>Pré-Cadastros Pendentes:</strong> <?= $totalPre ?></p>
</div>

<?php require_once __DIR__ . "/../../layout/admin_footer.php"; ?>