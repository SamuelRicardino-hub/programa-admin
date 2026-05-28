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
    echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
          <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css'>
          <div class='container mt-5 text-center'>
            <div class='alert alert-warning shadow-sm border-0 p-4'>
                <i class='bi bi-exclamation-triangle fs-1 text-warning'></i><br><br>
                <h5>A Ficha de Inclusão ainda não foi preenchida para este participante.</h5>
            </div>
            <a href='ficha_inclusao.php?participante_id=$participante_id' class='btn btn-primary'>
                <i class='bi bi-plus-lg me-1'></i> Preencher Agora
            </a>
          </div>";
    exit;
}

/**
 * Função auxiliar para tratar campos vazios e alternativos
 */
function exibir($f, $coluna) {
    return !empty($f[$coluna]) ? htmlspecialchars($f[$coluna]) : '-';
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
            border-left: 4px solid #0d6efd;
        }
        .data-label { font-weight: 700; color: #6c757d; font-size: 0.8rem; text-transform: uppercase; margin-bottom: 0; }
        .data-value { font-size: 1rem; margin-bottom: 12px; border-bottom: 1px dotted #dee2e6; padding-bottom: 2px; color: #212529; }
        
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

    <div class="mb-4 no-print d-flex justify-content-between align-items-center">
    <a href="participantes_detalhes.php?id=<?= $participante_id ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Voltar para Detalhes
    </a>
    <div class="d-flex gap-2">
        <a href="ficha_inclusao.php?participante_id=<?= $participante_id ?>" class="btn btn-warning">
            <i class="bi bi-pencil-square"></i> Editar Ficha
        </a>
        <a href="ficha_inclusao_pdf.php?participante_id=<?= $participante_id ?>" target="_blank" class="btn btn-danger">
            <i class="bi bi-file-earmark-pdf"></i> Visualizar / Imprimir PDF
        </a>
    </div>
</div>

    <div class="card shadow-sm">
        <div class="card-body p-5">

            <div class="header-doc">
                <img src="../assets/LogoPrefeitura.png" height="80" alt="Logo Prefeitura" onerror="this.style.display='none'">
                <div class="header-text">
                    <div class="fw-bold fs-6">ESTADO DO RIO DE JANEIRO</div>
                    <div class="fw-bold fs-5">PREFEITURA MUNICIPAL DE PARACAMBI</div>
                    <div class="fw-bold fs-5">SECRETARIA MUNICIPAL DE PROTEÇÃO E POLÍTICA PARA A MULHER</div>
                    <div class="fw-bold text-primary mt-1">PROJETO S.E.R. – GRUPO REFLEXIVO PARA HOMENS</div>
                </div>
                <img src="../assets/ProjetoSER.jpg" height="80" alt="Logo Projeto SER" style="border-radius: 5px;" onerror="this.style.display='none'">
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
                    <div class="data-value"><?= htmlspecialchars($p['numero_processo'] ?: '-') ?></div>
                </div>
            </div>

            <div class="section-title mt-3"><i class="bi bi-person-lines-fill me-2"></i>Perfil Sociobiográfico</div>
            <div class="row">
                <div class="col-md-4">
                    <p class="data-label">Grau de Parentesco com a Denunciante</p>
                    <div class="data-value"><?= exibir($f, 'parentesco_denunciante') ?></div>
                </div>
                <div class="col-md-4">
                    <p class="data-label">Cor/Raça</p>
                    <div class="data-value"><?= exibir($f, 'cor') ?></div>
                </div>
                <div class="col-md-4">
                    <p class="data-label">Naturalidade</p>
                    <div class="data-value"><?= exibir($f, 'naturalidade') ?></div>
                </div>
                <div class="col-md-4">
                    <p class="data-label">Religião</p>
                    <div class="data-value"><?= exibir($f, 'religiao') ?></div>
                </div>
                <div class="col-md-4">
                    <p class="data-label">Escolaridade</p>
                    <div class="data-value"><?= exibir($f, 'escolaridade') ?></div>
                </div>
                <div class="col-md-4">
                    <p class="data-label">Estado Civil / Relacionamento Atual</p>
                    <div class="data-value fw-bold"><?= exibir($f, 'relacionamento_atual') ?></div>
                </div>

                <?php if ($f['relacionamento_atual'] !== 'Solteiro'): ?>
                    <div class="col-md-6 bg-light p-2 rounded border-start border-primary border-3 mb-3 ms-2">
                        <p class="data-label text-primary">Relacionamento com:</p>
                        <div class="data-value border-0 mb-0"><?= exibir($f, 'relacionamento_amoroso_detalhe') ?></div>
                    </div>
                    <div class="col-md-5 bg-light p-2 rounded border-start border-primary border-3 mb-3 ms-2">
                        <p class="data-label text-primary">Como é o relacionamento com a parceira atual?</p>
                        <div class="data-value border-0 mb-0"><?= exibir($f, 'relacionamento_parceira_atual') ?></div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="section-title mt-3"><i class="bi bi-cash-stack me-2"></i>Situação Econômica e Moradia</div>
            <div class="row">
                <div class="col-md-4">
                    <p class="data-label">Renda Familiar</p>
                    <div class="data-value"><?= exibir($f, 'renda_familiar') ?></div>
                </div>
                <div class="col-md-4">
                    <p class="data-label">Vínculo de Trabalho / Ocupação</p>
                    <div class="data-value"><?= exibir($f, 'trabalho_ocupacao') ?></div>
                </div>
                <div class="col-md-4">
                    <p class="data-label">Profissão Atual/Última</p>
                    <div class="data-value"><?= exibir($f, 'profissao') ?></div>
                </div>
                <div class="col-md-12">
                    <p class="data-label">Condição de Moradia</p>
                    <div class="data-value"><?= exibir($f, 'condicao_moradia') ?></div>
                </div>
            </div>

            <div class="section-title mt-3"><i class="bi bi-people-fill me-2"></i>Estrutura Familiar e Paternidade</div>
            <div class="row">
                <div class="col-md-4">
                    <p class="data-label">Quantidade de Filhos</p>
                    <div class="data-value fw-bold"><?= (int)$f['qtd_filhos'] ?></div>
                </div>
                <div class="col-md-4">
                    <p class="data-label">Pessoas morando na mesma residência</p>
                    <div class="data-value"><?= (int)$f['pessoas_na_casa'] ?></div>
                </div>
                <div class="col-md-4">
                    <p class="data-label">Divide os trabalhos domésticos da casa?</p>
                    <div class="data-value"><?= exibir($f, 'divisao_domestica') ?></div>
                </div>

                <?php if ((int)$f['qtd_filhos'] > 0): ?>
                    <div class="col-md-6">
                        <p class="data-label">Filhos com a atual companheira</p>
                        <div class="data-value"><?= exibir($f, 'filhos_com_atual') ?></div>
                    </div>
                    <div class="col-md-6">
                        <p class="data-label">Filhos com a denunciante</p>
                        <div class="data-value"><?= exibir($f, 'filhos_com_denunciante') ?></div>
                    </div>
                    <div class="col-md-12">
                        <p class="data-label">Qual a frequência que vê os filhos (caso não more junto)?</p>
                        <div class="data-value"><?= exibir($f, 'frequencia_ver_filhos') ?></div>
                    </div>
                    <div class="col-md-4">
                        <p class="data-label">Conversam sobre a criação dos filhos?</p>
                        <div class="data-value"><?= exibir($f, 'conversa_criacao_filhos') ?></div>
                    </div>
                    <div class="col-md-4">
                        <p class="data-label">Auxilia nas lições de casa?</p>
                        <div class="data-value"><?= exibir($f, 'auxilio_licoes_casa') ?></div>
                    </div>
                    <div class="col-md-4">
                        <p class="data-label">Vai às reuniões da escola?</p>
                        <div class="data-value"><?= exibir($f, 'reunioes_escola') ?></div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="section-title mt-3"><i class="bi bi-heart-pulse-fill me-2"></i>Condições de Saúde e Hábitos</div>
            <div class="row">
                <div class="col-md-6">
                    <p class="data-label">Apresenta problemas de saúde?</p>
                    <div class="data-value"><?= nl2br(exibir($f, 'problemas_saude')) ?></div>
                </div>
                <div class="col-md-6">
                    <p class="data-label">Faz uso de medicação?</p>
                    <div class="data-value"><?= nl2br(exibir($f, 'medicacao')) ?></div>
                </div>
                <div class="col-md-4">
                    <p class="data-label">Frequenta bares?</p>
                    <div class="data-value"><?= exibir($f, 'frequencia_bares') ?></div>
                </div>
                <div class="col-md-4">
                    <p class="data-label">Costuma beber</p>
                    <div class="data-value fw-semibold text-dark"><?= exibir($f, 'bebidas_comuns') ?></div>
                </div>
                <div class="col-md-4">
                    <p class="data-label">Quais drogas já utilizou</p>
                    <div class="data-value fw-semibold text-dark"><?= exibir($f, 'drogas_utilizadas') ?></div>
                </div>
            </div>

            <div class="section-title mt-3"><i class="bi bi-exclamation-octagon-fill me-2"></i>Histórico de Violência</div>
            <div class="row">
                <div class="col-md-12">
                    <p class="data-label">Durante o último ano praticou algum tipo de violência?</p>
                    <div class="data-value fw-bold <?= $f['praticou_violencia_ultimo_ano'] !== 'Não' ? 'text-danger' : '' ?>">
                        <?= exibir($f, 'praticou_violencia_ultimo_ano') ?>
                    </div>
                </div>

                <?php if ($f['praticou_violencia_ultimo_ano'] !== 'Não' && !empty($f['praticou_violencia_ultimo_ano'])): ?>
                    <div class="col-md-12 bg-light p-3 rounded mb-3 border-start border-danger border-3">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="data-label text-danger">Em quem praticou:</p>
                                <div class="data-value border-0 fw-semibold"><?= exibir($f, 'violencia_em_quem') ?></div>
                            </div>
                            <div class="col-md-6">
                                <p class="data-label text-danger">Tipo de violência praticada:</p>
                                <div class="data-value border-0 fw-semibold"><?= exibir($f, 'tipo_violencia_praticada') ?></div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="col-md-6">
                    <p class="data-label">Na infância, o pai era presente?</p>
                    <div class="data-value"><?= exibir($f, 'pai_presente_infancia') ?></div>
                </div>
                <div class="col-md-6">
                    <p class="data-label">Houve conflitos com violência na família de origem?</p>
                    <div class="data-value"><?= exibir($f, 'conflitos_infancia') ?></div>
                </div>
                
                <div class="col-md-12">
                    <p class="data-label">Já foi agredido pela companheira?</p>
                    <div class="data-value"><?= exibir($f, 'ja_foi_agredido_companheira') ?></div>
                </div>

                <?php if ($f['ja_foi_agredido_companheira'] === 'Sim'): ?>
                    <div class="col-md-6 bg-light p-2 rounded mb-2 ms-2">
                        <p class="data-label text-dark">Qual tipo de violência sofreu?</p>
                        <div class="data-value border-0 mb-0"><?= exibir($f, 'tipo_violencia_sofrida') ?></div>
                    </div>
                    <div class="col-md-5 bg-light p-2 rounded mb-2 ms-2">
                        <p class="data-label text-dark">Denunciou? Motivo:</p>
                        <div class="data-value border-0 mb-0"><?= exibir($f, 'denunciou_motivo') ?></div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="section-title mt-3"><i class="bi bi-gavel me-2"></i>Situação Jurídica e Histórico Penal</div>
            <div class="row">
                <div class="col-md-6">
                    <p class="data-label">Já foi indiciado por violência contra a mulher anteriormente?</p>
                    <div class="data-value"><?= exibir($f, 'indiciado_anteriormente') ?></div>
                </div>
                <div class="col-md-6">
                    <p class="data-label">Fez uso de drogas/álcool antes de cometer a violência atual?</p>
                    <div class="data-value"><?= exibir($f, 'uso_drogas_antes_fato') ?></div>
                </div>

                <?php if ($f['indiciado_anteriormente'] === 'Sim'): ?>
                    <div class="col-md-12 bg-light p-2 rounded mb-2 border-start border-warning border-3">
                        <p class="data-label text-warning-emphasis">Que tipo de violência anterior?</p>
                        <div class="data-value border-0 mb-0"><?= exibir($f, 'tipo_violencia_anterior') ?></div>
                    </div>
                <?php endif; ?>

                <div class="col-md-12">
                    <p class="data-label">Já foi indiciado por outro motivo (Exceto Maria da Penha)?</p>
                    <div class="data-value"><?= exibir($f, 'indiciado_outro_motivo') ?></div>
                </div>
                <div class="col-md-12">
                    <p class="data-label">Já esteve preso ou cumpriu medida socioeducativa?</p>
                    <div class="data-value border-0" style="white-space: pre-line;"><?= exibir($f, 'historico_prisao') ?></div>
                </div>
            </div>

            <div class="mt-5 pt-5 d-none d-print-block">
                <div class="row text-center">
                    <div class="col-6">
                        <div style="border-top: 1px solid #000; margin: 0 30px;"></div>
                        <small class="text-uppercase fw-bold">Assinatura do Participante</small>
                    </div>
                    <div class="col-6">
                        <div style="border-top: 1px solid #000; margin: 0 30px;"></div>
                        <small class="text-uppercase fw-bold">Responsável Técnico / Projeto S.E.R.</small>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>