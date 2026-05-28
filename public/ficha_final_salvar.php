<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/auth.php';
auth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p_id = $_POST['participante_id'] ?? null;
    
    if (!$p_id) die("ID não encontrado");

    // Prepara os temas (checkbox)
    $temas = isset($_POST['temas_importantes']) ? implode(', ', $_POST['temas_importantes']) : '';

    // Verifica se já existe um registro para este participante
    $stmt = $pdo->prepare("SELECT id FROM ficha_avaliacao_final WHERE participante_id = ?");
    $stmt->execute([$p_id]);
    $existe = $stmt->fetch();

    if ($existe) {
        // SQL DE ATUALIZAÇÃO
        $sql = "UPDATE ficha_avaliacao_final SET 
                sentimento_denuncia = :sd, motivo_denuncia = :md, motivo_dificuldade = :mdi,
                avaliacao_participacao = :ap, sentimento_inicio = :si, temas_importantes = :ti,
                vantagens_experiencia = :ve, desvantagens_experiencia = :de, 
                mudanca_relacionamentos = :mrel, o_que_mais_gostou = :omg, 
                impacto_mudanca_costumes = :imc, mudanca_visao_mundo = :mvm, 
                relacao_grupo_pensamento = :rgp, motivo_recomendacao = :mr
                WHERE participante_id = :id";
    } else {
        // SQL DE INSERÇÃO
        $sql = "INSERT INTO ficha_avaliacao_final (
                participante_id, sentimento_denuncia, motivo_denuncia, motivo_dificuldade,
                avaliacao_participacao, sentimento_inicio, temas_importantes,
                vantagens_experiencia, desvantagens_experiencia, mudanca_relacionamentos,
                o_que_mais_gostou, impacto_mudanca_costumes, mudanca_visao_mundo,
                relacao_grupo_pensamento, motivo_recomendacao
                ) VALUES (
                :id, :sd, :md, :mdi, :ap, :si, :ti, :ve, :de, :mrel, :omg, :imc, :mvm, :rgp, :mr
                )";
    }

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':sd'   => $_POST['sentimento_denuncia'] ?? null,
            ':md'   => $_POST['motivo_denuncia'] ?? null,
            ':mdi'  => $_POST['motivo_dificuldade'] ?? null,
            ':ap'   => $_POST['avaliacao_participacao'] ?? null,
            ':si'   => $_POST['sentimento_inicio'] ?? null,
            ':ti'   => $temas,
            ':ve'   => $_POST['vantagens_experiencia'] ?? null,
            ':de'   => $_POST['desvantagens_experiencia'] ?? null,
            ':mrel' => $_POST['mudanca_relacionamentos'] ?? null,
            ':omg'  => $_POST['o_que_mais_gostou'] ?? null,
            ':imc'  => $_POST['impacto_mudanca_costumes'] ?? null,
            ':mvm'  => $_POST['mudanca_visao_mundo'] ?? null,
            ':rgp'  => $_POST['relacao_grupo_pensamento'] ?? null,
            ':mr'   => $_POST['motivo_recomendacao'] ?? null,
            ':id'   => $p_id
        ]);

        header("Location: participantes_detalhes.php?id=$p_id&sucesso=1");
    } catch (PDOException $e) {
        die("Erro ao salvar no banco da prefeitura: " . $e->getMessage());
    }
}