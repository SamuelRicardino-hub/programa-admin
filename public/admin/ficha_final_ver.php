<?php
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../config/auth.php';

auth();

$participante_id = $_GET['participante_id'] ?? null;

if (!$participante_id) {
    die("Participante não informado");
}

// Buscar participante + número do processo da ficha inclusão
$stmt = $pdo->prepare("
    SELECT p.nome, fi.numero_processo
    FROM participantes p
    LEFT JOIN ficha_inclusao fi ON fi.participante_id = p.id
    WHERE p.id = ?
");
$stmt->execute([$participante_id]);
$p = $stmt->fetch();

if (!$p) {
    die("Participante não encontrado");
}

// Buscar ficha final
$stmt = $pdo->prepare("SELECT * FROM ficha_avaliacao_final WHERE participante_id = ?");
$stmt->execute([$participante_id]);
$f = $stmt->fetch();

if (!$f) {
    die("Ficha final não encontrada");
}

// Função segura
function campo($f, $c) {
    return !empty($f[$c]) ? htmlspecialchars($f[$c]) : '-';
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Ficha Final</title>

<style>
body {
    font-family: Arial, sans-serif;
    margin: 40px;
}

.section {
    margin-top: 25px;
    border: 1px solid #ccc;
    padding: 15px;
    border-radius: 6px;
}

.label {
    font-weight: bold;
}

/* BOTÕES */
.top-bar {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
}

.btn {
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
    text-decoration: none;
}

.btn-print {
    background: #0d6efd;
    color: #fff;
}

.btn-print:hover {
    background: #0b5ed7;
}

.btn-back {
    background: #6c757d;
    color: #fff;
}

.btn-back:hover {
    background: #5c636a;
}

/* impressão */
@media print {
    .top-bar {
        display: none;
    }
}
</style>
</head>

<body>

<!-- BOTÕES -->
<div class="top-bar">

    <a href="participantes_detalhes.php?id=<?= $participante_id ?>" class="btn btn-back">
        ← Voltar
    </a>

    <button onclick="window.print()" class="btn btn-print">
        🖨 Imprimir / Salvar PDF
    </button>

</div>

<!-- CABEÇALHO -->
<div style="display:flex; align-items:center; margin-bottom:20px;">

    <div style="width:120px;">
        <img src="../../assets/LogoPrefeitura.png" style="height:70px;">
    </div>

    <div style="flex:1; text-align:center;">
        <div style="font-size:16px; font-weight:bold;">
            Estado do Rio de Janeiro
        </div>
        <div style="font-size:16px; font-weight:bold;">
            Prefeitura Municipal de Paracambi
        </div>
        <div style="font-size:15px;">
            Secretaria Municipal de Proteção e Política para a Mulher
        </div>
        <div style="font-size:15px; font-weight:bold;">
            Projeto S.E.R. – Grupo Reflexivo para Homens
        </div>
    </div>

    <div style="width:120px; text-align:right;">
        <img src="../../assets/ProjetoSER.jpg" style="height:70px;">
    </div>

</div>

<hr>

<!-- DADOS -->
<p><strong>Participante:</strong> <?= htmlspecialchars($p['nome']) ?></p>
<p><strong>Nº Processo:</strong> <?= htmlspecialchars($p['numero_processo'] ?? '-') ?></p>

<!-- AVALIAÇÃO -->
<div class="section">
<h4>Avaliação</h4>

<p><span class="label">Sentimento:</span> <?= campo($f,'sentimento_denuncia') ?></p>
<p><span class="label">Denúncia justa:</span> <?= campo($f,'acha_justa') ?></p>
<p><span class="label">Motivo:</span> <?= campo($f,'motivo_denuncia') ?></p>

<p><span class="label">Dificuldade:</span> <?= campo($f,'dificuldade_participar') ?></p>
<p><span class="label">Motivo dificuldade:</span> <?= campo($f,'motivo_dificuldade') ?></p>

<p><span class="label">Avaliação participação:</span> <?= campo($f,'avaliacao_participacao') ?></p>
</div>

<!-- RESULTADOS -->
<div class="section">
<h4>Resultados</h4>

<p><span class="label">Pontos positivos:</span> <?= campo($f,'pontos_positivos') ?></p>
<p><span class="label">Pontos negativos:</span> <?= campo($f,'pontos_negativos') ?></p>
<p><span class="label">Temas importantes:</span> <?= campo($f,'temas_importantes') ?></p>
</div>

<!-- CONCLUSÃO -->
<div class="section">
<h4>Conclusão</h4>

<p><span class="label">Houve mudança:</span> <?= campo($f,'houve_mudanca') ?></p>
<p><span class="label">Descrição:</span> <?= campo($f,'descricao_mudanca') ?></p>

<p><span class="label">Gostou do grupo:</span> <?= campo($f,'gostou_grupo') ?></p>
<p><span class="label">Recomendaria:</span> <?= campo($f,'recomendaria') ?></p>
<p><span class="label">Motivo:</span> <?= campo($f,'motivo_recomendacao') ?></p>

<p><span class="label">Sugestões:</span> <?= campo($f,'sugestoes') ?></p>
</div>

</body>
</html>