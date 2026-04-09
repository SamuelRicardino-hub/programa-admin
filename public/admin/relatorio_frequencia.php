<?php
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../lib/fpdf/fpdf.php';

auth();
canAny(['admin', 'atendente']);

function txt($t) {
    return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $t ?? '');
}

$turma_id = $_GET['turma_id'] ?? null;
if (!$turma_id) die("Turma não informada");

// ==============================
// 📥 DADOS
// ==============================
$stmt = $pdo->prepare("SELECT * FROM turmas WHERE id = ?");
$stmt->execute([$turma_id]);
$turma = $stmt->fetch();

$stmt = $pdo->prepare("SELECT * FROM turmas_sessoes WHERE turma_id = ? ORDER BY data");
$stmt->execute([$turma_id]);
$sessoes = $stmt->fetchAll();

$stmt = $pdo->prepare("SELECT * FROM participantes WHERE turma_id = ? ORDER BY nome");
$stmt->execute([$turma_id]);
$participantes = $stmt->fetchAll();

// ==============================
// 📊 PRESENÇAS
// ==============================
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

// ==============================
// 🧾 PDF
// ==============================
class PDF extends FPDF {

    function Header() {

        $logo = __DIR__ . '/../../assets/logo.png';
        if (file_exists($logo)) {
            $this->Image($logo, 10, 8, 20);
        }

        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'RELATÓRIO DE FREQUÊNCIA'), 0, 1, 'C');

        $this->SetFont('Arial', '', 11);
        $this->Cell(0, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Secretaria de Assistência Social'), 0, 1, 'C');

        $this->Ln(5);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', '', 8);

        $this->Cell(0, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Emitido em: ') . date('d/m/Y H:i'), 0, 0, 'L');
        $this->Cell(0, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Página ') . $this->PageNo(), 0, 0, 'R');
    }
}

$pdf = new PDF('L', 'mm', 'A4');
$pdf->SetAutoPageBreak(true, 15);
$pdf->AddPage();

// ==============================
// 📐 DIMENSÕES
// ==============================
$pageWidth = 277; // largura útil
$colNome = 70;
$colPerc = 20;

$totalSessoes = count($sessoes);
$colSessao = ($pageWidth - $colNome - $colPerc) / ($totalSessoes ?: 1);

// ==============================
// 📌 INFO DA TURMA
// ==============================
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, txt("Turma: {$turma['nome']}"), 0, 1);

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 6, txt("Responsável: {$turma['responsavel']}"), 0, 1);
$pdf->Cell(0, 6, txt("Total de Sessões: $totalSessoes"), 0, 1);

$pdf->Ln(5);

// ==============================
// 🧱 FUNÇÃO CABEÇALHO TABELA
// ==============================
function cabecalhoTabela($pdf, $sessoes, $colNome, $colSessao, $colPerc) {

    $pdf->SetFont('Arial', 'B', 8);
    $pdf->SetFillColor(200, 200, 200);

    $pdf->Cell($colNome, 7, txt('Participante'), 1, 0, 'L', true);

    foreach ($sessoes as $s) {
        $pdf->Cell($colSessao, 7, date('d/m', strtotime($s['data'])), 1, 0, 'C', true);
    }

    $pdf->Cell($colPerc, 7, '%', 1, 1, 'C', true);

    $pdf->SetFont('Arial', '', 8);
}

// ==============================
// 📊 TABELA
// ==============================
cabecalhoTabela($pdf, $sessoes, $colNome, $colSessao, $colPerc);

$linhaAltura = 6;

foreach ($participantes as $p) {

    // quebra de página
    if ($pdf->GetY() + $linhaAltura > 190) {
        $pdf->AddPage();
        cabecalhoTabela($pdf, $sessoes, $colNome, $colSessao, $colPerc);
    }

    $pdf->Cell($colNome, 6, txt($p['nome']), 1);

    $presente = 0;

    foreach ($sessoes as $s) {

        $status = $presencas[$p['id']][$s['id']] ?? 'falta';

        if ($status == 'presente') {
            $pdf->SetFillColor(40, 167, 69);
            $txtCell = 'P';
            $presente++;
        } elseif ($status == 'justificado') {
            $pdf->SetFillColor(255, 193, 7);
            $txtCell = 'J';
        } else {
            $pdf->SetFillColor(220, 53, 69);
            $txtCell = 'F';
        }

        $pdf->Cell($colSessao, 6, $txtCell, 1, 0, 'C', true);
    }

    $total = count($sessoes);
    $percentual = $total > 0 ? round(($presente / $total) * 100) : 0;

    $pdf->SetFillColor(255,255,255);
    $pdf->Cell($colPerc, 6, $percentual . '%', 1, 1, 'C');
}

// ==============================
// 📈 RESUMO
// ==============================
$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 6, txt('Resumo Geral'), 0, 1);

$pdf->SetFont('Arial', '', 10);

$pdf->Cell(0, 6, txt("Total de Participantes: " . count($participantes)), 0, 1);

// média
$soma = 0;

foreach ($participantes as $p) {

    $presente = 0;

    foreach ($sessoes as $s) {
        if (($presencas[$p['id']][$s['id']] ?? '') == 'presente') {
            $presente++;
        }
    }

    $total = count($sessoes);
    $percentual = $total > 0 ? ($presente / $total) * 100 : 0;

    $soma += $percentual;
}

$media = count($participantes) ? round($soma / count($participantes)) : 0;

$pdf->Cell(0, 6, txt("Média de Frequência da Turma: $media%"), 0, 1);

// ==============================
// 📤 OUTPUT
// ==============================
$pdf->Output("I", "frequencia_turma_$turma_id.pdf");
exit;