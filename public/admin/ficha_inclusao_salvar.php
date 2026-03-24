<?php
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../config/logs.php';

auth();
canAny(['admin', 'atendente']);

// 🔒 Função para evitar erro de índice inexistente
function post($campo)
{
    return $_POST[$campo] ?? null;
}

$pre_cadastro_id = post('pre_cadastro_id');

if (!$pre_cadastro_id) {
    die("Pré-cadastro inválido");
}

$sql = $pdo->prepare("
    INSERT INTO ficha_inclusao (
        pre_cadastro_id,
        cor,
        situacao_civil,
        religiao,
        escolaridade,
        renda_familiar,
        ocupacao,
        profissao,
        ocupacao_companheira,
        profissao_companheira,
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
    ) VALUES (
        :pre_cadastro_id,
        :cor,
        :situacao_civil,
        :religiao,
        :escolaridade,
        :renda_familiar,
        :ocupacao,
        :profissao,
        :ocupacao_companheira,
        :profissao_companheira,
        :condicao_moradia,
        :numero_filhos,
        :numero_pessoas_casa,
        :problemas_saude,
        :uso_medicacao,
        :uso_alcool,
        :frequencia_bebida,
        :drogas_utilizadas,
        :violencia_praticada,
        :violencia_sofrida,
        :historico_familiar,
        :situacao_juridica,
        :expectativa_grupo
    )
");

$sql->execute([
    ':pre_cadastro_id' => $pre_cadastro_id,
    ':cor' => post('cor'),
    ':situacao_civil' => post('situacao_civil'),
    ':religiao' => post('religiao'),
    ':escolaridade' => post('escolaridade'),
    ':renda_familiar' => post('renda_familiar'),
    ':ocupacao' => post('ocupacao'),
    ':profissao' => post('profissao'),
    ':ocupacao_companheira' => post('ocupacao_companheira'),
    ':profissao_companheira' => post('profissao_companheira'),
    ':condicao_moradia' => post('condicao_moradia'),
    ':numero_filhos' => post('numero_filhos'),
    ':numero_pessoas_casa' => post('numero_pessoas_casa'),
    ':problemas_saude' => post('problemas_saude'),
    ':uso_medicacao' => post('uso_medicacao'),
    ':uso_alcool' => post('uso_alcool'),
    ':frequencia_bebida' => post('frequencia_bebida'),
    ':drogas_utilizadas' => post('drogas_utilizadas'),
    ':violencia_praticada' => post('violencia_praticada'),
    ':violencia_sofrida' => post('violencia_sofrida'),
    ':historico_familiar' => post('historico_familiar'),
    ':situacao_juridica' => post('situacao_juridica'),
    ':expectativa_grupo' => post('expectativa_grupo')
]);

// 📜 LOG
registrarLog(
    $pdo,
    'CREATE',
    'ficha_inclusao',
    $pdo->lastInsertId(),
    "Criou ficha de inclusão",
    $_SESSION['usuario']['id']
);

header("Location: pre_cadastros.php");
exit;
