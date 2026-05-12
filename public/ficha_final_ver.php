<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/auth.php';

auth();

$participante_id = $_GET['participante_id'] ?? null;
if (!$participante_id) die("Participante não informado");

// Busca o nome do participante
$stmt = $pdo->prepare("SELECT nome FROM participantes WHERE id = ?");
$stmt->execute([$participante_id]);
$p = $stmt->fetch();

if (!$p) die("Participante não encontrado");

// Busca a ficha de avaliação final
$stmt = $pdo->prepare("SELECT * FROM ficha_avaliacao_final WHERE participante_id = ?");
$stmt->execute([$participante_id]);
$f = $stmt->fetch();

if (!$f) {
    echo "<div class='container mt-5 text-center'>
            <div class='alert alert-info shadow-sm'>
                <i class='bi bi-info-circle fs-1'></i><br>
                A Ficha de Avaliação Final ainda não foi preenchida.
            </div>
            <a href='ficha_final.php?id=$participante_id' class='btn btn-success'>Preencher Avaliação Final</a>
          </div>";
    exit;
}

function campo($f, $c) {
    return !empty($f[$c]) ? nl2br(htmlspecialchars($f[$c])) : '<span class="text-muted italic">Não informado</span>';
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Avaliação Final - <?= htmlspecialchars($p['nome']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body { background: #f5f7f9; color: #2d3436; font-size: 0.95rem; }
        .card { border-radius: 15px; border: none; }
        
        .header-doc { display: flex; align-items: center; border-bottom: 2px solid #333; padding-bottom: 15px; margin-bottom: 30px; }
        .header-text { text-align: center; flex: 1; line-height: 1.3; }
        
        .section-title { 
            background: #f8f9fa; 
            font-weight: 800; 
            padding: 10px 15px; 
            border-radius: 6px; 
            margin: 25px 0 15px 0; 
            text-transform: uppercase; 
            font-size: 0.85rem;
            color: #0984e3;
            border-left: 5px solid #0984e3;
        }

        .question-box { margin-bottom: 18px; padding-left: 5px; }
        .label { font-weight: 700; color: #636e72; display: block; font-size: 0.85rem; text-transform: uppercase; margin-bottom: 4px; }
        .answer { background: #fff; padding: 5px 0; border-bottom: 1px solid #eee; min-height: 25px; }
        
        .badge-theme { background: #e1f5fe; color: #0288d1; border: 1px solid #b3e5fc; padding: 5px 12px; border-radius: 20px; font-size: 0.8rem; display: inline-block; margin: 2px; }

        @media print {
            .no-print { display: none !important; }
            body { background: white; padding: 0; }
            .card { box-shadow: none !important; }
            .section-title { background: #eee !important; color: black !important; border-left: 5px solid #333 !important; }
            .container { width: 100%; max-width: 100%; }
        }
    </style>
</head>
<body>

<div class="container py-4">

    <div class="mb-4 no-print d-flex justify-content-between align-items-center">
        <a href="participantes_detalhes.php?id=<?= $participante_id ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Voltar ao Perfil
        </a>
        <div class="d-flex gap-2">
            <a href="ficha_final_editar.php?id=<?= $participante_id ?>" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Editar Respostas
            </a>
            <button onclick="window.print()" class="btn btn-dark">
                <i class="bi bi-printer"></i> Imprimir Documento
            </button>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-5">

            <div class="header-doc">
                <img src="../assets/LogoPrefeitura.png" height="85" alt="Prefeitura">
                <div class="header-text">
                    <div class="fw-bold">ESTADO DO RIO DE JANEIRO</div>
                    <div class="fw-bold fs-5">PREFEITURA MUNICIPAL DE PARACAMBI</div>
                    <div class="small">SECRETARIA MUNICIPAL DE PROTEÇÃO E POLÍTICA PARA A MULHER</div>
                    <div class="fw-bold text-success mt-1">PROJETO S.E.R. – GRUPO REFLEXIVO PARA HOMENS</div>
                </div>
                <img src="../assets/ProjetoSER.jpg" height="85" alt="Projeto SER">
            </div>

            <div class="text-center mb-5">
                <h4 class="fw-bold">AVALIAÇÃO DE DESFECHO E IMPACTO</h4>
                <p class="text-muted">Aplicação Final do Protocolo de Atendimento</p>
            </div>

            <div class="mb-4 p-3 border rounded bg-light">
                <span class="label">Participante avaliado:</span>
                <span class="fs-5 fw-bold text-dark"><?= htmlspecialchars($p['nome']) ?></span>
            </div>

            <div class="section-title">1. Percepção sobre o Processo Jurídico</div>
            <div class="row">
                <div class="col-md-6 question-box">
                    <span class="label">Sentimento ao receber a denúncia:</span>
                    <div class="answer"><?= campo($f, 'sentimento_denuncia') ?></div>
                </div>
                <div class="col-md-6 question-box">
                    <span class="label">Considerou a denúncia justa?</span>
                    <div class="answer fw-bold text-primary"><?= strtoupper(campo($f, 'acha_justa')) ?></div>
                </div>
                <div class="col-12 question-box">
                    <span class="label">Justificativa da percepção:</span>
                    <div class="answer"><?= campo($f, 'motivo_denuncia') ?></div>
                </div>
            </div>

            <div class="section-title">2. Avaliação da Experiência no Grupo</div>
            <div class="row">
                <div class="col-md-6 question-box">
                    <span class="label">Houve dificuldade para participar?</span>
                    <div class="answer"><?= campo($f, 'dificuldade_participar') ?></div>
                </div>
                <div class="col-md-6 question-box">
                    <span class="label">Avaliação qualitativa da participação:</span>
                    <div class="answer"><?= campo($f, 'avaliacao_participacao') ?></div>
                </div>
                <div class="col-12 question-box">
                    <span class="label">Observações sobre as dificuldades:</span>
                    <div class="answer"><?= campo($f, 'motivo_dificuldade') ?></div>
                </div>
                <div class="col-md-6 question-box">
                    <span class="label">Pontos Positivos (O que foi bom):</span>
                    <div class="answer text-success"><?= campo($f, 'pontos_positivos') ?></div>
                </div>
                <div class="col-md-6 question-box">
                    <span class="label">Pontos Negativos (O que foi ruim):</span>
                    <div class="answer text-danger"><?= campo($f, 'pontos_negativos') ?></div>
                </div>
            </div>

            <div class="section-title">3. Absorção de Conteúdo</div>
            <div class="question-box">
                <span class="label">Temas identificados como mais relevantes:</span>
                <div class="mt-2">
                    <?php 
                    $temas = explode(',', $f['temas_importantes'] ?? '');
                    if(!empty($f['temas_importantes'])) {
                        foreach($temas as $tema) {
                            echo '<span class="badge-theme">' . trim(htmlspecialchars($tema)) . '</span>';
                        }
                    } else {
                        echo '<span class="text-muted">-</span>';
                    }
                    ?>
                </div>
            </div>

            <div class="section-title">4. Impacto e Autoavaliação de Mudança</div>
            <div class="row">
                <div class="col-md-4 question-box">
                    <span class="label">Percepção de mudança:</span>
                    <div class="answer fw-bold"><?= campo($f, 'houve_mudanca') ?></div>
                </div>
                <div class="col-md-8 question-box">
                    <span class="label">Descrição da mudança comportamental:</span>
                    <div class="answer"><?= campo($f, 'descricao_mudanca') ?></div>
                </div>
                <div class="col-md-6 question-box">
                    <span class="label">O que mais gostou na dinâmica do grupo?</span>
                    <div class="answer"><?= campo($f, 'gostou_grupo') ?></div>
                </div>
                <div class="col-md-6 question-box">
                    <span class="label">Sentimento ao concluir o ciclo:</span>
                    <div class="answer"><?= campo($f, 'como_saiu') ?></div>
                </div>
            </div>

            <div class="section-title">5. Recomendação e Melhorias</div>
            <div class="row">
                <div class="col-md-4 question-box">
                    <span class="label">Recomendaria o grupo?</span>
                    <div class="answer fw-bold"><?= campo($f, 'recomendaria') ?></div>
                </div>
                <div class="col-md-8 question-box">
                    <span class="label">Motivo da recomendação/não recomendação:</span>
                    <div class="answer"><?= campo($f, 'motivo_recomendacao') ?></div>
                </div>
                <div class="col-12 question-box">
                    <span class="label">Sugestões e Críticas Gerais:</span>
                    <div class="answer"><?= campo($f, 'sugestoes') ?></div>
                </div>
            </div>

            <div class="mt-5 pt-4 d-none d-print-block">
                <div class="row text-center mt-5">
                    <div class="col-6">
                        <div style="border-top: 1px solid #000; margin: 0 30px;"></div>
                        <small>Assinatura do Participante</small>
                    </div>
                    <div class="col-6">
                        <div style="border-top: 1px solid #000; margin: 0 30px;"></div>
                        <small>Responsável Técnico / Facilitador</small>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <small class="text-muted">Paracambi, RJ, <?= date('d/m/Y') ?></small>
                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>