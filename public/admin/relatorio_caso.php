<?php
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../lib/fpdf/fpdf.php';

auth();
canAny(['admin', 'atendente']);

function txt($t) {
    return utf8_decode($t ?? '');
}

// ==============================
// 📄 CLASSE PDF CUSTOM
// ==============================
class PDF extends FPDF {

    function Header() {
        // LOGO
        $this->Image(__DIR__ . '/../../assets/logo.png', 10, 8, 25);

        // TÍTULO
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, txt('PREFEITURA MUNICIPAL'), 0, 1, 'C');

        $this->SetFont('Arial', '', 11);
        $this->Cell(0, 6, txt('Secretaria de Assistência Social'), 0, 1, 'C');

        $this->Ln(5);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', '', 9);

        $this->Cell(0, 5, txt('Emitido em: ') . date('d/m/Y H:i'), 0, 0, 'L');
        $this->Cell(0, 5, txt('Página ') . $this->PageNo(), 0, 0, 'R');
    }
}

// ==============================
// 📥 DADOS
// ==============================
$caso_id = $_GET['id'] ?? null;

if (!$caso_id) die("Caso não informado");

// Caso
$stmt = $pdo->prepare("SELECT * FROM casos WHERE id = ?");
$stmt->execute([$caso_id]);
$caso = $stmt->fetch();

// Participantes
$stmt = $pdo->prepare("SELECT * FROM participantes WHERE caso_id = ?");
$stmt->execute([$caso_id]);
$participantes = $stmt->fetchAll();

$vitima = null;
$autor = null;

foreach ($participantes as $p) {
    if ($p['tipo'] == 'vitima') $vitima = $p;
    if ($p['tipo'] == 'autor') $autor = $p;
}

// Fichas
$stmt = $pdo->prepare("SELECT * FROM ficha_inclusao WHERE participante_id = ?");
$stmt->execute([$vitima['id'] ?? 0]);
$fichaInclusao = $stmt->fetch();

$stmt = $pdo->prepare("SELECT * FROM ficha_avaliacao_final WHERE participante_id = ?");
$stmt->execute([$autor['id'] ?? 0]);
$fichaFinal = $stmt->fetch();

// ==============================
// 🖨️ PDF
// ==============================
$pdf = new PDF();
$pdf->SetAutoPageBreak(true, 15);
$pdf->AddPage();

// ==============================
// 🏷️ TÍTULO
// ==============================
$pdf->SetFont('Arial', 'B', 15);
$pdf->Cell(0, 10, txt("RELATÓRIO DE CASO Nº $caso_id"), 0, 1, 'C');
$pdf->Ln(5);

// ==============================
// 👤 VÍTIMA
// ==============================
if ($vitima) {

    $pdf->SetFillColor(220, 53, 69);
    $pdf->SetTextColor(255);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 8, txt(' DADOS DA VÍTIMA'), 0, 1, 'L', true);

    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial', '', 11);

    $pdf->Cell(0, 6, txt("Nome: {$vitima['nome']}"), 0, 1);
    $pdf->Cell(0, 6, txt("CPF: {$vitima['cpf']}"), 0, 1);
    $pdf->Cell(0, 6, txt("Telefone: {$vitima['telefone']}"), 0, 1);
    $pdf->Ln(3);

    if ($fichaInclusao) {

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(0, 6, txt('Ficha de Inclusão'), 0, 1);

        $pdf->SetFont('Arial', '', 10);

        $pdf->MultiCell(0, 5, txt("Situação familiar: " . ($fichaInclusao['situacao_familiar'] ?? '')));
        $pdf->MultiCell(0, 5, txt("Saúde: " . ($fichaInclusao['saude'] ?? '')));
        $pdf->MultiCell(0, 5, txt("Uso de substâncias: " . ($fichaInclusao['uso_substancias'] ?? '')));
        $pdf->MultiCell(0, 5, txt("Histórico de violência: " . ($fichaInclusao['historico_violencia'] ?? '')));
        $pdf->Ln(3);
    }
}

// ==============================
// 👤 AUTOR
// ==============================
if ($autor) {

    $pdf->SetFillColor(33, 37, 41);
    $pdf->SetTextColor(255);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 8, txt(' DADOS DO AUTOR'), 0, 1, 'L', true);

    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial', '', 11);

    $pdf->Cell(0, 6, txt("Nome: {$autor['nome']}"), 0, 1);
    $pdf->Cell(0, 6, txt("CPF: {$autor['cpf']}"), 0, 1);
    $pdf->Cell(0, 6, txt("Telefone: {$autor['telefone']}"), 0, 1);
    $pdf->Ln(3);

    if ($fichaFinal) {

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(0, 6, txt('Ficha de Avaliação Final'), 0, 1);

        $pdf->SetFont('Arial', '', 10);

        $pdf->MultiCell(0, 5, txt("Sentimento sobre denúncia: " . ($fichaFinal['sentimento_denuncia'] ?? '')));
        $pdf->MultiCell(0, 5, txt("Considera justa: " . ($fichaFinal['acha_justa'] ?? '')));
        $pdf->MultiCell(0, 5, txt("Mudança de comportamento: " . ($fichaFinal['houve_mudanca'] ?? '')));
        $pdf->MultiCell(0, 5, txt("Descrição da mudança: " . ($fichaFinal['descricao_mudanca'] ?? '')));
        $pdf->MultiCell(0, 5, txt("Impacto nos relacionamentos: " . ($fichaFinal['impacto_relacionamentos'] ?? '')));
        $pdf->MultiCell(0, 5, txt("Recomendaria o programa: " . ($fichaFinal['recomendaria'] ?? '')));
    }
}

// ==============================
// 📤 OUTPUT
// ==============================
$pdf->Output("I", "caso_$caso_id.pdf");
exit;