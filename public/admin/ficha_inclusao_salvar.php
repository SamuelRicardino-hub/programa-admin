<?php
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../config/logs.php';

auth();
canAny(['admin','atendente']);

// ==============================
// 🔒 VALIDAR MÉTODO
// ==============================
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: casos_lista.php");
    exit;
}

// ==============================
// 🔒 VALIDAR ID
// ==============================
$participante_id = filter_input(INPUT_POST, 'participante_id', FILTER_VALIDATE_INT);

if (!$participante_id) {
    die("Participante inválido");
}

// ==============================
// 🚫 EVITAR DUPLICIDADE
// ==============================
$stmt = $pdo->prepare("
    SELECT id FROM ficha_inclusao 
    WHERE participante_id = ?
");
$stmt->execute([$participante_id]);

if ($stmt->fetch()) {
    die("Ficha de inclusão já cadastrada para este participante.");
}

// ==============================
// 📥 RECEBER DADOS
// ==============================
function campo($nome) {
    return trim($_POST[$nome] ?? '');
}

$cor                     = campo('cor');
$situacao_civil          = campo('situacao_civil');
$religiao                = campo('religiao');
$escolaridade            = campo('escolaridade');
$profissao               = campo('profissao');
$ocupacao                = campo('ocupacao');
$renda_familiar          = campo('renda_familiar');
$condicao_moradia        = campo('condicao_moradia');
$numero_filhos           = $_POST['numero_filhos'] ?? null;
$numero_pessoas_casa     = $_POST['numero_pessoas_casa'] ?? null;
$problemas_saude         = campo('problemas_saude');
$uso_medicacao           = campo('uso_medicacao');
$uso_alcool              = campo('uso_alcool');
$frequencia_bebida       = campo('frequencia_bebida');
$drogas_utilizadas       = campo('drogas_utilizadas');
$violencia_praticada     = campo('violencia_praticada');
$violencia_sofrida       = campo('violencia_sofrida');
$historico_familiar      = campo('historico_familiar');
$situacao_juridica       = campo('situacao_juridica');
$expectativa_grupo       = campo('expectativa_grupo');

// ==============================
// 💾 INSERT
// ==============================
$stmt = $pdo->prepare("
    INSERT INTO ficha_inclusao (
        participante_id,
        cor,
        situacao_civil,
        religiao,
        escolaridade,
        profissao,
        ocupacao,
        renda_familiar,
        condicao_moradia,
        numero_filhos,
        numero_pessoas_casa,
        problemas_saude,
        uso_medicacao,
        uso_alcool,
        frequencia_bebida,
        drogas_utilizadas,
        violencia_praticada,
        violencia_sofrida,
        historico_familiar,
        situacao_juridica,
        expectativa_grupo
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->execute([
    $participante_id,
    $cor,
    $situacao_civil,
    $religiao,
    $escolaridade,
    $profissao,
    $ocupacao,
    $renda_familiar,
    $condicao_moradia,
    $numero_filhos,
    $numero_pessoas_casa,
    $problemas_saude,
    $uso_medicacao,
    $uso_alcool,
    $frequencia_bebida,
    $drogas_utilizadas,
    $violencia_praticada,
    $violencia_sofrida,
    $historico_familiar,
    $situacao_juridica,
    $expectativa_grupo
]);

$ficha_id = $pdo->lastInsertId();

// ==============================
// 🧾 LOG
// ==============================
registrarLog(
    $pdo,
    'CREATE',
    'ficha_inclusao',
    $ficha_id,
    "Preencheu ficha de inclusão (participante ID $participante_id)",
    $_SESSION['usuario']['id']
);

// ==============================
// 🔍 BUSCAR CASO PARA REDIRECIONAR
// ==============================
$stmt = $pdo->prepare("
    SELECT caso_id FROM participantes WHERE id = ?
");
$stmt->execute([$participante_id]);
$caso = $stmt->fetch(PDO::FETCH_ASSOC);

// ==============================
// 🚀 REDIRECIONAR
// ==============================
header("Location: caso_detalhes.php?id=" . $caso['caso_id']);
exit;