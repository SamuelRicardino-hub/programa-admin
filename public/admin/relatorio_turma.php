<?php
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../lib/fpdf/fpdf.php';

auth();
canAny(['admin','atendente']);

$turma_id = $_GET['turma_id'] ?? null;

if (!$turma_id) {
    die("Turma não informada");
}

// 📊 Turma
$stmt = $pdo->prepare("SELECT nome FROM turmas WHERE id = ?");
$stmt->execute([$turma_id]);
$turma = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$turma) {
    die("Turma não encontrada");
}

// 👥 Participantes
$stmt = $pdo->prepare("
    SELECT nome, cpf, telefone
    FROM participantes
    WHERE turma_id = ?
    ORDER BY nome
");
$stmt->execute([$turma_id]);
$participantes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 📄 PDF
class PDF extends FPDF {

    function Header() {

        // LOGO
        $this->Image(__DIR__ . '/../../assets/logo.png', 10, 8, 30);

        $this->SetY(10);

        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 8, utf8_decode('Prefeitura Municipal de Paracambi'), 0, 1, 'C');

        $this->SetFont('Arial', '', 12);
        $this->Cell(0, 8, utf8_decode('Relatório de Participantes'), 0, 1, 'C');

        $this->Ln(5);

        $this->SetDrawColor(200, 200, 200);
        $this->Line(10, 35, 200, 35);

        $this->Ln(5);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);

        $this->Cell(0, 10, 
            utf8_decode('Gerado em: ' . date('d/m/Y H:i') . 
            ' | Página ' . $this->PageNo()),
            0, 0, 'C'
        );
    }
}

$pdf = new PDF();
$pdf->AddPage();

$pdf->Ln(10);

// 📌 Info da turma
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, utf8_decode('Turma: ' . $turma['nome']), 0, 1);

$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 8, 'Total de participantes: ' . count($participantes), 0, 1);

$pdf->Ln(5);

// 🎨 Cabeçalho tabela
$pdf->SetFillColor(0, 102, 204);
$pdf->SetTextColor(255,255,255);
$pdf->SetFont('Arial', 'B', 11);

$pdf->Cell(90, 8, 'Nome', 1, 0, 'C', true);
$pdf->Cell(40, 8, 'CPF', 1, 0, 'C', true);
$pdf->Cell(50, 8, 'Telefone', 1, 1, 'C', true);

$pdf->SetTextColor(0,0,0);
$pdf->SetFont('Arial', '', 10);

$fill = false;

foreach ($participantes as $p) {

    if ($pdf->GetY() > 260) {
        $pdf->AddPage();
        $pdf->Ln(10);

        $pdf->SetFillColor(0, 102, 204);
        $pdf->SetTextColor(255,255,255);
        $pdf->SetFont('Arial', 'B', 11);

        $pdf->Cell(90, 8, 'Nome', 1, 0, 'C', true);
        $pdf->Cell(40, 8, 'CPF', 1, 0, 'C', true);
        $pdf->Cell(50, 8, 'Telefone', 1, 1, 'C', true);

        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('Arial', '', 10);
    }

    $pdf->SetFillColor($fill ? 240 : 255, $fill ? 240 : 255, $fill ? 240 : 255);

    $x = $pdf->GetX();
    $y = $pdf->GetY();

    $pdf->MultiCell(90, 8, utf8_decode($p['nome']), 1, 'L', true);
    $altura = $pdf->GetY() - $y;

    $pdf->SetXY($x + 90, $y);
    $pdf->Cell(40, $altura, $p['cpf'], 1, 0, 'C', true);
    $pdf->Cell(50, $altura, $p['telefone'], 1, 1, 'C', true);

    $fill = !$fill;
}

$pdf->Output("relatorio_turma.pdf", "I");
exit;