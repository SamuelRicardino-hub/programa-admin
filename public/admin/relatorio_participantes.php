<?php
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../lib/fpdf/fpdf.php';

auth();
canAny(['admin','atendente']);

$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID inválido");
}

$sql = $pdo->prepare("
    SELECT p.*, t.nome AS turma_nome, t.responsavel
    FROM participantes p
    LEFT JOIN turmas t ON t.id = p.turma_id
    WHERE p.id = ?
");
$sql->execute([$id]);
$p = $sql->fetch(PDO::FETCH_ASSOC);

if (!$p) {
    die("Participante não encontrado");
}

class PDF extends FPDF {

    function Header() {
        $this->Image(__DIR__ . '/../../assets/logo.png', 10, 8, 30);

        $this->SetY(10);

        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 8, utf8_decode('Prefeitura Municipal de Paracambi'), 0, 1, 'C');

        $this->SetFont('Arial', '', 12);
        $this->Cell(0, 8, utf8_decode('Ficha do Participante'), 0, 1, 'C');

        $this->Ln(5);

        $this->SetDrawColor(200, 200, 200);
        $this->Line(10, 35, 200, 35);

        $this->Ln(5);
    }

    function box($titulo, $conteudo) {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 8, utf8_decode($titulo), 0, 1);

        $this->SetFont('Arial', '', 11);
        $this->MultiCell(0, 6, utf8_decode($conteudo));

        $this->Ln(3);
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

// 👤 Dados
$pdf->box("Dados Pessoais",
    "Nome: {$p['nome']}\n" .
    "CPF: {$p['cpf']}\n" .
    "Email: {$p['email']}\n" .
    "Telefone: {$p['telefone']}\n" .
    "Nascimento: " . date('d/m/Y', strtotime($p['data_nascimento']))
);

// 📍 Endereço
$pdf->box("Endereço",
    "Endereço: {$p['endereco']}\n" .
    "Bairro: {$p['bairro']}"
);

// 🏫 Turma
$pdf->box("Turma",
    "Nome: " . ($p['turma_nome'] ?? 'Não vinculada') . "\n" .
    "Responsável: " . ($p['responsavel'] ?? '-')
);

// 📝 Observações
if (!empty($p['observacoes'])) {
    $pdf->box("Observações", $p['observacoes']);
}

$pdf->Output("participante.pdf", "I");
exit;