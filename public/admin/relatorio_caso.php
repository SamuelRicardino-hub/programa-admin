<?php
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../lib/fpdf/fpdf.php';

auth();
canAny(['admin', 'atendente']);

$caso_id = $_GET['caso_id'] ?? null;

if (!$caso_id) {
    die("Caso não informado");
}

// 👩 VÍTIMA
$sql = $pdo->prepare("
SELECT p.*, f.*
FROM participantes p
LEFT JOIN ficha_inclusao f ON f.pre_cadastro_id = p.id
WHERE p.caso_id = ? AND p.tipo = 'vitima'
");
$sql->execute([$caso_id]);
$vitima = $sql->fetch(PDO::FETCH_ASSOC);

// 👨 AUTOR
$sql = $pdo->prepare("
SELECT p.*, f.*
FROM participantes p
LEFT JOIN ficha_avaliacao_final f ON f.participante_id = p.id
WHERE p.caso_id = ? AND p.tipo = 'autor'
");
$sql->execute([$caso_id]);
$autor = $sql->fetch(PDO::FETCH_ASSOC);

class PDF extends FPDF
{

    function Header()
    {
        $this->Image(__DIR__ . '/../../assets/logo.png', 10, 8, 25);

        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, 'Prefeitura Municipal de Paracambi', 0, 1, 'C');

        $this->SetFont('Arial', '', 12);
        $this->Cell(0, 8, 'Relatorio do Caso', 0, 1, 'C');

        $this->Ln(5);
    }

    function SectionTitle($title)
    {
        $this->SetFont('Arial', 'B', 12);
        $this->SetFillColor(230, 230, 230);
        $this->Cell(0, 8, $title, 0, 1, 'L', true);
        $this->Ln(2);
    }

    function Field($label, $value)
    {
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(60, 6, $label, 0);
        $this->SetFont('Arial', '', 10);
        $this->MultiCell(0, 6, utf8_decode($value ?: '-'));
    }
}

$pdf = new PDF();
$pdf->AddPage();


// =====================================================
// 👩 VÍTIMA
// =====================================================
if ($vitima) {

    $pdf->SectionTitle('DADOS DA VITIMA');

    $pdf->Field('Nome:', $vitima['nome']);
    $pdf->Field('CPF:', $vitima['cpf']);
    $pdf->Field('Telefone:', $vitima['telefone']);
    $pdf->Field('Email:', $vitima['email']);

    $pdf->Ln(3);

    $pdf->SectionTitle('FICHA DE INCLUSAO');

    $pdf->Field('Cor:', $vitima['cor']);
    $pdf->Field('Estado civil:', $vitima['situacao_civil']);
    $pdf->Field('Escolaridade:', $vitima['escolaridade']);
    $pdf->Field('Profissao:', $vitima['profissao']);
    $pdf->Field('Renda familiar:', $vitima['renda_familiar']);

    $pdf->Field('Problemas de saude:', $vitima['problemas_saude']);
    $pdf->Field('Uso de medicacao:', $vitima['uso_medicacao']);

    $pdf->Field('Uso de alcool:', $vitima['uso_alcool']);
    $pdf->Field('Drogas:', $vitima['drogas_utilizadas']);

    $pdf->Field('Violencia sofrida:', $vitima['violencia_sofrida']);
    $pdf->Field('Historico familiar:', $vitima['historico_familiar']);
}


// =====================================================
// 👨 AUTOR
// =====================================================
if ($autor) {

    $pdf->AddPage();

    $pdf->SectionTitle('DADOS DO AUTOR');

    $pdf->Field('Nome:', $autor['nome']);
    $pdf->Field('CPF:', $autor['cpf']);
    $pdf->Field('Telefone:', $autor['telefone']);
    $pdf->Field('Email:', $autor['email']);

    $pdf->Ln(3);

    $pdf->SectionTitle('FICHA DE AVALIACAO FINAL');

    $pdf->Field('Sentimento denuncia:', $autor['sentimento_denuncia']);
    $pdf->Field('Denuncia justa:', $autor['acha_justa']);
    $pdf->Field('Motivo:', $autor['motivo_denuncia']);

    $pdf->Field('Dificuldade:', $autor['dificuldade_participar']);
    $pdf->Field('Motivo dificuldade:', $autor['motivo_dificuldade']);

    $pdf->Field('Participacao:', $autor['avaliacao_participacao']);

    $pdf->Field('Pontos positivos:', $autor['pontos_positivos']);
    $pdf->Field('Pontos negativos:', $autor['pontos_negativos']);

    $pdf->Field('Mudanca relacao:', $autor['houve_mudanca']);
    $pdf->Field('Descricao:', $autor['descricao_mudanca']);

    $pdf->Field('Impacto:', $autor['impacto_relacionamentos']);
    $pdf->Field('Motivo impacto:', $autor['motivo_impacto']);

    $pdf->Field('Mudou pensamento:', $autor['mudou_pensamento']);
    $pdf->Field('Explicacao:', $autor['explicacao_pensamento']);

    $pdf->Field('Recomendaria:', $autor['recomendaria']);
    $pdf->Field('Motivo:', $autor['motivo_recomendacao']);
}

$pdf->Output("relatorio_caso.pdf", "I");
exit;
