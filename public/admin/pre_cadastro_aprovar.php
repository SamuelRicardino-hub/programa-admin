<?php
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../config/logs.php';

auth();
canAny(['admin', 'atendente']);

$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID não informado");
}

// ==============================
// 🔍 BUSCAR PRÉ-CADASTRO
// ==============================
$stmt = $pdo->prepare("SELECT * FROM pre_cadastros WHERE id = ?");
$stmt->execute([$id]);
$pre = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pre) {
    die("Pré-cadastro não encontrado");
}

// ==============================
// 🚫 EVITAR DUPLICIDADE
// ==============================
if ($pre['status'] == 'aprovado') {
    header("Location: pre_cadastros.php");
    exit;
}

// ==============================
// 🧱 INICIAR TRANSAÇÃO
// ==============================
$pdo->beginTransaction();

$stmt = $pdo->prepare("
    SELECT caso_id FROM participantes WHERE cpf = ?
");
$stmt->execute([$pre['cpf']]);

$existente = $stmt->fetch(PDO::FETCH_ASSOC);

if ($existente) {
    header("Location: caso_detalhes.php?id=" . $existente['caso_id']);
    exit;
}

try {

    // ==============================
    // 1️⃣ CRIAR CASO
    // ==============================
    $pdo->exec("INSERT INTO casos () VALUES ()");
    $caso_id = $pdo->lastInsertId();

    // ==============================
    // 2️⃣ CRIAR PARTICIPANTE (VÍTIMA)
    // ==============================
    $stmt = $pdo->prepare("
        INSERT INTO participantes (
            nome,
            cpf,
            data_nascimento,
            telefone,
            email,
            endereco,
            bairro,
            caso_id,
            tipo
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'vitima')
    ");

    $stmt->execute([
        $pre['nome'],
        $pre['cpf'],
        $pre['data_nascimento'],
        $pre['telefone'],
        $pre['email'],
        $pre['endereco'],
        $pre['bairro'],
        $caso_id
    ]);

    $participante_id = $pdo->lastInsertId();

    // ==============================
    // 3️⃣ ATUALIZAR STATUS
    // ==============================
    $stmt = $pdo->prepare("
        UPDATE pre_cadastros 
        SET status = 'aprovado'
        WHERE id = ?
    ");
    $stmt->execute([$id]);

    // ==============================
    // 🧾 LOG
    // ==============================
    registrarLog(
        $pdo,
        'CREATE',
        'casos',
        $caso_id,
        "Criou caso via aprovação de pré-cadastro ID $id",
        $_SESSION['usuario']['id']
    );

    registrarLog(
        $pdo,
        'CREATE',
        'participantes',
        $participante_id,
        "Criou vítima automaticamente (caso $caso_id)",
        $_SESSION['usuario']['id']
    );

    // ==============================
    // ✅ COMMIT
    // ==============================
    $pdo->commit();

    // ==============================
    // 🚀 REDIRECIONAR PARA O CASO
    // ==============================
    header("Location: caso_detalhes.php?id=" . $caso_id);
    exit;
} catch (Exception $e) {

    $pdo->rollBack();
    die("Erro ao aprovar: " . $e->getMessage());
}
