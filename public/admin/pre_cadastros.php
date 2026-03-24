<?php
require_once __DIR__ . "/../../config/conexao.php";
require_once __DIR__ . "/../../layout/admin_header.php";

$statusFiltro = $_GET['status'] ?? 'pendente';

$stmt = $pdo->prepare("SELECT * FROM pre_cadastros WHERE status = ? ORDER BY criado_em DESC");
$stmt->execute([$statusFiltro]);
$preCadastros = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<h2 class="mb-4">Pré-Cadastros Pendentes</h2>

<div class="card shadow-sm">
    <div class="card-body">

        <?php if (count($preCadastros) === 0): ?>
            <p class="text-muted">Nenhum pré-cadastro pendente.</p>
        <?php else: ?>
            <div class="mb-3">
                <a href="?status=pendente" class="btn btn-outline-warning btn-sm">Pendentes</a>
                <a href="?status=aprovado" class="btn btn-outline-success btn-sm">Aprovados</a>
            </div>
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Data</th>
                        <th width="200">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($preCadastros as $pre): ?>
                        <tr>
                            <td><?= htmlspecialchars($pre['nome']) ?></td>
                            <td><?= htmlspecialchars($pre['cpf']) ?></td>
                            <td><?= htmlspecialchars($pre['email']) ?></td>

                            <td>
                                <?php if ($pre['status'] === 'pendente'): ?>
                                    <span class="badge bg-warning text-dark">Pendente</span>
                                <?php elseif ($pre['status'] === 'aprovado'): ?>
                                    <span class="badge bg-success">Aprovado</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Recusado</span>
                                <?php endif; ?>
                            </td>

                            <td><?= date("d/m/Y H:i", strtotime($pre['criado_em'])) ?></td>

                            <td>
                                <?php if ($pre['status'] === 'pendente'): ?>
                                    <a href="pre_cadastro_aprovar.php?id=<?= $pre['id'] ?>"
                                        class="btn btn-sm btn-success" 
                                        onclick="return confirm('Deseja realmente aprovar este pré-cadastro?')">
                                        Aprovar
                                    </a>

                                    <a href="pre_cadastro_processar.php?id=<?= $pre['id'] ?>&acao=rejeitar"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Tem certeza que deseja REJEITAR este pré-cadastro?')">
                                        Rejeitar
                                    </a>

                                <?php else: ?>
                                    <span class="text-muted">Sem ações</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <?php endif; ?>

    </div>
</div>

<?php require_once __DIR__ . "/../../layout/admin_footer.php"; ?>