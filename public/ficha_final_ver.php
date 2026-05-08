<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/auth.php';

auth();

$participante_id = $_GET['participante_id'] ?? null;
if (!$participante_id) die("Participante não informado");

// Participante
$stmt = $pdo->prepare("SELECT nome FROM participantes WHERE id = ?");
$stmt->execute([$participante_id]);
$p = $stmt->fetch();

if (!$p) die("Participante não encontrado");

// Ficha
$stmt = $pdo->prepare("SELECT * FROM ficha_avaliacao_final WHERE participante_id = ?");
$stmt->execute([$participante_id]);
$f = $stmt->fetch();

if (!$f) die("Ficha final não encontrada");

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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f6fa;
        }

        .card {
            border-radius: 12px;
        }

        .header-doc {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header-center {
            text-align: center;
            flex: 1;
        }

        .logo {
            height: 70px;
        }

        .section-title {
            font-weight: bold;
            border-bottom: 2px solid #ddd;
            margin-top: 20px;
            margin-bottom: 10px;
            padding-bottom: 5px;
        }

        .label {
            font-weight: bold;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="container mt-4">

        <!-- BOTÕES -->
        <div class="mb-3 no-print d-flex gap-2">
            <a href="participantes_detalhes.php?id=<?= $participante_id ?>" class="btn btn-secondary">
                ← Voltar
            </a>

            <a href="ficha_final_editar.php?id=<?= $participante_id ?>" class="btn btn-warning">
                Editar Ficha Final
            </a>

            <a href="ficha_final_pdf.php?participante_id=<?= $participante_id ?>" class="btn btn-danger">
                📄 Gerar PDF
            </a>
        </div>

        <div class="card shadow">
            <div class="card-body">

                <!-- CABEÇALHO -->
                <div class="header-doc mb-3">

                    <img src="../assets/LogoPrefeitura.png" height="100">

                    <div class="header-center">
                        <div><strong>Estado do Rio de Janeiro</strong></div>
                        <div><strong>Prefeitura Municipal de Paracambi</strong></div>
                        <div><strong>Secretaria Municipal de Proteção e Política para a Mulher</strong></div>
                        <div><strong>Projeto S.E.R. – Grupo Reflexivo para Homens</strong></div>
                    </div>

                    <img src="../assets/ProjetoSER.jpg" height="100">

                </div>

                <hr>

                <p><strong>Participante:</strong> <?= htmlspecialchars($p['nome']) ?></p>

                <!-- AVALIAÇÃO -->
                <div class="section-title">Avaliação</div>

                <p><span class="label">Sentimento ao denunciar:</span> <?= campo($f, 'sentimento_denuncia') ?></p>
                <p><span class="label">Denúncia justa:</span> <?= campo($f, 'acha_justa') ?></p>
                <p><span class="label">Motivo:</span> <?= campo($f, 'motivo_denuncia') ?></p>

                <p><span class="label">Dificuldade para participar:</span> <?= campo($f, 'dificuldade_participar') ?></p>
                <p><span class="label">Motivo da dificuldade:</span> <?= campo($f, 'motivo_dificuldade') ?></p>

                <p><span class="label">Avaliação da participação:</span> <?= campo($f, 'avaliacao_participacao') ?></p>

                <!-- RESULTADOS -->
                <div class="section-title">Resultados</div>

                <p><span class="label">Pontos positivos:</span> <?= campo($f, 'pontos_positivos') ?></p>
                <p><span class="label">Pontos negativos:</span> <?= campo($f, 'pontos_negativos') ?></p>
                <p><span class="label">Temas importantes:</span> <?= campo($f, 'temas_importantes') ?></p>

                <!-- CONCLUSÃO -->
                <div class="section-title">Conclusão</div>

                <p><span class="label">Houve mudança:</span> <?= campo($f, 'houve_mudanca') ?></p>
                <p><span class="label">Descrição da mudança:</span> <?= campo($f, 'descricao_mudanca') ?></p>

                <p><span class="label">Gostou do grupo:</span> <?= campo($f, 'gostou_grupo') ?></p>
                <p><span class="label">Como saiu do grupo:</span> <?= campo($f, 'como_saiu') ?></p>

                <p><span class="label">Recomendaria:</span> <?= campo($f, 'recomendaria') ?></p>
                <p><span class="label">Motivo da recomendação:</span> <?= campo($f, 'motivo_recomendacao') ?></p>

                <p><span class="label">Sugestões:</span> <?= campo($f, 'sugestoes') ?></p>

            </div>
        </div>

    </div>

</body>

</html>