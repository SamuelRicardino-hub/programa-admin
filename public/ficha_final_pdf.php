<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../lib/tfpdf/tfpdf.php';

auth();

$participante_id = $_GET['participante_id'] ?? null;
if (!$participante_id) die("Participante não informado");

// PARTICIPANTE
$stmt = $pdo->prepare("SELECT nome FROM participantes WHERE id = ?");
$stmt->execute([$participante_id]);
$p = $stmt->fetch();

if (!$p) die("Participante não encontrado");

// FICHA FINAL
$stmt = $pdo->prepare("SELECT * FROM ficha_avaliacao_final WHERE participante_id = ?");
$stmt->execute([$participante_id]);
$f = $stmt->fetch();

if (!$f) die("Ficha final não encontrada");

// Helper para tratar campos nulos ou vazios
function campo($f, $c) {
    return !empty($f[$c]) ? $f[$c] : '-';
}

// Inicializa o PDF
$pdf = new tFPDF();
$pdf->AddPage();

// Configuração das Fontes UTF-8
$pdf->AddFont('DejaVu','','DejaVuSans.ttf', true);
$pdf->AddFont('DejaVu','B','DejaVuSans-Bold.ttf', true);

// ===== LOGOS (Caminhos Corrigidos) =====
$logoPrefeitura = __DIR__ . '/../assets/LogoPrefeitura.png';
$logoProjeto    = __DIR__ . '/../assets/ProjetoSER.jpg';

if (file_exists($logoPrefeitura)) {
    $pdf->Image($logoPrefeitura, 8, 10, 45);
}

if (file_exists($logoProjeto)) {
    $pdf->Image($logoProjeto, 170, 10, 30);
}

// ===== CABEÇALHO OFICIAL =====
// ===== CABEÇALHO OFICIAL =====
$pdf->SetFont('DejaVu','B',9);
$pdf->Cell(0,5,'ESTADO DO RIO DE JANEIRO',0,1,'C');
$pdf->Cell(0,5,'PREFEITURA MUNICIPAL DE PARACAMBI',0,1,'C');
$pdf->SetFont('DejaVu','B',9);
$pdf->Cell(0,5,'SECRETARIA MUNICIPAL DE PROTEÇÃO E POLÍTICA PARA A MULHER',0,1,'C');
$pdf->SetFont('DejaVu','B',9);
$pdf->Cell(0,5,'PROJETO S.E.R. – GRUPO REFLEXIVO PARA HOMENS',0,1,'C');


$pdf->Ln(10);
$pdf->SetFont('DejaVu', 'B', 12);
$pdf->Cell(0,6, 'FICHA DE AVALIAÇÃO FINAL', 0, 1, 'C');
$pdf->SetFont('DejaVu', '', 9);
$pdf->Cell(0,4, 'Relatório de Conclusão e Impacto de Linha de Cuidado', 0, 1, 'C');
$pdf->Ln(6);

// ===== IDENTIFICAÇÃO DO PARTICIPANTE =====
$pdf->SetFont('DejaVu','B',10);
$pdf->SetFillColor(233, 236, 239);
$pdf->Cell(0,6,'IDENTIFICAÇÃO',0,1,'L',true);
$pdf->Ln(2);

$pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Participante: '); 
$pdf->SetFont('DejaVu','',9); $pdf->Write(6, mb_strtoupper($p['nome'], 'UTF-8')."\n");
$pdf->Ln(4);

// ===== SEÇÃO 1: PERCEPÇÃO SOBRE A DENÚNCIA =====
$pdf->SetFont('DejaVu','B',10);
$pdf->Cell(0,6,'1. PERCEPÇÃO DO PROCESSO E DA DENÚNCIA',0,1,'L',true);
$pdf->Ln(2);

$pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Sentimento em relação à denúncia sofrida: '."\n"); 
$pdf->SetFont('DejaVu','',9); $pdf->MultiCell(0,5, campo($f,'sentimento_denuncia'));
$pdf->Ln(2);

$pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Considera a denúncia inicial justa? '); 
$pdf->SetFont('DejaVu','',9); $pdf->Write(6, campo($f,'acha_justa')."\n");

$pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Justificativa/Motivo: '."\n"); 
$pdf->SetFont('DejaVu','',9); $pdf->MultiCell(0,5, campo($f,'motivo_denuncia'));
$pdf->Ln(4);

// ===== SEÇÃO 2: EXPERIÊNCIA NO GRUPO REFLEXIVO =====
$pdf->SetFont('DejaVu','B',10);
$pdf->Cell(0,6,'2. ADESÃO E PARTICIPAÇÃO NO GRUPO',0,1,'L',true);
$pdf->Ln(2);

$pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Encontrou alguma dificuldade para participar dos encontros? '); 
$pdf->SetFont('DejaVu','',9); $pdf->Write(6, campo($f,'dificuldade_participar')."\n");

if ($f['dificuldade_participar'] === 'Sim') {
    $pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Motivo detalhado da dificuldade: '."\n"); 
    $pdf->SetFont('DejaVu','',9); $pdf->MultiCell(0,5, campo($f,'motivo_dificuldade'));
    $pdf->Ln(2);
}

$pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Autoavaliação da sua participação ativa no grupo: '."\n"); 
$pdf->SetFont('DejaVu','',9); $pdf->MultiCell(0,5, campo($f,'avaliacao_participacao'));
$pdf->Ln(4);

// ===== SEÇÃO 3: RESULTADOS E APRENDIZADOS =====
$pdf->SetFont('DejaVu','B',10);
$pdf->Cell(0,6,'3. RESULTADOS E APRENDIZADOS EXTRAÍDOS',0,1,'L',true);
$pdf->Ln(2);

$pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Pontos Positivos apontados pelo participante: '."\n"); 
$pdf->SetFont('DejaVu','',9); $pdf->MultiCell(0,5, campo($f,'pontos_positivos'));
$pdf->Ln(2);

$pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Pontos Negativos/Críticas ao formato: '."\n"); 
$pdf->SetFont('DejaVu','',9); $pdf->MultiCell(0,5, campo($f,'pontos_negativos'));
$pdf->Ln(2);

$pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Quais temas abordados foram considerados mais importantes? '."\n"); 
$pdf->SetFont('DejaVu','',9); $pdf->MultiCell(0,5, campo($f,'temas_importantes'));
$pdf->Ln(4);

// ===== SEÇÃO 4: CONCLUSÃO E IMPACTO SOCIAL =====
$pdf->SetFont('DejaVu','B',10);
$pdf->Cell(0,6,'4. CONCLUSÃO E IMPACTO COMPORTAMENTAL',0,1,'L',true);
$pdf->Ln(2);

$pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Percebeu alguma mudança em suas atitudes ou visão após o grupo? '); 
$pdf->SetFont('DejaVu','',9); $pdf->Write(6, campo($f,'houve_mudanca')."\n");

if ($f['houve_mudanca'] === 'Sim') {
    $pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Descrição das mudanças comportamentais identificadas: '."\n"); 
    $pdf->SetFont('DejaVu','',9); $pdf->MultiCell(0,5, campo($f,'descricao_mudanca'));
    $pdf->Ln(2);
}

$pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Gostou da experiência de integrar o Grupo Reflexivo? '); 
$pdf->SetFont('DejaVu','',9); $pdf->Write(6, campo($f,'gostou_grupo')."\n");

$pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Como avalia o seu estado emocional e cognitivo ao sair do grupo? '."\n"); 
$pdf->SetFont('DejaVu','',9); $pdf->MultiCell(0,5, campo($f,'como_saiu'));
$pdf->Ln(2);

$pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Recomendaria o Projeto S.E.R. para outros homens em situação similar? '); 
$pdf->SetFont('DejaVu','',9); $pdf->Write(6, campo($f,'recomendaria')."\n");

$pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Motivo da recomendação/não recomendação: '."\n"); 
$pdf->SetFont('DejaVu','',9); $pdf->MultiCell(0,5, campo($f,'motivo_recomendacao'));
$pdf->Ln(2);

$pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Sugestões adicionais para o aprimoramento do projeto: '."\n"); 
$pdf->SetFont('DejaVu','',9); $pdf->MultiCell(0,5, campo($f,'sugestoes'));

// ===== BLOCO DE ASSINATURAS =====
$pdf->SetFont('DejaVu','B',8);
$y = $pdf->GetY();
if ($y > 245) {
    $pdf->AddPage();
    $y = $pdf->GetY() + 10;
}
$pdf->Line(15, $y+15, 95, $y+15);
$pdf->Line(115, $y+15, 195, $y+15);

$pdf->SetY($y+17);
$pdf->SetX(15);
$pdf->Cell(80,4,'ASSINATURA DO PARTICIPANTE',0,0,'C');
$pdf->SetX(115);
$pdf->Cell(80,4,'RESPONSÁVEL TÉCNICO / PROJETO S.E.R.',0,1,'C');

// OUTPUT
$pdf->Output('I', 'ficha_final_' . $participante_id . '.pdf');