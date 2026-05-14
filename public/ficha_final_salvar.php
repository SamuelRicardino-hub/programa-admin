<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/auth.php';

auth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p_id = $_POST['participante_id'] ?? null;

    if (!$p_id) {
        die("ID do participante não informado.");
    }

    // Transforma o array de temas em uma string separada por vírgulas
    $temas = isset($_POST['temas_importantes']) ? implode(', ', $_POST['temas_importantes']) : '';

    try {
        // Query de UPDATE organizada
        $sql = "UPDATE ficha_avaliacao_final SET 
                sentimento_denuncia = :sd,
                motivo_denuncia = :md,
                motivo_dificuldade = :mdi,
                avaliacao_participacao = :ap,
                sentimento_inicio = :si,
                temas_importantes = :ti,
                vantagens_experiencia = :ve,
                desvantagens_experiencia = :de,
                mudanca_relacionamentos = :mrel,
                o_que_mais_gostou = :omg,
                impacto_mudanca_costumes = :imc,
                mudanca_visao_mundo = :mvm,
                relacao_grupo_pensamento = :rgp,
                motivo_recomendacao = :mr
                WHERE participante_id = :id";

        $stmt = $pdo->prepare($sql);
        
        // Execução com o mapeamento correto dos nomes (placeholders)
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
        exit;

    } catch (PDOException $e) {
        // Exibe o erro de forma clara caso algo ainda falhe
        die("Erro ao salvar: " . $e->getMessage());
    }
}