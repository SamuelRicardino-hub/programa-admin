<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../layout/admin_header.php';

auth();
canAny(['admin', 'atendente']);

$busca = trim($_GET['q'] ?? '');

$resultados = [];

if ($busca !== '') {

    // 🔍 PARTICIPANTES
    $sql1 = $pdo->prepare("
        SELECT id, nome, cpf, email, 'Participante' AS tipo
        FROM participantes
        WHERE nome LIKE :busca 
           OR cpf LIKE :busca 
           OR email LIKE :busca
    ");

    $sql1->execute([':busca' => "%$busca%"]);
    $participantes = $sql1->fetchAll(PDO::FETCH_ASSOC);

    // 🔍 PRÉ-CADASTROS
    $sql2 = $pdo->prepare("
        SELECT id, nome, cpf, email, 'Pré-cadastro' AS tipo
        FROM pre_cadastros
        WHERE status = 'pendente'
        AND (
        nome LIKE :busca 
            OR cpf LIKE :busca 
            OR email LIKE :busca
)
    ");

    $sql2->execute([':busca' => "%$busca%"]);
    $precadastros = $sql2->fetchAll(PDO::FETCH_ASSOC);

    // Junta tudo
    $resultados = array_merge($participantes, $precadastros);
}
?>

<h2 class="mb-4">Busca Global</h2>

<input type="text" 
       id="busca"
       class="form-control mb-3"
       placeholder="Digite nome, CPF ou email...">

       <table class="table table-striped">
    <thead>
        <tr>
            <th>Tipo</th>
            <th>Nome</th>
            <th>CPF</th>
            <th>Email</th>
            <th>Ação</th>
        </tr>
    </thead>
    <tbody id="resultadoBusca">
        <tr>
            <td colspan="5" class="text-muted">
                Digite para buscar...
            </td>
        </tr>
    </tbody>
</table>

<div class="card">
    <div class="card-body">

        <?php if ($busca === ''): ?>
            <p class="text-muted">Digite algo para buscar...</p>

        <?php elseif (empty($resultados)): ?>
            <p class="text-danger">Nenhum resultado encontrado.</p>

        <?php else: ?>

            <table class="table table-striped">
                <tr>
                    <th>Tipo</th>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Email</th>
                    <th>Ação</th>
                </tr>

                <?php foreach ($resultados as $r): ?>
                    <tr>
                        <td><?= $r['tipo'] ?></td>
                        <td><?= htmlspecialchars($r['nome']) ?></td>
                        <td><?= htmlspecialchars($r['cpf']) ?></td>
                        <td><?= htmlspecialchars($r['email']) ?></td>

                        <td>
                            <?php if ($r['tipo'] === 'Participante'): ?>
                                <a href="participantes_editar.php?id=<?= $r['id'] ?>"
                                    class="btn btn-sm btn-primary">
                                    Abrir
                                </a>
                            <?php else: ?>
                                <a href="pre_cadastros.php"
                                    class="btn btn-sm btn-warning">
                                    Ver
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

            </table>

        <?php endif; ?>

    </div>
</div>

<script>
let timeout = null;

document.getElementById('busca').addEventListener('keyup', function() {

    clearTimeout(timeout);

    const valor = this.value;

    timeout = setTimeout(() => {

        if (valor.length < 2) {
            document.getElementById('resultadoBusca').innerHTML =
                '<tr><td colspan="5">Digite pelo menos 2 caracteres</td></tr>';
            return;
        }

        fetch('buscar_ajax.php?q=' + encodeURIComponent(valor))
            .then(res => res.json())
            .then(dados => {

                let html = '';

                if (dados.length === 0) {
                    html = '<tr><td colspan="5">Nenhum resultado</td></tr>';
                } else {

                    dados.forEach(item => {

                        let botao = '';

                        if (item.tipo === 'Participante') {
                            botao = `<a href="participantes_editar.php?id=${item.id}" class="btn btn-sm btn-primary">Abrir</a>`;
                        } else {
                            botao = `<a href="pre_cadastros.php" class="btn btn-sm btn-warning">Ver</a>`;
                        }

                        html += `
                            <tr>
                                <td>${item.tipo}</td>
                                <td>${item.nome}</td>
                                <td>${item.cpf ?? ''}</td>
                                <td>${item.email ?? ''}</td>
                                <td>${botao}</td>
                            </tr>
                        `;
                    });
                }

                document.getElementById('resultadoBusca').innerHTML = html;
            });

    }, 300); // ⏱ delay (evita spam no banco)
});
</script>

<?php require_once __DIR__ . '/../layout/admin_footer.php'; ?>