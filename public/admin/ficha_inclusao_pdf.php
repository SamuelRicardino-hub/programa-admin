<?php
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../assets/fpdf/fpdf.php';

auth();

$participante_id = $_GET['participante_id'] ?? null;
if (!$participante_id) die("Participante não informado");

// Participante
$stmt = $pdo->prepare("SELECT nome FROM participantes WHERE id = ?");
$stmt->execute([$participante_id]);
$p = $stmt->fetch();

if (!$p) die("Participante não encontrado");

// Ficha
$stmt = $pdo->prepare("SELECT * FROM ficha_inclusao WHERE participante_id = ?");
$stmt->execute([$participante_id]);
$f = $stmt->fetch();

if (!$f) die("Ficha não encontrada");

function campo($f, $c) {
    return !empty($f[$c]) ? $f[$c] : '-';
}

// Criar PDF
$pdf = new FPDF();
$pdf->AddPage();

// ================== LOGOS ==================
$pdf->Image(__DIR__ . '/../../assets/LogoPrefeitura.png', 10, 10, 25);
$pdf->Image(__DIR__ . '/../../assets/ProjetoSER.png', 170, 10, 25);

// ================== CABEÇALHO ==================
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,5,'Estado do Rio de Janeiro',0,1,'C');
$pdf->Cell(0,5,'Prefeitura Municipal de Paracambi',0,1,'C');

$pdf->SetFont('Arial','',11);
$pdf->Cell(0,5,'Secretaria Municipal de Protecao e Politica para a Mulher',0,1,'C');

$pdf->SetFont('Arial','B',11);
$pdf->Cell(0,5,'Projeto S.E.R. – Grupo Reflexivo para Homens',0,1,'C');

$pdf->Ln(10);

// ================== DADOS ==================
$pdf->SetFont('Arial','',11);

$pdf->Cell(0,6,'Participante: '.$p['nome'],0,1);

// ================== FUNÇÃO AUX ==================
function linha($pdf, $label, $valor) {
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(60,6,$label.':',0,0);
    $pdf->SetFont('Arial','',10);
    $pdf->MultiCell(0,6,$valor,0,1);
}

// ================== SEÇÕES ==================

$pdf->Ln(5);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,6,'Dados Gerais',0,1);

linha($pdf,'Cor', campo($f,'cor'));
linha($pdf,'Religiao', campo($f,'religiao'));
linha($pdf,'Escolaridade', campo($f,'escolaridade'));
linha($pdf,'Renda', campo($f,'renda_familiar'));
linha($pdf,'Trabalho', campo($f,'trabalho'));
linha($pdf,'Profissao', campo($f,'profissao'));
linha($pdf,'Moradia', campo($f,'condicao_moradia'));
linha($pdf,'Relacionamento', campo($f,'relacionamento_atual'));

$pdf->Ln(5);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,6,'Saude',0,1);

linha($pdf,'Problemas', campo($f,'problemas_saude'));
linha($pdf,'Medicacao', campo($f,'uso_medicacao'));
linha($pdf,'Uso de alcool', campo($f,'uso_alcool'));
linha($pdf,'Frequencia', campo($f,'frequencia_bebida'));
linha($pdf,'Drogas', campo($f,'drogas_utilizadas'));

$pdf->Ln(5);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,6,'Historico',0,1);

linha($pdf,'Violencia praticada', campo($f,'violencia_praticada'));
linha($pdf,'Violencia sofrida', campo($f,'violencia_sofrida'));
linha($pdf,'Historico familiar', campo($f,'historico_familiar'));
linha($pdf,'Situacao juridica', campo($f,'situacao_juridica'));

$pdf->Ln(5);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,6,'Expectativa',0,1);

$pdf->SetFont('Arial','',10);
$pdf->MultiCell(0,6, campo($f,'expectativa_grupo'));

// ================== OUTPUT ==================
$pdf->Output('D', 'ficha_inclusao.pdf');