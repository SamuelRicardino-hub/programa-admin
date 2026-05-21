<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../lib/tfpdf/tfpdf.php';

auth();

$participante_id = $_GET['participante_id'] ?? null;
if (!$participante_id) die("Participante não informado");

// PARTICIPANTE
$stmt = $pdo->prepare("SELECT nome, numero_processo FROM participantes WHERE id = ?");
$stmt->execute([$participante_id]);
$p = $stmt->fetch();

if (!$p) die("Participante não encontrado");

// FICHA
$stmt = $pdo->prepare("SELECT * FROM ficha_inclusao WHERE participante_id = ?");
$stmt->execute([$participante_id]);
$f = $stmt->fetch();

if (!$f) die("Ficha de inclusão não encontrada");

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

// ===== LOGOS =====
$logoPrefeitura = __DIR__ . '/../assets/LogoPrefeitura.png';
$logoProjeto    = __DIR__ . '/../assets/ProjetoSER.jpg';

if (file_exists($logoPrefeitura)) {
    $pdf->Image($logoPrefeitura, 10, 10, 35);
}

if (file_exists($logoProjeto)) {
    $pdf->Image($logoProjeto, 165, 10, 35);
}

// ===== CABEÇALHO OFICIAL =====
$pdf->SetFont('DejaVu','B',11);
$pdf->Cell(0,5,'ESTADO DO RIO DE JANEIRO',0,1,'C');
$pdf->Cell(0,5,'PREFEITURA MUNICIPAL DE PARACAMBI',0,1,'C');
$pdf->SetFont('DejaVu','',9);
$pdf->Cell(0,5,'SECRETARIA MUNICIPAL DE PROTEÇÃO E POLÍTICA PARA A MULHER',0,1,'C');
$pdf->SetFont('DejaVu','B',10);
$pdf->Cell(0,5,'PROJETO S.E.R. – GRUPO REFLEXIVO PARA HOMENS',0,1,'C');

$pdf->Ln(10);
$pdf->SetFont('DejaVu', 'B', 12);
$pdf->Cell(0,6, 'FICHA DE INCLUSÃO TÉCNICA', 0, 1, 'C');
$pdf->SetFont('DejaVu', '', 9);
$pdf->Cell(0,4, 'Registro Geral de Identificação Psicossocial', 0, 1, 'C');
$pdf->Ln(6);

// ===== SEÇÃO 1: CONTROLE INTERNO E PROCESSUAL =====
$pdf->SetFont('DejaVu','B',10);
$pdf->SetFillColor(233, 236, 239);
$pdf->Cell(0,6,'1. CONTROLE INTERNO E PROCESSUAL',0,1,'L',true);
$pdf->Ln(2);

$pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Participante: '); 
$pdf->SetFont('DejaVu','',9); $pdf->Write(6, mb_strtoupper($p['nome'], 'UTF-8')."\n");

$pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Nº do Caso (Interno): '); 
$pdf->SetFont('DejaVu','',9); $pdf->Write(6, campo($f,'numero_caso')."        ");
$pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Nº do Processo (TJ): '); 
$pdf->SetFont('DejaVu','',9); $pdf->Write(6, ($p['numero_processo'] ?: '-')."\n");
$pdf->Ln(3);

// ===== SEÇÃO 2: PERFIL SOCIOBIOGRÁFICO =====
$pdf->SetFont('DejaVu','B',10);
$pdf->Cell(0,6,'2. PERFIL SOCIOBIOGRÁFICO',0,1,'L',true);
$pdf->Ln(2);

$pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Grau de Parentesco com a Denunciante: '); 
$pdf->SetFont('DejaVu','',9); $pdf->Write(6, campo($f,'parentesco_denunciante')."\n");

$pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Cor/Raça: '); 
$pdf->SetFont('DejaVu','',9); $pdf->Write(6, campo($f,'cor')."        ");
$pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Naturalidade: '); 
$pdf->SetFont('DejaVu','',9); $pdf->Write(6, campo($f,'naturalidade')."\n");

$pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Religião: '); 
$pdf->SetFont('DejaVu','',9); $pdf->Write(6, campo($f,'religiao')."        ");
$pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Escolaridade: '); 
$pdf->SetFont('DejaVu','',9); $pdf->Write(6, campo($f,'escolaridade')."\n");

$pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Estado Civil / Relacionamento Atual: '); 
$pdf->SetFont('DejaVu','',9); $pdf->Write(6, campo($f,'relationship_atual' ?? 'relacionamento_atual')."\n");

// Condicional de relacionamento (Oculta se for Solteiro)
if ($f['relacionamento_atual'] !== 'Solteiro') {
    $pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Mantém relação com: '); 
    $pdf->SetFont('DejaVu','',9); $pdf->Write(6, campo($f,'relacionamento_amoroso_detalhe')."\n");
    $pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Como qualifica a relação com a parceira atual? '); 
    $pdf->SetFont('DejaVu','',9); $pdf->Write(6, campo($f,'relacionamento_parceira_atual')."\n");
}
$pdf->Ln(3);

// ===== SEÇÃO 3: SITUAÇÃO ECONÔMICA E MORADIA =====
$pdf->SetFont('DejaVu','B',10);
$pdf->Cell(0,6,'3. SITUAÇÃO ECONÔMICA E MORADIA',0,1,'L',true);
$pdf->Ln(2);

$pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Renda Familiar: '); 
$pdf->SetFont('DejaVu','',9); $pdf->Write(6, campo($f,'renda_familiar')."        ");
$pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Vínculo de Trabalho/Ocupação: '); 
$pdf->SetFont('DejaVu','',9); $pdf->Write(6, campo($f,'trabalho_ocupacao')."\n");

$pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Profissão Atual/Última: '); 
$pdf->SetFont('DejaVu','',9); $pdf->Write(6, campo($f,'profissao')."\n");
$pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Condição de Moradia: '); 
$pdf->SetFont('DejaVu','',9); $pdf->Write(6, campo($f,'condicao_moradia')."\n");
$pdf->Ln(3);

// ===== SEÇÃO 4: ESTRUTURA FAMILIAR E PATERNIDADE =====
$pdf->SetFont('DejaVu','B',10);
$pdf->Cell(0,6,'4. ESTRUTURA FAMILIAR E PATERNIDADE',0,1,'L',true);
$pdf->Ln(2);

$pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Quantidade de Filhos: '); 
$pdf->SetFont('DejaVu','',9); $pdf->Write(6, campo($f,'qtd_filhos')."        ");
$pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Pessoas residindo na mesma casa: '); 
$pdf->SetFont('DejaVu','',9); $pdf->Write(6, campo($f,'pessoas_na_casa')."\n");
$pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Divide tarefas domésticas? '); 
$pdf->SetFont('DejaVu','',9); $pdf->Write(6, campo($f,'divisao_domestica')."\n");

// Condicional de Filhos (Oculta se não tiver)
if ((int)$f['qtd_filhos'] > 0) {
    $pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Filhos com a atual companheira: '); 
    $pdf->SetFont('DejaVu','',9); $pdf->Write(6, campo($f,'filhos_com_atual')."        ");
    $pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Filhos com a denunciante: '); 
    $pdf->SetFont('DejaVu','',9); $pdf->Write(6, campo($f,'filhos_com_denunciante')."\n");
    
    $pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Frequência de contato com os filhos (fora da residência): '); 
    $pdf->SetFont('DejaVu','',9); $pdf->Write(6, campo($f,'frequencia_ver_filhos')."\n");
    
    $pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Conversam sobre a criação dos filhos? '); 
    $pdf->SetFont('DejaVu','',9); $pdf->Write(6, campo($f,'conversa_criacao_filhos')."    ");
    $pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Auxilia nas lições de casa? '); 
    $pdf->SetFont('DejaVu','',9); $pdf->Write(6, campo($f,'auxilio_licoes_casa')."\n");
    
    $pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Frequenta as reuniões escolares de pais? '); 
    $pdf->SetFont('DejaVu','',9); $pdf->Write(6, campo($f,'reunioes_escola')."\n");
}
$pdf->Ln(3);

// ===== SEÇÃO 5: CONDIÇÕES DE SAÚDE E HÁBITOS =====
$pdf->SetFont('DejaVu','B',10);
$pdf->Cell(0,6,'5. CONDIÇÕES DE SAÚDE E HÁBITOS',0,1,'L',true);
$pdf->Ln(2);

$pdf->SetFont('DejaVu','B',9); $pdf->Cell(0,5,'Apresenta problemas de saúde?',0,1);
$pdf->SetFont('DejaVu','',9); $pdf->MultiCell(0,5, campo($f,'problemas_saude'));

$pdf->SetFont('DejaVu','B',9); $pdf->Cell(0,5,'Faz uso de alguma medicação contínua?',0,1);
$pdf->SetFont('DejaVu','',9); $pdf->MultiCell(0,5, campo($f,'medicacao'));

$pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Frequência que frequenta bares: '); 
$pdf->SetFont('DejaVu','',9); $pdf->Write(6, campo($f,'frequencia_bares')."\n");

$pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Bebidas de consumo comum (Checkboxes): '); 
$pdf->SetFont('DejaVu','',9); $pdf->Write(6, campo($f,'bebidas_comuns')."\n");

$pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Substâncias/Drogas já utilizadas (Checkboxes): '); 
$pdf->SetFont('DejaVu','',9); $pdf->Write(6, campo($f,'drogas_utilizadas')."\n");
$pdf->Ln(3);

// ===== SEÇÃO 6: HISTÓRICO DE VIOLÊNCIA =====
$pdf->SetFont('DejaVu','B',10);
$pdf->Cell(0,6,'6. HISTÓRICO DE VIOLÊNCIA',0,1,'L',true);
$pdf->Ln(2);

$pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Durante o último ano praticou algum tipo de violência? '); 
$pdf->SetFont('DejaVu','',9); $pdf->Write(6, campo($f,'praticou_violencia_ultimo_ano')."\n");

if ($f['praticou_violencia_ultimo_ano'] !== 'Não' && !empty($f['praticou_violencia_ultimo_ano'])) {
    $pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Em quem praticou a violência: '); 
    $pdf->SetFont('DejaVu','',9); $pdf->Write(6, campo($f,'violencia_em_quem')."\n");
    $pdf->SetFont('DejaVu','B',9); $pdf->Cell(0,5,'Tipologias de violência praticadas:',0,1);
    $pdf->SetFont('DejaVu','',9); $pdf->MultiCell(0,5, campo($f,'tipo_violencia_praticada'));
}

$pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Na infância, a figura do pai era presente? '); 
$pdf->SetFont('DejaVu','',9); $pdf->Write(6, campo($f,'pai_presente_infancia')."\n");
$pdf->SetFont('DejaVu','B',9); $pdf->Cell(0,5,'Houve cenários ou conflitos violentos em sua família de origem?',0,1);
$pdf->SetFont('DejaVu','',9); $pdf->MultiCell(0,5, campo($f,'conflitos_infancia'));

$pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Já sofreu algum tipo de agressão por parte da companheira? '); 
$pdf->SetFont('DejaVu','',9); $pdf->Write(6, campo($f,'ja_foi_agredido_companheira')."\n");

if ($f['ja_foi_agredido_companheira'] === 'Sim') {
    $pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Natureza da violência sofrida: '); 
    $pdf->SetFont('DejaVu','',9); $pdf->Write(6, campo($f,'tipo_violencia_sofrida')."\n");
    $pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Prestou denúncia? Motivo: '); 
    $pdf->SetFont('DejaVu','',9); $pdf->Write(6, campo($f,'denunciou_motivo')."\n");
}
$pdf->Ln(3);

// ===== SEÇÃO 7: SITUAÇÃO JURÍDICA E HISTÓRICO PENAL =====
$pdf->SetFont('DejaVu','B',10);
$pdf->Cell(0,6,'7. SITUAÇÃO JURÍDICA E HISTÓRICO PENAL',0,1,'L',true);
$pdf->Ln(2);

$pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Já respondeu criminalmente/indiciado por violência contra mulher antes? '); 
$pdf->SetFont('DejaVu','',9); $pdf->Write(6, campo($f,'indiciado_anteriormente')."\n");

if ($f['indiciado_anteriormente'] === 'Sim') {
    $pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Tipificação criminal anterior: '); 
    $pdf->SetFont('DejaVu','',9); $pdf->Write(6, campo($f,'tipo_violencia_anterior')."\n");
}

$pdf->SetFont('DejaVu','B',9); $pdf->Write(6,'Fez uso de bebidas/drogas antes de cometer a infração atual? '); 
$pdf->SetFont('DejaVu','',9); $pdf->Write(6, campo($f,'uso_drogas_antes_fato')."\n");

$pdf->SetFont('DejaVu','B',9); $pdf->Cell(0,5,'Já foi indiciado por outro teor criminal (exceto contexto Maria da Penha)?',0,1);
$pdf->SetFont('DejaVu','',9); $pdf->MultiCell(0,5, campo($f,'indiciado_outro_motivo'));

$pdf->SetFont('DejaVu','B',9); $pdf->Cell(0,5,'Histórico prisional ou cumprimento de medidas socioeducativas:',0,1);
$pdf->SetFont('DejaVu','',9); $pdf->MultiCell(0,5, campo($f,'historico_prisao'));

$pdf->Ln(15);

// ===== BLOCO DE ASSINATURAS =====
$pdf->SetFont('DejaVu','B',8);
$y = $pdf->GetY();
if ($y > 250) { // Se estiver muito no fim da página, joga para a próxima
    $pdf->AddPage();
    $y = $pdf->GetY() + 10;
}
$pdf->Line(15, $y+10, 95, $y+10);
$pdf->Line(115, $y+10, 195, $y+10);

$pdf->SetY($y+12);
$pdf->SetX(15);
$pdf->Cell(80,4,'ASSINATURA DO PARTICIPANTE',0,0,'C');
$pdf->SetX(115);
$pdf->Cell(80,4,'RESPONSÁVEL TÉCNICO / PROJETO S.E.R.',0,1,'C');

// OUTPUT
$pdf->Output('I', 'ficha_inclusao_' . $participante_id . '.pdf');