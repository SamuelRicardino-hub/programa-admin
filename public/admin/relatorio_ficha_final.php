<?php
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../config/auth.php';

auth();

$participante_id = $_GET['participante_id'] ?? null;

$stmt = $pdo->prepare("SELECT nome, numero_processo FROM participantes WHERE id = ?");
$stmt->execute([$participante_id]);
$p = $stmt->fetch();

$stmt = $pdo->prepare("SELECT * FROM ficha_avaliacao_final WHERE participante_id = ?");
$stmt->execute([$participante_id]);
$f = $stmt->fetch();

function campo($f, $c)
{
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
            font-family: Arial;
            margin: 40px;
        }

        h1,
        h2 {
            text-align: center;
        }

        .section {
            margin-top: 25px;
        }

        .label {
            font-weight: bold;
        }

        @media print {
            button {
                display: none;
            }
        }
    </style>
</head>

<body>

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
            <img src="../../assets/ProjetoSER.jpg"
                style="height:70px;">
        </div>

    </div>

    <hr>

    <p><strong>Participante:</strong> <?= htmlspecialchars($p['nome']) ?></p>
    <p><strong>Nº Processo:</strong> <?= htmlspecialchars($p['numero_processo']) ?></p>

    <div class="section">
        <h4>Avaliação</h4>

        <p><span class="label">Sentimento:</span> <?= campo($f, 'sentimento_denuncia') ?></p>
        <p><span class="label">Denúncia justa:</span> <?= campo($f, 'acha_justa') ?></p>
        <p><span class="label">Motivo:</span> <?= campo($f, 'motivo_denuncia') ?></p>

        <p><span class="label">Dificuldade:</span> <?= campo($f, 'dificuldade_participar') ?></p>
        <p><span class="label">Motivo dificuldade:</span> <?= campo($f, 'motivo_dificuldade') ?></p>

        <p><span class="label">Avaliação participação:</span> <?= campo($f, 'avaliacao_participacao') ?></p>

    </div>

    <div class="section">
        <h4>Resultados</h4>

        <p><span class="label">Pontos positivos:</span> <?= campo($f, 'pontos_positivos') ?></p>
        <p><span class="label">Pontos negativos:</span> <?= campo($f, 'pontos_negativos') ?></p>
        <p><span class="label">Temas importantes:</span> <?= campo($f, 'temas_importantes') ?></p>

    </div>

    <div class="section">
        <h4>Conclusão</h4>

        <p><span class="label">Houve mudança:</span> <?= campo($f, 'houve_mudanca') ?></p>
        <p><span class="label">Descrição:</span> <?= campo($f, 'descricao_mudanca') ?></p>

        <p><span class="label">Gostou do grupo:</span> <?= campo($f, 'gostou_grupo') ?></p>
        <p><span class="label">Recomendaria:</span> <?= campo($f, 'recomendaria') ?></p>
        <p><span class="label">Motivo:</span> <?= campo($f, 'motivo_recomendacao') ?></p>

        <p><span class="label">Sugestões:</span> <?= campo($f, 'sugestoes') ?></p>

    </div>

    <button onclick="window.print()">🖨 Imprimir / Salvar PDF</button>

</body>

</html>