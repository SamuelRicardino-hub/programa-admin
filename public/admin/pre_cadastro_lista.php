<?php
require_once __DIR__ . "/../../config/conexao.php";
require_once __DIR__ . "/../../config/protect.php";
require_once __DIR__ . "/../../layout/header.php";

$result = $conn->query("SELECT * FROM pre_cadastros ORDER BY criado_em DESC");
?>

<h2>Pré-Cadastros</h2>

<table border="1">
    <tr>
        <th>Nome</th>
        <th>CPF</th>
        <th>Status</th>
        <th>Ações</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['nome']) ?></td>
            <td><?= htmlspecialchars($row['cpf']) ?></td>
            <td><?= $row['status'] ?></td>
            <td>
                <?php if ($row['status'] === 'pendente'): ?>
                    <a href="pre_cadastro_aprovar.php?id=<?= $row['id'] ?>">Aprovar</a>
                    <a href="pre_cadastro_rejeitar.php?id=<?= $row['id'] ?>">Rejeitar</a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<?php require_once __DIR__ . "/../../layout/footer.php"; ?>