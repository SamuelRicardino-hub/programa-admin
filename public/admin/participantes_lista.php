<?php
require_once __DIR__ . "/../../config/protect.php";
require_once __DIR__ .'../config/conexao.php';

$titulo = "Participantes";
require_once __DIR__ . '/../layout/header.php';

$sql = $pdo->query("
    SELECT p.id, p.nome, p.email, t.nome AS turma
    FROM participantes p
    JOIN turmas t ON t.id = p.turma_id
    ORDER BY p.nome
");

$participantes = $sql->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Participantes</h3>
    <a href="participantes_form.php" class="btn btn-success">
        + Cadastrar Participante
    </a>
</div>

<div class="card shadow">
    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Nome</th>
                        <th>email</th>
                        <th>Turma</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($participantes as $p): ?>
                        <tr>
                            <td><?= htmlspecialchars($p['nome']) ?></td>
                            <td><?= htmlspecialchars($p['email']) ?></td>
                            <td><?= htmlspecialchars($p['turma']) ?></td>
                            <td class="text-center">

                                <a href="participantes_editar.php?id=<?= $p['id'] ?>"
                                   class="btn btn-sm btn-warning">
                                    Editar
                                </a>

                                <a href="participantes_excluir.php?id=<?= $p['id'] ?>"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Deseja excluir este participante?')">
                                    Excluir
                                </a>

                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if (count($participantes) === 0): ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                Nenhum participante cadastrado
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
