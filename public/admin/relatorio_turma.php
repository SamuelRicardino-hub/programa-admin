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

// 📊 Buscar turma
$stmt = $pdo->prepare("SELECT nome FROM turmas WHERE id = ?");
$stmt->execute([$turma_id]);
$turma = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$turma) {
    die("Turma não encontrada");
}

// 👥 Buscar participantes
$stmt = $pdo->prepare("
    SELECT nome, cpf, telefone
    FROM participantes
    WHERE turma_id = ?
    ORDER BY nome
");
$stmt->execute([$turma_id]);
$participantes = $stmt->fetchAll(PDO::FETCH_ASSOC);

class PDF extends FPDF {

    function Header() {
        // Título sistema
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 8, 'Prefeitura Municipal de Paracambi', 0, 1, 'C');

        $this->SetFont('Arial', '', 12);
        $this->Cell(0, 8, 'Relatorio de Participantes', 0, 1, 'C');

        $this->Ln(5);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);

        $this->Cell(0, 10, 
            'Gerado em: ' . date('d/m/Y H:i') . 
            ' | Pagina ' . $this->PageNo(),
            0, 0, 'C'
        );
    }
}

$pdf = new PDF();
$pdf->AddPage();

// 📌 Nome da turma
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, 'Turma: ' . $turma['nome'], 0, 1);

// 📊 Total
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 8, 'Total de participantes: ' . count($participantes), 0, 1);

$pdf->Ln(5);

// 📋 Cabeçalho da tabela
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(80, 8, 'Nome', 1);
$pdf->Cell(40, 8, 'CPF', 1);
$pdf->Cell(50, 8, 'Telefone', 1);
$pdf->Ln();

// 📊 Dados
$pdf->SetFont('Arial', '', 10);

foreach ($participantes as $p) {

    // quebra automática de página
    if ($pdf->GetY() > 260) {
        $pdf->AddPage();

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(80, 8, 'Nome', 1);
        $pdf->Cell(40, 8, 'CPF', 1);
        $pdf->Cell(50, 8, 'Telefone', 1);
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 10);
    }

    $pdf->Cell(80, 8, utf8_decode($p['nome']), 1);
    $pdf->Cell(40, 8, $p['cpf'], 1);
    $pdf->Cell(50, 8, $p['telefone'], 1);
    $pdf->Ln();
}

$pdf->Output("relatorio_turma.pdf", "I");
exit;
