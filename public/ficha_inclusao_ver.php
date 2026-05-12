<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/auth.php';

auth();

$participante_id = $_GET['participante_id'] ?? null;
if (!$participante_id) die("Participante não informado");

// Busca dados básicos do participante
$stmt = $pdo->prepare("SELECT nome, numero_processo FROM participantes WHERE id = ?");
$stmt->execute([$participante_id]);
$p = $stmt->fetch();

if (!$p) die("Participante não encontrado");

// Busca a ficha técnica completa
$stmt = $pdo->prepare("SELECT * FROM ficha_inclusao WHERE participante_id = ?");
$stmt->execute([$participante_id]);
$f = $stmt->fetch();

if (!$f) {
    echo "<div class='container mt-5 text-center'>
            <div class='alert alert-warning shadow-sm'>
                <i class='bi bi-exclamation-triangle fs-1'></i><br>
                A Ficha de Inclusão ainda não foi preenchida para este participante.
            </div>
            <a href='ficha_inclusao.php?id=$participante_id' class='btn btn-primary'>Preencher Agora</a>
          </div>";
    exit;
}

/**
 * Função auxiliar para tratar campos vazios e concatenar o campo "outro"
 */
function exibir($f, $principal, $outro = null) {
    $valor = !empty($f[$principal]) ? htmlspecialchars($f[$principal]) : '-';
    if ($outro && !empty($f[$outro])) {
        $valor .= " (" . htmlspecialchars($f[$outro]) . ")";
    }
    return $valor;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Visualizar Ficha - <?= htmlspecialchars($p['nome']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body { background: #f8f9fa; color: #333; }
        .card { border-radius: 15px; border: none; }
        .header-doc { display: flex; align-items: center; border-bottom: 2px solid #000; padding-bottom: 15px; margin-bottom: 25px; }
        .header-text { text-align: center; flex: 1; font-size: 0.9rem; line-height: 1.2; }
        .section-title { 
            background: #e9ecef; 
            font-weight: bold; 
            padding: 8px 15px; 
            border-radius: 5px; 
            margin-bottom: 15px; 
            text-transform: uppercase; 
            font-size: 0.85rem;
            color: #495057;
            border-left: 4px solid var(--bs-primary);
        }
        .data-label { font-weight: 700; color: #6c757d; font-size: 0.8rem; text-transform: uppercase; margin-bottom: 0; }
        .data-value { font-size: 1rem; margin-bottom: 12px; border-bottom: 1px dotted #dee2e6; padding-bottom: 2px; }
        
        @media print {
            .no-print { display: none !important; }
            body { background: white; }
            .card { box-shadow: none !important; }
            .container { width: 100% !important; max-width: 100% !important; margin: 0 !important; padding: 0 !important; }
        }
    </style>
</head>
<body>

<div class="container py-4">

    <div class="mb-4 no-print d-flex justify-content-between">
        <a href="participantes_detalhes.php?id=<?= $participante_id ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
        <div class="d-flex gap-2">
            <a href="ficha_inclusao_editar.php?participante_id=<?= $participante_id ?>" class="btn btn-warning">
                <i class="bi bi-pencil-square"></i> Editar
            </a>
            <button onclick="window.print()" class="btn btn-dark">
                <i class="bi bi-printer"></i> Imprimir Ficha
            </button>
            <a href="ficha_inclusao_pdf.php?participante_id=<?= $participante_id ?>" class="btn btn-danger">
                <i class="bi bi-file-pdf"></i> Gerar PDF
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-5">

            <div class="header-doc">
                <img src="../assets/LogoPrefeitura.png" height="80" alt="Logo Prefeitura">
                <div class="header-text">
                    <div class="fw-bold fs-6">ESTADO DO RIO DE JANEIRO</div>
                    <div class="fw-bold fs-5">PREFEITURA MUNICIPAL DE PARACAMBI</div>
                    <div class="small">SECRETARIA MUNICIPAL DE PROTEÇÃO E POLÍTICA PARA A MULHER</div>
                    <div class="fw-bold text-primary mt-1">PROJETO S.E.R. – GRUPO REFLEXIVO PARA HOMENS</div>
                </div>
                <img src="../assets/ProjetoSER.jpg" height="80" alt="Logo Projeto SER" style="border-radius: 5px;">
            </div>

            <div class="text-center mb-4">
                <h4 class="fw-bold">FICHA DE INCLUSÃO TÉCNICA</h4>
                <p class="text-muted small">Registro Geral de Identificação Psicossocial</p>
            </div>

            <div class="section-title"><i class="bi bi-folder2-open me-2"></i>Controle Interno e Processual</div>
            <div class="row">
                <div class="col-md-6">
                    <p class="data-label">Participante</p>
                    <div class="data-value fw-bold text-uppercase"><?= htmlspecialchars($p['nome']) ?></div>
                </div>
                <div class="col-md-3">
                    <p class="data-label">Nº do Caso (Interno)</p>
                    <div class="data-value"><?= exibir($f, 'numero_caso') ?></div>
                </div>
                <div class="col-md-3">
                    <p class="data-label">Nº do Processo (TJ)</p>
                    <div class="data-value"><?= htmlspecialchars($p['numero_processo']) ?></div>
                </div>
            </div>

            <div class="section-title mt-3"><i class="bi bi-person-lines-fill me-2"></i>Perfil Sociobiográfico</div>
            <div class="row">
                <div class="col-md-3">
                    <p class="data-label">Idade</p>
                    <div class="data-value"><?= exibir($f, 'idade') ?> anos</div>
                </div>
                <div class="col-md-3">
                    <p class="data-label">Cor/Raça</p>
                    <div class="data-value"><?= exibir($f, 'cor') ?></div>
                </div>
                <div class="col-md-3">
                    <p class="data-label">Naturalidade</p>
                    <div class="data-value"><?= exibir($f, 'naturalidade') ?></div>
                </div>
                <div class="col-md-3">
                    <p class="data-label">Religião</p>
                    <div class="data-value"><?= exibir($f, 'religiao', 'religiao_outro') ?></div>
                </div>
                
                <div class="col-md-6">
                    <p class="data-label">Escolaridade</p>
                    <div class="data-value"><?= exibir($f, 'escolaridade', 'escolaridade_outro') ?></div>
                </div>
                <div class="col-md-6">
                    <p class="data-label">Estado Civil / Relacionamento</p>
                    <div class="data-value"><?= exibir($f, 'relacionamento_atual', 'relacionamento_outro') ?></div>
                </div>
            </div>

            <div class="section-title mt-3"><i class="bi bi-cash-stack me-2"></i>Situação Econômica e Moradia</div>
            <div class="row">
                <div class="col-md-4">
                    <p class="data-label">Renda Familiar</p>
                    <div class="data-value"><?= exibir($f, 'renda_familiar', 'renda_outro') ?></div>
                </div>
                <div class="col-md-4">
                    <p class="data-label">Ocupação/Trabalho</p>
                    <div class="data-value"><?= exibir($f, 'trabalho', 'trabalho_outro') ?></div>
                </div>
                <div class="col-md-4">
                    <p class="data-label">Profissão</p>
                    <div class="data-value"><?= exibir($f, 'profissao') ?></div>
                </div>
                <div class="col-md-12">
                    <p class="data-label">Condição de Moradia</p>
                    <div class="data-value"><?= exibir($f, 'condicao_moradia', 'moradia_outro') ?></div>
                </div>
            </div>

            <div class="section-title mt-3"><i class="bi bi-chat-left-text me-2"></i>Relacionamento e Parentesco</div>
            <div class="row">
                <div class="col-md-6">
                    <p class="data-label">Grau de Parentesco com a Denunciante</p>
                    <div class="data-value"><?= exibir($f, 'parentesco') ?></div>
                </div>
                <div class="col-md-6">
                    <p class="data-label">Mantém relação com:</p>
                    <div class="data-value"><?= exibir($f, 'relacao_com', 'relacao_com_outro') ?></div>
                </div>
            </div>

            <div class="mt-5 pt-4 d-none d-print-block">
                <div class="row text-center">
                    <div class="col-6">
                        <div style="border-top: 1px solid #000; margin: 0 20px;"></div>
                        <small>Assinatura do Participante</small>
                    </div>
                    <div class="col-6">
                        <div style="border-top: 1px solid #000; margin: 0 20px;"></div>
                        <small>Responsável Técnico / Projeto S.E.R.</small>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>