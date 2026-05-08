<?php
require_once __DIR__ . "/../config/conexao.php";
require_once __DIR__ . "/../config/auth.php";
require_once __DIR__ ."/../layout/admin_header.php";

$turma_id = $_GET['turma_id'] ?? null;
$data_inicio = $_GET['data_inicio'] ?? null;
$data_fim = $_GET['data_fim'] ?? null;

if (!$turma_id) die("Turma não informada");

// Buscar turma
$stmt = $pdo->prepare("SELECT * FROM turmas WHERE id = ?");
$stmt->execute([$turma_id]);
$turma = $stmt->fetch();

if (!$turma) die("Turma não encontrada");

// 🔍 Montar filtro de datas
$filtro_data = "";
$params = [$turma_id];

if ($data_inicio && $data_fim) {
    $filtro_data = "AND ts.data BETWEEN ? AND ?";
    $params[] = $data_inicio;
    $params[] = $data_fim;
}

// 📊 Query principal
$stmt = $pdo->prepare("
SELECT 
    p.id,
    p.nome,
    COUNT(pr.id) as total,
    SUM(pr.status = 'presente') as presencas,
    SUM(pr.status = 'falta') as faltas,
    SUM(pr.status = 'justificado') as justificadas
FROM participantes p
LEFT JOIN presencas pr ON pr.participante_id = p.id
LEFT JOIN turmas_sessoes ts ON ts.id = pr.sessao_id
WHERE p.turma_id = ?
$filtro_data
GROUP BY p.id
ORDER BY p.nome
");

$stmt->execute($params);
$dados = $stmt->fetchAll();

// 📊 Totais gerais
$total_aulas = 0;
$total_presencas = 0;
$total_faltas = 0;

foreach ($dados as $d) {
    $total_aulas += $d['total'];
    $total_presencas += $d['presencas'];
    $total_faltas += $d['faltas'];
}
?>

<div class="container mt-4">

<h2>📊 Relatório de Frequência</h2>
<p><strong>Turma:</strong> <?= $turma['nome'] ?></p>

<!-- 🔍 FILTRO -->
<form method="GET" class="row g-3 mb-3">
    <input type="hidden" name="turma_id" value="<?= $turma_id ?>">

    <div class="col-md-3">
        <label>Data Início</label>
        <input type="date" name="data_inicio" value="<?= $data_inicio ?>" class="form-control">
    </div>

    <div class="col-md-3">
        <label>Data Fim</label>
        <input type="date" name="data_fim" value="<?= $data_fim ?>" class="form-control">
    </div>

    <div class="col-md-3 align-self-end">
        <button class="btn btn-primary">Filtrar</button>
    </div>
</form>

<!-- 📊 RESUMO -->
<div class="row mb-3">
    <div class="col-md-4">
        <div class="alert alert-info">
            Total de Registros: <strong><?= $total_aulas ?></strong>
        </div>
    </div>

    <div class="col-md-4">
        <div class="alert alert-success">
            Presenças: <strong><?= $total_presencas ?></strong>
        </div>
    </div>

    <div class="col-md-4">
        <div class="alert alert-danger">
            Faltas: <strong><?= $total_faltas ?></strong>
        </div>
    </div>
</div>

<!-- 📋 TABELA -->
<table class="table table-bordered table-striped">
<thead>
<tr>
    <th>Nome</th>
    <th>Total</th>
    <th>Presenças</th>
    <th>Faltas</th>
    <th>Justificadas</th>
    <th>% Frequência</th>
</tr>
</thead>
<tbody>

<?php foreach ($dados as $d): 
$percentual = $d['total'] > 0 ? round(($d['presencas'] / $d['total']) * 100, 1) : 0;
?>
<tr>
    <td><?= $d['nome'] ?></td>
    <td><?= $d['total'] ?></td>
    <td><?= $d['presencas'] ?></td>
    <td><?= $d['faltas'] ?></td>
    <td><?= $d['justificadas'] ?></td>
    <td>
        <span class="badge <?= $percentual >= 75 ? 'bg-success' : 'bg-danger' ?>">
            <?= $percentual ?>%
        </span>
    </td>
</tr>
<?php endforeach; ?>

</tbody>
</table>

</div>

<?php require_once __DIR__ ."/../layout/admin_footer.php";?>