<?php
require_once __DIR__ . '/../../config/conexao.php';

$q = trim($_GET['q'] ?? '');

if ($q === '') {
    echo json_encode([]);
    exit;
}

// 🔍 PARTICIPANTES
$sql1 = $pdo->prepare("
    SELECT id, nome, cpf, email, 'Participante' AS tipo
    FROM participantes
    WHERE nome LIKE :busca 
       OR cpf LIKE :busca 
       OR email LIKE :busca
");
$sql1->execute([':busca' => "%$q%"]);
$participantes = $sql1->fetchAll(PDO::FETCH_ASSOC);

// 🔍 PRÉ-CADASTROS (SÓ PENDENTE)
$sql2 = $pdo->prepare("
    SELECT id, nome, cpf, email, 'Pré-cadastro' AS tipo
    FROM pre_cadastros
    WHERE status = 'pendente'
    AND (
        nome LIKE :busca 
        OR cpf LIKE :busca 
        OR email LIKE :busca
    )
");
$sql2->execute([':busca' => "%$q%"]);
$precadastros = $sql2->fetchAll(PDO::FETCH_ASSOC);

// Junta tudo
$resultados = array_merge($participantes, $precadastros);

// Retorna JSON
header('Content-Type: application/json');
echo json_encode($resultados);