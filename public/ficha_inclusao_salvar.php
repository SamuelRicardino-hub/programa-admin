<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/auth.php';
auth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['participante_id'] ?? null;

    if (!$id) {
        die("ID do participante não fornecido.");
    }

    // --- TRATAMENTO DE CAMPOS MULTI-SELEÇÃO (CHECKBOXES) ---
    
    // Bebidas
    $bebidas = isset($_POST['bebidas']) ? implode(', ', $_POST['bebidas']) : '';
    if (!empty($_POST['bebida_outro'])) {
        $bebidas .= (empty($bebidas) ? '' : ', ') . $_POST['bebida_outro'];
    }

    // Drogas
    $drogas = isset($_POST['drogas']) ? implode(', ', $_POST['drogas']) : '';
    if (!empty($_POST['droga_outro'])) {
        $drogas .= (empty($drogas) ? '' : ', ') . $_POST['droga_outro'];
    }

    // Vítimas da Violência Praticada
    $vitimas = isset($_POST['vitimas']) ? implode(', ', $_POST['vitimas']) : '';
    if (!empty($_POST['vitima_outro'])) {
        $vitimas .= (empty($vitimas) ? '' : ', ') . $_POST['vitima_outro'];
    }

    // Tipos de Violência Praticada
    $tipos_v = isset($_POST['tipos_v_praticada']) ? implode(', ', $_POST['tipos_v_praticada']) : '';
    if (!empty($_POST['tipo_v_outro'])) {
        $tipos_v .= (empty($tipos_v) ? '' : ', ') . $_POST['tipo_v_outro'];
    }

    // --- CAPTURA DOS DEMAIS CAMPOS ---
    
    $dados = [
        'participante_id'               => $id,
        'numero_caso'                   => $_POST['numero_caso'] ?? '',
        'parentesco_denunciante'        => $_POST['parentesco'] ?? '',
        'naturalidade'                  => $_POST['naturalidade'] ?? '',
        'cor'                           => $_POST['cor'] ?? '',
        'religiao'                      => ($_POST['religiao'] === 'Outra') ? $_POST['religiao_outro'] : $_POST['religiao'],
        'relacionamento_atual'          => ($_POST['relacionamento'] === 'Outro') ? $_POST['relacionamento_outro'] : $_POST['relacionamento'],
        'relacionamento_amoroso_detalhe'=> $_POST['relacao_com'] ?? '',
        'escolaridade'                  => ($_POST['escolaridade'] === 'Outro') ? $_POST['escolaridade_outro'] : $_POST['escolaridade'],
        'renda_familiar'                => ($_POST['renda'] === 'Outro') ? $_POST['renda_outro'] : $_POST['renda'],
        'trabalho_ocupacao'             => ($_POST['trabalho'] === 'Outro') ? $_POST['trabalho_outro'] : $_POST['trabalho'],
        'profissao'                     => $_POST['profissao'] ?? '',
        'condicao_moradia'              => ($_POST['moradia'] === 'Outro') ? $_POST['moradia_outro'] : $_POST['moradia'],
        'qtd_filhos'                    => (int)$_POST['qtd_filhos'],
        'pessoas_na_casa'               => (int)$_POST['pessoas_na_casa'],
        'filhos_com_atual'              => $_POST['filhos_com_atual'] ?? '',
        'filhos_com_denunciante'        => $_POST['filhos_com_denunciante'] ?? '',
        'frequencia_ver_filhos'         => $_POST['frequencia_ver_filhos'] ?? '',
        'conversa_criacao_filhos'       => $_POST['conversa_criacao_filhos'] ?? '',
        'auxilio_licoes_casa'           => $_POST['auxilio_licoes_casa'] ?? '',
        'reunioes_escola'               => $_POST['reunioes_escola'] ?? '',
        'divisao_domestica'             => $_POST['divisao_domestica'] ?? '',
        'relacionamento_parceira_atual' => $_POST['relacionamento_parceira_atual'] ?? '',
        'problemas_saude'               => $_POST['problemas_saude'] ?? '',
        'medicacao'                     => $_POST['medicacao'] ?? '',
        'frequencia_bares'              => $_POST['frequencia_bares'] ?? '',
        'bebidas_comuns'                => $bebidas,
        'drogas_utilizadas'             => $drogas,
        'praticou_violencia_ultimo_ano' => $_POST['praticou_violencia_ultimo_ano'] ?? '',
        'violencia_em_quem'             => $vitimas,
        'tipo_violencia_praticada'      => $tipos_v,
        'pai_presente_infancia'         => $_POST['pai_presente_infancia'] ?? '',
        'conflitos_infancia'            => $_POST['conflitos_infancia'] ?? '',
        'ja_foi_agredido_companheira'   => $_POST['ja_foi_agredido_companheira'] ?? '',
        'tipo_violencia_sofrida'        => $_POST['tipo_violencia_sofrida'] ?? '',
        'denunciou_motivo'              => $_POST['denunciou_motivo'] ?? '',
        'indiciado_anteriormente'       => $_POST['indiciado_anteriormente'] ?? '',
        'tipo_violencia_anterior'       => $_POST['tipo_violencia_anterior'] ?? '',
        'uso_drogas_antes_fato'         => $_POST['uso_drogas_antes_fato'] ?? '',
        'indiciado_outro_motivo'        => $_POST['indiciado_outro_motivo'] ?? '',
        'historico_prisao'              => $_POST['historico_prisao'] ?? ''
    ];

    try {
        // Verifica se já existe uma ficha para esse participante
        $check = $pdo->prepare("SELECT id FROM ficha_inclusao WHERE participante_id = ?");
        $check->execute([$id]);
        
        if ($check->fetch()) {
            // Se já existe, faz UPDATE
            $sql = "UPDATE ficha_inclusao SET ";
            $campos = [];
            foreach ($dados as $key => $value) {
                if ($key !== 'participante_id') $campos[] = "$key = :$key";
            }
            $sql .= implode(', ', $campos) . " WHERE participante_id = :participante_id";
        } else {
            // Se não existe, faz INSERT
            $cols = implode(', ', array_keys($dados));
            $placeholders = ':' . implode(', :', array_keys($dados));
            $sql = "INSERT INTO ficha_inclusao ($cols) VALUES ($placeholders)";
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($dados);

        header("Location: participantes_detalhes.php?id=$id&status=sucesso");
    } catch (PDOException $e) {
        die("Erro ao salvar os dados: " . $e->getMessage());
    }
} else {
    header("Location: participantes_lista.php");
}