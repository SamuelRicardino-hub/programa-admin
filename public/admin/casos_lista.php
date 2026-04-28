<?php
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../layout/admin_header.php';

auth();
canAny(['admin','atendente']);

$casos = $sql->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">

<h3>Casos</h3>

<a href="caso_novo.php" class="btn btn-primary mb-3">
    Novo Caso
</a>

<table class="table table-bordered">

<tr>
    <th>ID</th>
    <th>Vítima</th>
    <th>Autor</th>
    <th>Ações</th>
</tr>

<?php foreach ($casos as $c): ?>
<tr>
    <td><?= $c['id'] ?></td>
    <td><?= htmlspecialchars($c['vitima_nome'] ?? '-') ?></td>
    <td><?= htmlspecialchars($c['autor_nome'] ?? '-') ?></td>

    <td>
        <a href="caso_detalhes.php?id=<?= $c['id'] ?>" class="btn btn-info btn-sm">
            Ver
        </a>

        <a href="relatorio_caso.php?caso_id=<?= $c['id'] ?>" 
           target="_blank"
           class="btn btn-danger btn-sm">
            PDF
        </a>
    </td>
</tr>
<?php endforeach; ?>

</table>

</div>

<?php require_once __DIR__ . '/../../layout/admin_footer.php'; ?>