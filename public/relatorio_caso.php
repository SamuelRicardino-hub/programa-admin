<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../lib/tfpdf/tfpdf.php';

auth();

$caso_id = $_GET['id'] ?? null;
if (!$caso_id) die("Caso não informado");

// PARTICIPANTE
$stmt = $pdo->prepare("SELECT * FROM participantes WHERE caso_id = ?");
$stmt->execute([$caso_id]);
$p = $stmt->fetch();

// FICHAS
$stmt = $pdo->prepare("SELECT * FROM ficha_inclusao WHERE participante_id = ?");
$stmt->execute([$p['id']]);
$inc = $stmt->fetch();

$stmt = $pdo->prepare("SELECT * FROM ficha_avaliacao_final WHERE participante_id = ?");
$stmt->execute([$p['id']]);
$final = $stmt->fetch();

// PDF
$pdf = new tFPDF();
$pdf->AddPage();

// FONTES
$pdf->AddFont('DejaVu','','DejaVuSans.ttf', true);
$pdf->AddFont('DejaVu','B','DejaVuSans-Bold.ttf', true);

// HEADER
$pdf->Image(__DIR__.'/../assets/LogoPrefeitura.png', 10, 8, 20);
$pdf->Image(__DIR__.'/../assets/ProjetoSER.png', 175, 8, 20);

$pdf->SetFont('DejaVu','B',12);
$pdf->Cell(0,6,'Estado do Rio de Janeiro',0,1,'C');
$pdf->Cell(0,6,'Prefeitura Municipal de Paracambi',0,1,'C');

$pdf->SetFont('DejaVu','',10);
$pdf->Cell(0,6,'Secretaria Municipal de Proteção e Política para a Mulher',0,1,'C');

$pdf->SetFont('DejaVu','B',11);
$pdf->Cell(0,6,'Projeto S.E.R. – Grupo Reflexivo para Homens',0,1,'C');

$pdf->Ln(10);

// TÍTULO
$pdf->SetFont('DejaVu','B',14);
$pdf->Cell(0,8,"Relatório do Caso Nº $caso_id",0,1,'C');

$pdf->Ln(5);

// PARTICIPANTE
$pdf->SetFont('DejaVu','B',12);
$pdf->Cell(0,6,'Dados do Participante',0,1);

$pdf->SetFont('DejaVu','',11);
$pdf->Cell(0,6,"Nome: {$p['nome']}",0,1);
$pdf->Cell(0,6,"CPF: {$p['cpf']}",0,1);

$pdf->Ln(5);

// INCLUSÃO
if ($inc) {
    $pdf->SetFont('DejaVu','B',12);
    $pdf->Cell(0,6,'Ficha de Inclusão',0,1);

    $pdf->SetFont('DejaVu','',10);
    $pdf->MultiCell(0,5,"Escolaridade: ".$inc['escolaridade']);
    $pdf->MultiCell(0,5,"Profissão: ".$inc['profissao']);
    $pdf->MultiCell(0,5,"Expectativa: ".$inc['expectativa_grupo']);
}

// FINAL
if ($final) {
    $pdf->Ln(5);

    $pdf->SetFont('DejaVu','B',12);
    $pdf->Cell(0,6,'Ficha Final',0,1);

    $pdf->SetFont('DejaVu','',10);
    $pdf->MultiCell(0,5,"Houve mudança: ".$final['houve_mudanca']);
    $pdf->MultiCell(0,5,"Descrição: ".$final['descricao_mudanca']);
    $pdf->MultiCell(0,5,"Recomendaria: ".$final['recomendaria']);
}

$pdf->Output();