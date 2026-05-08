<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/auth.php';

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

function campo($f, $c)
{
    return !empty($f[$c]) ? htmlspecialchars($f[$c]) : '-';
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Ficha de Inclusão</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
        }

        .header-doc {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header-text {
            text-align: center;
            flex: 1;
        }

        .card {
            border-radius: 12px;
        }

        .section-title {
            font-weight: bold;
            border-bottom: 2px solid #ddd;
            margin-bottom: 10px;
            padding-bottom: 5px;
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

            <a href="ficha_inclusao_editar.php?participante_id=<?= $participante_id ?>" class="btn btn-warning">
                Editar Ficha de Inclusão
            </a>

            <a href="ficha_inclusao_pdf.php?participante_id=<?= $participante_id ?>" class="btn btn-danger">
                📄 Gerar PDF
            </a>

        </div>

        <!-- CARD -->
        <div class="card shadow">
            <div class="card-body">

                <!-- CABEÇALHO -->
                <div class="header-doc mb-3">

                    <img src="../assets/LogoPrefeitura.png" height="100">

                    <div class="header-text">
                        <div><strong>Estado do Rio de Janeiro</strong></div>
                        <div><strong>Prefeitura Municipal de Paracambi</strong></div>
                        <div><strong>Secretaria Municipal de Proteção e Política para a Mulher</strong></div>
                        <div><strong>Projeto S.E.R. – Grupo Reflexivo para Homens</strong></div>
                    </div>

                    <img src="../assets/ProjetoSER.jpg" height="100">

                </div>

                <hr>

                <!-- PARTICIPANTE -->
                <p><strong>Participante:</strong> <?= htmlspecialchars($p['nome']) ?></p>
                <p><strong>Nº Processo:</strong> <?= htmlspecialchars($p['numero_processo']) ?></p>

                <!-- DADOS GERAIS -->
                <div class="mt-4">
                    <div class="section-title">Dados Gerais</div>

                    <p><strong>Cor:</strong> <?= campo($f, 'cor') ?></p>
                    <p><strong>Religião:</strong> <?= campo($f, 'religiao') ?> <?= campo($f, 'religiao_outro') ?></p>
                    <p><strong>Escolaridade:</strong> <?= campo($f, 'escolaridade') ?> <?= campo($f, 'escolaridade_outro') ?></p>
                    <p><strong>Renda:</strong> <?= campo($f, 'renda_familiar') ?> <?= campo($f, 'renda_outro') ?></p>
                    <p><strong>Trabalho:</strong> <?= campo($f, 'trabalho') ?> <?= campo($f, 'trabalho_outro') ?></p>
                    <p><strong>Profissão:</strong> <?= campo($f, 'profissao') ?></p>
                    <p><strong>Moradia:</strong> <?= campo($f, 'condicao_moradia') ?> <?= campo($f, 'moradia_outro') ?></p>
                    <p><strong>Relacionamento:</strong> <?= campo($f, 'relacionamento_atual') ?> <?= campo($f, 'relacionamento_outro') ?></p>
                </div>

                <!-- SAÚDE -->
                <div class="mt-4">
                    <div class="section-title">Saúde</div>

                    <p><strong>Problemas:</strong> <?= campo($f, 'problemas_saude') ?></p>
                    <p><strong>Medicação:</strong> <?= campo($f, 'uso_medicacao') ?></p>
                    <p><strong>Álcool:</strong> <?= campo($f, 'uso_alcool') ?></p>
                    <p><strong>Frequência:</strong> <?= campo($f, 'frequencia_bebida') ?></p>
                    <p><strong>Drogas:</strong> <?= campo($f, 'drogas_utilizadas') ?></p>
                </div>

                <!-- HISTÓRICO -->
                <div class="mt-4">
                    <div class="section-title">Histórico</div>

                    <p><strong>Violência praticada:</strong> <?= campo($f, 'violencia_praticada') ?></p>
                    <p><strong>Violência sofrida:</strong> <?= campo($f, 'violencia_sofrida') ?></p>
                    <p><strong>Histórico familiar:</strong> <?= campo($f, 'historico_familiar') ?></p>
                    <p><strong>Situação jurídica:</strong> <?= campo($f, 'situacao_juridica') ?></p>
                </div>

                <!-- EXPECTATIVA -->
                <div class="mt-4">
                    <div class="section-title">Expectativa</div>

                    <p><?= campo($f, 'expectativa_grupo') ?></p>
                </div>

            </div>
        </div>

    </div>

</body>

</html>