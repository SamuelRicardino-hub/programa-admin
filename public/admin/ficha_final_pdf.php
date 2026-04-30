<?php
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../lib/tfpdf/tfpdf.php';

auth();

$participante_id = $_GET['participante_id'] ?? null;
if (!$participante_id) die("Participante não informado");

// Participante
$stmt = $pdo->prepare("SELECT nome FROM participantes WHERE id = ?");
$stmt->execute([$participante_id]);
$p = $stmt->fetch();

if (!$p) die("Participante não encontrado");

// Ficha final
$stmt = $pdo->prepare("SELECT * FROM ficha_avaliacao_final WHERE participante_id = ?");
$stmt->execute([$participante_id]);
$f = $stmt->fetch();

if (!$f) die("Ficha final não encontrada");

// Função helper
function campo($f, $c) {
    return !empty($f[$c]) ? $f[$c] : '-';
}

// PDF
$pdf = new tFPDF();
$pdf->AddPage();

// Fonte UTF-8
$pdf->AddFont('DejaVu','','DejaVuSans.ttf', true);
$pdf->AddFont('DejaVu','B','DejaVuSans-Bold.ttf', true);

// ===== LOGOS =====
$logoPrefeitura = __DIR__ . '/../../assets/LogoPrefeitura.png';
$logoProjeto    = __DIR__ . '/../../assets/ProjetoSER.jpg';

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

$pdf->SetFont('DejaVu','',11);
$pdf->Cell(0,6,'Secretaria Municipal de Proteção e Política para a Mulher',0,1,'C');

$pdf->SetFont('DejaVu','B',11);
$pdf->Cell(0,6,'Projeto S.E.R. – Grupo Reflexivo para Homens',0,1,'C');

$pdf->Ln(8);

$pdf->SetFont('DejaVu', 'B', 13);
$pdf->Cell(0,8, 'Ficha de Final',0,1, 'C');

$pdf->Ln(5);
// ===== PARTICIPANTE =====
$pdf->SetFont('DejaVu','B',11);
$pdf->Cell(0,8,'Participante: '.$p['nome'],0,1);

$pdf->Ln(5);

// ===== AVALIAÇÃO =====
$pdf->SetFont('DejaVu','B',12);
$pdf->Cell(0,8,'Avaliação',0,1);

$pdf->SetFont('DejaVu','',11);
$pdf->MultiCell(0,6,'Sentimento: '.campo($f,'sentimento_denuncia'));
$pdf->MultiCell(0,6,'Denúncia justa: '.campo($f,'acha_justa'));
$pdf->MultiCell(0,6,'Motivo: '.campo($f,'motivo_denuncia'));

$pdf->MultiCell(0,6,'Dificuldade em participar: '.campo($f,'dificuldade_participar'));
$pdf->MultiCell(0,6,'Motivo da dificuldade: '.campo($f,'motivo_dificuldade'));

$pdf->MultiCell(0,6,'Avaliação da participação: '.campo($f,'avaliacao_participacao'));

$pdf->Ln(4);

// ===== RESULTADOS =====
$pdf->SetFont('DejaVu','B',12);
$pdf->Cell(0,8,'Resultados',0,1);

$pdf->SetFont('DejaVu','',11);
$pdf->MultiCell(0,6,'Pontos positivos: '.campo($f,'pontos_positivos'));
$pdf->MultiCell(0,6,'Pontos negativos: '.campo($f,'pontos_negativos'));
$pdf->MultiCell(0,6,'Temas importantes: '.campo($f,'temas_importantes'));

$pdf->Ln(4);

// ===== CONCLUSÃO =====
$pdf->SetFont('DejaVu','B',12);
$pdf->Cell(0,8,'Conclusão',0,1);

$pdf->SetFont('DejaVu','',11);
$pdf->MultiCell(0,6,'Houve mudança: '.campo($f,'houve_mudanca'));
$pdf->MultiCell(0,6,'Descrição da mudança: '.campo($f,'descricao_mudanca'));
$pdf->MultiCell(0,6,'Gostou do grupo: '.campo($f,'gostou_grupo'));
$pdf->MultiCell(0,6,'Como saiu do grupo: '.campo($f,'como_saiu'));
$pdf->MultiCell(0,6,'Recomendaria: '.campo($f,'recomendaria'));
$pdf->MultiCell(0,6,'Motivo da recomendação: '.campo($f,'motivo_recomendacao'));
$pdf->MultiCell(0,6,'Sugestões: '.campo($f,'sugestoes'));

// ===== OUTPUT =====
$pdf->Output('I', 'ficha_final.pdf');