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
        SELECT id, nome, turma, numero_processo, 'Participante' AS tipo
        FROM participantes
        WHERE nome LIKE :busca 
           OR turma LIKE :busca 
           OR numero_processo LIKE :busca
    ");

    $sql1->execute([':busca' => "%$busca%"]);
    $participantes = $sql1->fetchAll(PDO::FETCH_ASSOC);

    // Junta tudo
    $resultados = array_merge($participantes);
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
            <th>Turma</th>
            <th>N° do Processo</th>
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
                            botao = `<a href="participantes_detalhes.php?id=${item.id}" class="btn btn-sm btn-primary">Ver</a>`;
                        } else {
                            botao = `<a href="pre_cadastros.php" class="btn btn-sm btn-warning">Ver</a>`;
                        }

                        html += `
                            <tr>
                                <td>${item.tipo}</td>
                                <td>${item.nome}</td>
                                <td>${item.turma}</td>
                                <td>${item.numero_proceso ?? ''}</td>
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