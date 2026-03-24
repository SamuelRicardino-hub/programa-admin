<?php
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../config/logs.php';

auth();
canAny(['admin','atendente']);

// ==============================
// 🔒 VALIDAR ID
// ==============================
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: casos_lista.php");
    exit;
}

// ==============================
// 🔍 BUSCAR CASO
// ==============================
$stmt = $pdo->prepare("SELECT * FROM casos WHERE id = ?");
$stmt->execute([$id]);
$caso = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$caso) {
    die("Caso não encontrado");
}

// ==============================
// 🚫 JÁ ENCERRADO?
// ==============================
if ($caso['status'] === 'encerrado') {
    header("Location: caso_detalhes.php?id=" . $id);
    exit;
}

// ==============================
// 🔍 VALIDAR REGRAS
// ==============================

// 👩 vítima com ficha inclusão
$stmt = $pdo->prepare("
SELECT f.id 
FROM participantes p
LEFT JOIN ficha_inclusao f ON f.pre_cadastro_id = p.id
WHERE p.caso_id = ? AND p.tipo = 'vitima'
");
$stmt->execute([$id]);
$fichaVitima = $stmt->fetch();

// 👨 autor com ficha final
$stmt = $pdo->prepare("
SELECT f.id 
FROM participantes p
LEFT JOIN ficha_avaliacao_final f ON f.participante_id = p.id
WHERE p.caso_id = ? AND p.tipo = 'autor'
");
$stmt->execute([$id]);
$fichaAutor = $stmt->fetch();

// ==============================
// ❌ BLOQUEAR SE NÃO COMPLETO
// ==============================
if (!$fichaVitima || !$fichaAutor) {
    die("Não é possível encerrar. Fichas incompletas.");
}

// ==============================
// 🔄 ATUALIZAR STATUS
// ==============================
$stmt = $pdo->prepare("
UPDATE casos 
SET status = 'encerrado'
WHERE id = ?
");
$stmt->execute([$id]);

// ==============================
// 🧾 LOG
// ==============================
registrarLog(
    $pdo,
    'UPDATE',
    'casos',
    $id,
    "Encerramento do caso",
    $_SESSION['usuario']['id']
);

// ==============================
// 🚀 REDIRECIONAR
// ==============================
header("Location: caso_detalhes.php?id=" . $id);
exit;