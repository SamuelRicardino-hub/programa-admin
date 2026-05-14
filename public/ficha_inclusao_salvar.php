<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/auth.php';

auth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Acesso inválido.");
}

$dados = $_POST;
$participante_id = $dados['participante_id'] ?? null;

if (!$participante_id) {
    die("ID do participante não informado.");
}

try {
    // 1. Verificamos se já existe uma ficha para decidir entre INSERT ou UPDATE
    $check = $pdo->prepare("SELECT id FROM ficha_inclusao WHERE participante_id = ?");
    $check->execute([$participante_id]);
    $ficha_existente = $check->fetch();

    // 2. Preparação dos campos "Outro" 
    // (Se o usuário selecionou 'Outro', usamos o valor do campo de texto)
    $religiao = ($dados['religiao'] == 'Outra') ? 'Outra' : $dados['religiao'];
    $escolaridade = ($dados['escolaridade'] == 'Outro') ? 'Outro' : $dados['escolaridade'];
    $trabalho = ($dados['trabalho'] == 'Outro') ? 'Outro' : $dados['trabalho'];
    $moradia = ($dados['moradia'] == 'Outro') ? 'Outro' : $dados['moradia'];

    if ($ficha_existente) {
        // SQL DE ATUALIZAÇÃO (Nomes baseados no seu SQL Dump)
        $sql = "UPDATE ficha_inclusao SET 
                numero_caso = ?, 
                numero_processo = ?, 
                nome = ?, 
                parentesco = ?, 
                idade = ?, 
                naturalidade = ?, 
                cor = ?, 
                relacionamento_atual = ?, 
                relacionamento_detalhe = ?, 
                religiao = ?, 
                religiao_outro = ?,
                escolaridade = ?, 
                escolaridade_outro = ?,
                renda_familiar = ?, 
                renda_outro = ?,
                trabalho = ?, 
                trabalho_outro = ?,
                profissao = ?, 
                condicao_moradia = ?,
                moradia_outro = ?,
                relacionamento_outro = ?
                WHERE participante_id = ?";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $dados['numero_caso'],
            $dados['numero_processo'],
            $dados['nome'], // No seu banco a coluna é 'nome', não 'nome_completo'
            $dados['parentesco'],
            $dados['idade'],
            $dados['naturalidade'],
            $dados['cor'],
            $dados['relacionamento'], // Vem do select 'relacionamento'
            $dados['relacao_com'],    // Vem do select 'relacao_com'
            $religiao,
            $dados['religiao_outro'] ?? null,
            $escolaridade,
            $dados['escolaridade_outro'] ?? null,
            $dados['renda'],
            $dados['renda_outro'] ?? null,
            $trabalho,
            $dados['trabalho_outro'] ?? null,
            $dados['profissao'],
            $moradia,
            $dados['moradia_outro'] ?? null,
            $dados['relacionamento_outro'] ?? null,
            $participante_id
        ]);
    } else {
        // SQL DE INSERÇÃO (Caso ainda não exista a ficha)
        $sql = "INSERT INTO ficha_inclusao (
            participante_id, numero_caso, numero_processo, nome, parentesco, idade, 
            naturalidade, cor, relacionamento_atual, relacionamento_detalhe, 
            religiao, religiao_outro, escolaridade, escolaridade_outro, 
            renda_familiar, renda_outro, trabalho, trabalho_outro, 
            profissao, condicao_moradia, moradia_outro, relacionamento_outro
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $participante_id,
            $dados['numero_caso'],
            $dados['numero_processo'],
            $dados['nome'],
            $dados['parentesco'],
            $dados['idade'],
            $dados['naturalidade'],
            $dados['cor'],
            $dados['relacionamento'],
            $dados['relacao_com'],
            $religiao,
            $dados['religiao_outro'] ?? null,
            $escolaridade,
            $dados['escolaridade_outro'] ?? null,
            $dados['renda'],
            $dados['renda_outro'] ?? null,
            $trabalho,
            $dados['trabalho_outro'] ?? null,
            $dados['profissao'],
            $moradia,
            $dados['moradia_outro'] ?? null,
            $dados['relacionamento_outro'] ?? null
        ]);
    }

    header("Location: participantes_detalhes.php?id=" . $participante_id . "&sucesso=1");
    exit;

} catch (PDOException $e) {
    die("Erro ao salvar no Banco de Dados: " . $e->getMessage());
}