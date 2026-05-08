<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/auth.php';

// Proteção: apenas logados buscam
auth();

$q = trim($_GET['q'] ?? '');

if ($q === '') {
    header('Content-Type: application/json');
    echo json_encode([]);
    exit;
}

try {
    // 🔍 BUSCA APENAS EM PARTICIPANTES (Estrutura Nova)
    // Agora buscamos pelo Nome ou pelo Número do Processo
    $sql = $pdo->prepare("
        SELECT 
            p.id, 
            p.nome, 
            p.numero_processo, 
            t.nome AS turma_nome,
            'Participante' AS tipo
        FROM participantes p
        LEFT JOIN turmas t ON t.id = p.turma_id
        WHERE p.nome LIKE :busca 
           OR p.numero_processo LIKE :busca 
        LIMIT 10
    ");
    
    $sql->execute([':busca' => "%$q%"]);
    $resultados = $sql->fetchAll(PDO::FETCH_ASSOC);

    // Retorna JSON
    header('Content-Type: application/json');
    echo json_encode($resultados);

} catch (PDOException $e) {
    // Em caso de erro no SQL, retorna o erro para o console do navegador
    header('Content-Type: application/json', true, 500);
    echo json_encode(['erro' => $e->getMessage()]);
}