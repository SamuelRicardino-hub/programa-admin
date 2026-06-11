<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/logs.php'; 

auth();
can('admin');

require_once __DIR__ . '/../layout/admin_header.php';

$mensagem_sucesso = "";
$mensagem_erro = "";

// Carrega as turmas para o menu de seleção
$stmt_turmas = $pdo->query("SELECT id, nome FROM turmas ORDER BY nome ASC");
$turmas = $stmt_turmas->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['submeter'])) {
    $turma_id = !empty($_POST['turma_id']) ? (int)$_POST['turma_id'] : null;
    
    if (!$turma_id) {
        $mensagem_erro = "Por favor, selecione a turma de destino.";
    } elseif (!empty($_FILES['arquivo_inclusao']['name']) && !empty($_FILES['arquivo_final']['name'])) {
        
        $csv_inclusao = $_FILES['arquivo_inclusao']['tmp_name'];
        $csv_final = $_FILES['arquivo_final']['tmp_name'];
        
        // 1. MAPEIA PLANILHA DE AVALIAÇÃO FINAL EM MEMÓRIA
        $avaliacoes_finais = [];
        if (($handle_f = fopen($csv_final, "r")) !== FALSE) {
            fgetcsv($handle_f, 0, ",", "\"", "\\"); // Pula o cabeçalho
            
            while (($dados_f = fgetcsv($handle_f, 0, ",", "\"", "\\")) !== FALSE) {
                $nome_final = mb_strtoupper(trim($dados_f[1] ?? ''), 'UTF-8'); // Coluna B: Nome
                if (!empty($nome_final)) {
                    $avaliacoes_finais[$nome_final] = [
                        'sentimento_denuncia'      => substr($dados_f[2] ?? '', 0, 50),
                        'acha_justa'               => (mb_strtolower(trim($dados_f[3] ?? '')) === 'sim') ? 'sim' : 'nao',
                        'motivo_denuncia'          => $dados_f[3] ?? '',
                        'dificuldade_participar'   => (mb_strtolower(trim($dados_f[4] ?? '')) === 'sim') ? 'sim' : 'nao',
                        'motivo_dificuldade'       => $dados_f[4] ?? '',
                        'avaliacao_participacao'   => substr($dados_f[5] ?? '', 0, 255),
                        'sentimento_inicio'        => $dados_f[6] ?? '',
                        'vantagens_experiencia'    => $dados_f[7] ?? '',
                        'temas_importantes'        => $dados_f[8] ?? '',
                        'mudanca_relacionamentos'  => $dados_f[9] ?? '',
                        'o_que_mais_gostou'        => $dados_f[10] ?? '',
                        'impacto_mudanca_costumes' => $dados_f[11] ?? '',
                        'mudanca_visao_mundo'      => substr($dados_f[12] ?? '', 0, 50),
                        'relacao_grupo_pensamento' => $dados_f[13] ?? '',
                        'recomendaria'             => (mb_strtolower(trim($dados_f[14] ?? '')) === 'sim') ? 'sim' : 'nao'
                    ];
                }
            }
            fclose($handle_f);
        }

        // 2. PROCESSA A FICHA DE INCLUSÃO E INSERE NAS TABELAS CERTAS
        if (($handle_i = fopen($csv_inclusao, "r")) !== FALSE) {
            fgetcsv($handle_i, 0, ",", "\"", "\\"); // Pula cabeçalho
            
            $total_importado = 0;
            $pdo->beginTransaction();
            
            try {
                $stmt_caso = $pdo->prepare("INSERT INTO casos (status, data_abertura) VALUES ('ativo', NOW())");
                
                $stmt_part = $pdo->prepare("
                    INSERT INTO participantes (caso_id, turma_id, nome, numero_processo, observacoes, total_passagens, status) 
                    VALUES (?, ?, ?, ?, ?, 1, 'ativo')
                ");
                
                $stmt_pivo = $pdo->prepare("INSERT INTO turmas_participantes (turma_id, participante_id) VALUES (?, ?)");
                
                // CORRIGIDO: Removida a coluna 'observacoes' que não existe na tabela ficha_inclusao
                $stmt_ficha_inc = $pdo->prepare("
                    INSERT INTO ficha_inclusao (
                        participante_id, nome, numero_processo, situacao_civil, escolaridade, trabalho_ocupacao, 
                        condicao_moradia, uso_alcool, frequencia_bebida, drogas_utilizadas, tipo_violencia_praticada, 
                        conflitos_infancia, ja_foi_agredido_companheira, tipo_violencia_sofrida, indiciado_anteriormente, 
                        uso_drogas_antes_fato, expectativa_grupo
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");

                $stmt_ficha_av = $pdo->prepare("
                    INSERT INTO ficha_avaliacao_final (
                        participante_id, sentimento_denuncia, acha_justa, motivo_denuncia, dificuldade_participar, 
                        motivo_dificuldade, avaliacao_participacao, sentimento_inicio, vantagens_experiencia, 
                        temas_importantes, mudanca_relacionamentos, o_que_mais_gostou, impacto_mudanca_costumes, 
                        mudanca_visao_mundo, relacao_grupo_pensamento, recomendaria
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");

                while (($dados_i = fgetcsv($handle_i, 0, ",", "\"", "\\")) !== FALSE) {
                    $nome            = trim($dados_i[3] ?? '');  // Coluna D: Nome Completo
                    $numero_processo = trim($dados_i[48] ?? ''); // Coluna AW: Processo
                    $obs_inclusao    = trim($dados_i[9] ?? '');  // Coluna J: Observação Inicial

                    if (empty($nome)) {
                        continue; 
                    }

                    // 1º Passo: Cria o Caso
                    $stmt_caso->execute();
                    $novo_caso_id = $pdo->lastInsertId();

                    // 2º Passo: Cria o Participante (Aqui sim salvamos as observações)
                    $stmt_part->execute([$novo_caso_id, $turma_id, $nome, $numero_processo, $obs_inclusao]);
                    $novo_participante_id = $pdo->lastInsertId();

                    // 3º Passo: Vincula à Turma
                    $stmt_pivo->execute([$turma_id, $novo_participante_id]);

                    // 4º Passo: Alimenta a tabela 'ficha_inclusao' (Ajustado sem a coluna inválida)
                    $stmt_ficha_inc->execute([
                        $novo_participante_id,
                        $nome,
                        $numero_processo,
                        substr($dados_i[4] ?? '', 0, 50),  
                        substr($dados_i[5] ?? '', 0, 100), 
                        substr($dados_i[6] ?? '', 0, 100), 
                        substr($dados_i[7] ?? '', 0, 100), 
                        substr($dados_i[8] ?? '', 0, 100), 
                        substr($dados_i[10] ?? '', 0, 100),
                        $dados_i[11] ?? '',
                        $dados_i[12] ?? '',
                        $dados_i[13] ?? '',
                        substr($dados_i[14] ?? '', 0, 50),
                        $dados_i[15] ?? '',
                        substr($dados_i[16] ?? '', 0, 50),
                        substr($dados_i[17] ?? '', 0, 50),
                        $dados_i[18] ?? ''
                    ]);

                    // 5º Passo: Se houver avaliação de saída, alimenta a 'ficha_avaliacao_final'
                    $nome_chave = mb_strtoupper($nome, 'UTF-8');
                    if (isset($avaliacoes_finais[$nome_chave])) {
                        $av = $avaliacoes_finais[$nome_chave];
                        
                        $stmt_ficha_av->execute([
                            $novo_participante_id,
                            $av['sentimento_denuncia'],
                            $av['acha_justa'],
                            $av['motivo_denuncia'],
                            $av['dificuldade_participar'],
                            $av['motivo_dificuldade'],
                            $av['avaliacao_participacao'],
                            $av['sentimento_inicio'],
                            $av['vantagens_experiencia'],
                            $av['temas_importantes'],
                            $av['mudanca_relacionamentos'],
                            $av['o_que_mais_gostou'],
                            $av['impacto_mudanca_costumes'],
                            $av['mudanca_visao_mundo'],
                            $av['relacao_grupo_pensamento'],
                            $av['recomendaria']
                        ]);
                    }

                    registrarLog($pdo, 'CREATE', 'participantes', $novo_participante_id, "Importação estruturada completa (Fichas Inclusão + Final) de: " . $nome);
                    $total_importado++;
                }

                $pdo->commit();
                $mensagem_sucesso = "Sucesso absoluto! O sistema cruzou as planilhas e distribuiu as respostas corretamente dentro das tabelas <strong>ficha_inclusao</strong> e <strong>ficha_avaliacao_final</strong> para os <strong>{$total_importado}</strong> participantes.";
                
            } catch (Exception $e) {
                $pdo->rollBack();
                $mensagem_erro = "Erro no banco de dados durante o cruzamento: " . $e->getMessage();
            }
            fclose($handle_i);
        }
    } else {
        $mensagem_erro = "Por favor, selecione ambos os arquivos (.CSV) para realizar o cruzamento.";
    }
}
?>

<div class="container py-4">
    <div class="mb-4">
        <h3 class="fw-bold text-dark mb-1">
            <i class="bi bi-shuffle text-success me-2"></i>Importador Relacional Avançado
        </h3>
        <p class="text-muted small">Este módulo preenche nativamente as tabelas internas do sistema separando o cadastro de entrada do questionário de saída.</p>
    </div>

    <?php if (!empty($mensagem_sucesso)): ?>
        <div class="alert alert-success border-0 shadow-sm mb-4"><?= $mensagem_sucesso ?></div>
    <?php endif; ?>

    <?php if (!empty($mensagem_erro)): ?>
        <div class="alert alert-danger border-0 shadow-sm mb-4"><?= $mensagem_erro ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="row g-4">
            <div class="col-md-7">
                <div class="card border-0 shadow-sm p-4">
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Turma de Destino</label>
                        <select name="turma_id" class="form-select" required>
                            <option value="">-- Selecione --</option>
                            <?php foreach ($turmas as $t): ?>
                                <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['nome']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">1. Planilha de Inclusão (Vai para 'ficha_inclusao')</label>
                        <input type="file" name="arquivo_inclusao" class="form-control" accept=".csv" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted small fw-bold">2. Planilha de Avaliação Final (Vai para 'ficha_avaliacao_final')</label>
                        <input type="file" name="arquivo_final" class="form-control" accept=".csv" required>
                    </div>

                    <button type="submit" name="submeter" class="btn btn-success fw-bold w-100">
                        <i class="bi bi-play-circle-fill me-1"></i> Executar Importação Banco a Banco
                    </button>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card border-0 shadow-sm bg-light h-100">
                    <div class="card-body">
                        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-check-all text-success me-1"></i> Estrutura Alinhada!</h6>
                        <p class="text-muted small">O código agora reflete perfeitamente as colunas reais do seu banco, enviando as observações unicamente para o prontuário do participante.</p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../layout/admin_footer.php'; ?>