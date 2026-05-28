<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../lib/tfpdf/tfpdf.php';

auth();
canAny(['admin', 'atendente']);

$turma_id = $_GET['turma_id'] ?? null;
if (!$turma_id) die("Turma não informada");

// DADOS
$stmt = $pdo->prepare("SELECT * FROM turmas WHERE id = ?");
$stmt->execute([$turma_id]);
$turma = $stmt->fetch();

$stmt = $pdo->prepare("SELECT * FROM turmas_sessoes WHERE turma_id = ? ORDER BY data");
$stmt->execute([$turma_id]);
$sessoes = $stmt->fetchAll();

$stmt = $pdo->prepare("SELECT * FROM participantes WHERE turma_id = ? ORDER BY nome");
$stmt->execute([$turma_id]);
$participantes = $stmt->fetchAll();

// PRESENÇAS
$presencas = [];
$stmt = $pdo->prepare("
    SELECT * FROM presencas 
    WHERE sessao_id IN (
        SELECT id FROM turmas_sessoes WHERE turma_id = ?
    )
");
$stmt->execute([$turma_id]);

foreach ($stmt->fetchAll() as $p) {
    $presencas[$p['participante_id']][$p['sessao_id']] = $p['status'];
}

// PDF
$pdf = new tFPDF('L','mm','A4');
$pdf->AddPage();

// FONTES (IMPORTANTE)
$pdf->AddFont('DejaVu','','DejaVuSans.ttf', true);
$pdf->AddFont('DejaVu','B','DejaVuSans-Bold.ttf', true);

// HEADER
$pdf->Image(__DIR__.'/../assets/LogoPrefeitura.png', 10, 10, 45);
$pdf->Image(__DIR__.'/../assets/ProjetoSER.jpg', 250, 10, 35);

$pdf->SetFont('DejaVu','B',12);
$pdf->Cell(0,6,'Estado do Rio de Janeiro',0,1,'C');
$pdf->Cell(0,6,'Prefeitura Municipal de Paracambi',0,1,'C');

$pdf->SetFont('DejaVu','B',12);
$pdf->Cell(0,6,'Secretaria Municipal de Proteção e Política para a Mulher',0,1,'C');

$pdf->SetFont('DejaVu','B',12);
$pdf->Cell(0,6,'Projeto S.E.R. – Grupo Reflexivo para Homens',0,1,'C');

$pdf->Ln(8);

// INFO
$pdf->SetFont('DejaVu','B',12);
$pdf->Cell(0,8,"Turma: {$turma['nome']}",0,1);

$pdf->SetFont('DejaVu','',10);
$pdf->Cell(0,6,"Responsável: {$turma['responsavel']}",0,1);
$pdf->Cell(0,6,"Total de Sessões: ".count($sessoes),0,1);

$pdf->Ln(5);

// DIMENSÕES
$pageWidth = 277;
$colNome = 70;
$colPerc = 20;
$colSessao = ($pageWidth - $colNome - $colPerc) / max(count($sessoes),1);

// CABEÇALHO
$pdf->SetFont('DejaVu','B',8);
$pdf->SetFillColor(200,200,200);

$pdf->Cell($colNome,7,'Participante',1,0,'L',true);

foreach ($sessoes as $s) {
    $pdf->Cell($colSessao,7,date('d/m', strtotime($s['data'])),1,0,'C',true);
}

$pdf->Cell($colPerc,7,'%',1,1,'C',true);

// LINHAS
$pdf->SetFont('DejaVu','',8);

foreach ($participantes as $p) {

    $pdf->Cell($colNome,6,$p['nome'],1);

    $presente = 0;

    foreach ($sessoes as $s) {

        $status = $presencas[$p['id']][$s['id']] ?? 'falta';

        if ($status == 'presente') {
            $pdf->SetFillColor(40,167,69);
            $txtCell = 'P';
            $presente++;
        } elseif ($status == 'justificado') {
            $pdf->SetFillColor(255,193,7);
            $txtCell = 'J';
        } else {
            $pdf->SetFillColor(220,53,69);
            $txtCell = 'F';
        }

        $pdf->Cell($colSessao,6,$txtCell,1,0,'C',true);
    }

    $total = count($sessoes);
    $percentual = $total ? round(($presente/$total)*100) : 0;

    $pdf->SetFillColor(255,255,255);
    $pdf->Cell($colPerc,6,$percentual.'%',1,1,'C');
}

$pdf->Output();