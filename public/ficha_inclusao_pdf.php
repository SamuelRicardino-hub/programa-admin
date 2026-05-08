<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../lib/tfpdf/tfpdf.php';

auth();

$participante_id = $_GET['participante_id'] ?? null;
if (!$participante_id) die("Participante não informado");

// PARTICIPANTE
$stmt = $pdo->prepare("SELECT nome FROM participantes WHERE id = ?");
$stmt->execute([$participante_id]);
$p = $stmt->fetch();

if (!$p) die("Participante não encontrado");

// FICHA
$stmt = $pdo->prepare("SELECT * FROM ficha_inclusao WHERE participante_id = ?");
$stmt->execute([$participante_id]);
$f = $stmt->fetch();

if (!$f) die("Ficha de inclusão não encontrada");

// helper
function campo($f, $c) {
    return !empty($f[$c]) ? $f[$c] : '-';
}

// PDF
$pdf = new tFPDF();
$pdf->AddPage();

// Fonte UTF-8
$pdf->AddFont('DejaVu','','DejaVuSans.ttf', true);
$pdf->AddFont('DejaVu','', 'DejaVuSans.ttf', true);
$pdf->AddFont('DejaVu','B','DejaVuSans-Bold.ttf', true);
// ===== LOGOS =====
$logoPrefeitura = __DIR__ . '../assets/LogoPrefeitura.png';
$logoProjeto    = __DIR__ . '../assets/ProjetoSER.jpg';

if (file_exists($logoPrefeitura)) {
    $pdf->Image($logoPrefeitura, 5, 10, 40);
}

if (file_exists($logoProjeto)) {
    $pdf->Image($logoProjeto, 170, 10, 30);
}

// ===== CABEÇALHO =====
$pdf->SetFont('DejaVu','B',12);
$pdf->Cell(0,6,'Estado do Rio de Janeiro',0,1,'C');
$pdf->Cell(0,6,'Prefeitura Municipal de Paracambi',0,1,'C');

$pdf->SetFont('DejaVu','B',11);
$pdf->Cell(0,6,'Secretaria Municipal de Proteção e Política para a Mulher',0,1,'C');

$pdf->SetFont('DejaVu','B',11);
$pdf->Cell(0,6,'Projeto S.E.R. – Grupo Reflexivo para Homens',0,1,'C');

$pdf->Ln(8);

$pdf->SetFont('DejaVu', 'B', 13);
$pdf->Cell(0,8, 'Ficha de Inclusão',0,1, 'C');

$pdf->Ln(5);
// ===== PARTICIPANTE =====
$pdf->SetFont('DejaVu','B',11);
$pdf->Cell(0,8,'Participante: '.$p['nome'],0,1);

$pdf->Ln(5);

// ===== DADOS INICIAIS =====
$pdf->SetFont('DejaVu','B',12);
$pdf->Cell(0,8,'Dados do Caso',0,1);

$pdf->SetFont('DejaVu','',11);
$pdf->MultiCell(0,6,'Número do caso: '.campo($f,'numero_caso'));
$pdf->MultiCell(0,6,'Número do processo: '.campo($f,'numero_processo'));
$pdf->MultiCell(0,6,'Nome completo: '.campo($f,'nome_completo'));
$pdf->MultiCell(0,6,'Parentesco: '.campo($f,'parentesco'));
$pdf->MultiCell(0,6,'Idade: '.campo($f,'idade'));
$pdf->MultiCell(0,6,'Naturalidade: '.campo($f,'naturalidade'));

$pdf->Ln(4);

// ===== DADOS SOCIAIS =====
$pdf->SetFont('DejaVu','B',12);
$pdf->Cell(0,8,'Dados Sociais',0,1);

$pdf->SetFont('DejaVu','',11);
$pdf->MultiCell(0,6,'Cor: '.campo($f,'cor'));

$pdf->MultiCell(0,6,'Relacionamento atual: '
    .campo($f,'relacionamento_atual').' '
    .campo($f,'relacionamento_outro'));

$pdf->MultiCell(0,6,'Religião: '
    .campo($f,'religiao').' '
    .campo($f,'religiao_outro'));

$pdf->MultiCell(0,6,'Escolaridade: '
    .campo($f,'escolaridade').' '
    .campo($f,'escolaridade_outro'));

$pdf->MultiCell(0,6,'Renda familiar: '
    .campo($f,'renda_familiar').' '
    .campo($f,'renda_outro'));

$pdf->MultiCell(0,6,'Trabalho: '
    .campo($f,'trabalho').' '
    .campo($f,'trabalho_outro'));

$pdf->MultiCell(0,6,'Profissão: '.campo($f,'profissao'));

$pdf->MultiCell(0,6,'Condição de moradia: '
    .campo($f,'condicao_moradia').' '
    .campo($f,'moradia_outro'));

$pdf->Ln(4);

// ===== SAÚDE =====
$pdf->SetFont('DejaVu','B',12);
$pdf->Cell(0,8,'Saúde',0,1);

$pdf->SetFont('DejaVu','',11);
$pdf->MultiCell(0,6,'Problemas de saúde: '.campo($f,'problemas_saude'));
$pdf->MultiCell(0,6,'Uso de medicação: '.campo($f,'uso_medicacao'));
$pdf->MultiCell(0,6,'Uso de álcool: '.campo($f,'uso_alcool'));
$pdf->MultiCell(0,6,'Frequência: '.campo($f,'frequencia_bebida'));
$pdf->MultiCell(0,6,'Drogas utilizadas: '.campo($f,'drogas_utilizadas'));

$pdf->Ln(4);

// ===== HISTÓRICO =====
$pdf->SetFont('DejaVu','B',12);
$pdf->Cell(0,8,'Histórico',0,1);

$pdf->SetFont('DejaVu','',11);
$pdf->MultiCell(0,6,'Violência praticada: '.campo($f,'violencia_praticada'));
$pdf->MultiCell(0,6,'Violência sofrida: '.campo($f,'violencia_sofrida'));
$pdf->MultiCell(0,6,'Histórico familiar: '.campo($f,'historico_familiar'));
$pdf->MultiCell(0,6,'Situação jurídica: '.campo($f,'situacao_juridica'));

$pdf->Ln(4);

// ===== EXPECTATIVA =====
$pdf->SetFont('DejaVu','B',12);
$pdf->Cell(0,8,'Expectativa',0,1);

$pdf->SetFont('DejaVu','',11);
$pdf->MultiCell(0,6,campo($f,'expectativa_grupo'));

// ===== OUTPUT =====
$pdf->Output('I', 'ficha_inclusao.pdf');