<?php
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../config/auth.php';

auth();

$participante_id = $_GET['participante_id'] ?? null;
if (!$participante_id) die("Participante não informado");

// Participante
$stmt = $pdo->prepare("SELECT nome, numero_processo FROM participantes WHERE id = ?");
$stmt->execute([$participante_id]);
$p = $stmt->fetch();

if (!$p) die("Participante não encontrado");

// Ficha
$stmt = $pdo->prepare("SELECT * FROM ficha_inclusao WHERE participante_id = ?");
$stmt->execute([$participante_id]);
$f = $stmt->fetch();

if (!$f) die("Ficha não encontrada");

function campo($f, $c) {
    return !empty($f[$c]) ? htmlspecialchars($f[$c]) : '-';
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Ficha de Inclusão</title>

<style>
body { font-family: Arial; margin: 40px; }
h1, h2 { text-align: center; }
.section { margin-top: 25px; }
.label { font-weight: bold; }
hr { margin: 20px 0; }

/* impressão */
@media print {
    button { display: none; }
}
</style>
</head>

<body>

<button onclick="window.print()">🖨 Imprimir / Salvar PDF</button>

<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px;">

    <!-- Logo esquerda (Prefeitura) -->
    <div>
        <img src="../../assets/logo_prefeitura.png" 
             style="height:70px;">
    </div>

    <!-- Texto central -->
    <div style="text-align:center; flex:1;">

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

    <!-- Logo direita (Projeto SER) -->
    <div>
        <img src="../../assets/logo_ser.png" 
             style="height:70px;">
    </div>

</div>

<hr>

<p><strong>Participante:</strong> <?= htmlspecialchars($p['nome']) ?></p>
<p><strong>Nº Processo:</strong> <?= htmlspecialchars($p['numero_processo']) ?></p>

<div class="section">
<h4>Dados Gerais</h4>

<p><span class="label">Cor:</span> <?= campo($f,'cor') ?></p>
<p><span class="label">Religião:</span> <?= campo($f,'religiao') ?> <?= campo($f,'religiao_outro') ?></p>
<p><span class="label">Escolaridade:</span> <?= campo($f,'escolaridade') ?> <?= campo($f,'escolaridade_outro') ?></p>
<p><span class="label">Renda:</span> <?= campo($f,'renda_familiar') ?> <?= campo($f,'renda_outro') ?></p>
<p><span class="label">Trabalho:</span> <?= campo($f,'trabalho') ?> <?= campo($f,'trabalho_outro') ?></p>
<p><span class="label">Profissão:</span> <?= campo($f,'profissao') ?></p>
<p><span class="label">Moradia:</span> <?= campo($f,'condicao_moradia') ?> <?= campo($f,'moradia_outro') ?></p>
<p><span class="label">Relacionamento:</span> <?= campo($f,'relacionamento_atual') ?> <?= campo($f,'relacionamento_outro') ?></p>

</div>

<div class="section">
<h4>Saúde</h4>

<p><span class="label">Problemas:</span> <?= campo($f,'problemas_saude') ?></p>
<p><span class="label">Medicação:</span> <?= campo($f,'uso_medicacao') ?></p>
<p><span class="label">Álcool:</span> <?= campo($f,'uso_alcool') ?></p>
<p><span class="label">Frequência:</span> <?= campo($f,'frequencia_bebida') ?></p>
<p><span class="label">Drogas:</span> <?= campo($f,'drogas_utilizadas') ?></p>

</div>

<div class="section">
<h4>Histórico</h4>

<p><span class="label">Violência praticada:</span> <?= campo($f,'violencia_praticada') ?></p>
<p><span class="label">Violência sofrida:</span> <?= campo($f,'violencia_sofrida') ?></p>
<p><span class="label">Histórico familiar:</span> <?= campo($f,'historico_familiar') ?></p>
<p><span class="label">Situação jurídica:</span> <?= campo($f,'situacao_juridica') ?></p>

</div>

<div class="section">
<h4>Expectativa</h4>
<p><?= campo($f,'expectativa_grupo') ?></p>
</div>

</body>
</html>