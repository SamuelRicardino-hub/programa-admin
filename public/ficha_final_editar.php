<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../layout/admin_header.php';

auth();

$participante_id = $_GET['id'] ?? null;

if (!$participante_id) {
    die("Participante não informado");
}

// BUSCA OS DADOS JÁ SALVOS
$stmt = $pdo->prepare("SELECT * FROM ficha_avaliacao_final WHERE participante_id = ?");
$stmt->execute([$participante_id]);
$dados = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$dados) {
    die("Nenhuma ficha encontrada para este participante. Use o formulário de cadastro primeiro.");
}

// Transforma a string de temas de volta em array para o checkbox
$temas_salvos = explode(', ', $dados['temas_importantes'] ?? '');
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>📝 Editar Ficha de Avaliação Final</h3>
        <a href="participantes_detalhes.php?id=<?= $participante_id ?>" class="btn btn-secondary btn-sm">Voltar</a>
    </div>

    <form method="POST" action="ficha_final_salvar.php">
        <input type="hidden" name="participante_id" value="<?= $participante_id ?>">

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                
                <div class="mb-3">
                    <label><strong>Como você se sentiu ao receber a denúncia?</strong></label><br>
                    <?php
                    $opcoes = ['raiva', 'medo', 'injustiçado', 'tristeza', 'tranquilo', 'nada'];
                    foreach ($opcoes as $op):
                        $checked = ($dados['sentimento_denuncia'] == $op) ? 'checked' : '';
                    ?>
                        <label class="me-3">
                            <input type="radio" name="sentimento_denuncia" value="<?= $op ?>" <?= $checked ?>> <?= ucfirst($op) ?>
                        </label>
                    <?php endforeach; ?>
                </div>

                <div class="mb-3">
                    <label><strong>A denúncia foi justa?</strong></label><br>
                    <label class="me-3"><input type="radio" name="acha_justa" value="sim" <?= ($dados['acha_justa'] == 'sim') ? 'checked' : '' ?>> Sim</label>
                    <label><input type="radio" name="acha_justa" value="nao" <?= ($dados['acha_justa'] == 'nao') ? 'checked' : '' ?>> Não</label>
                </div>

                <div class="mb-3">
                    <label>Motivo</label>
                    <textarea name="motivo_denuncia" class="form-control"><?= htmlspecialchars($dados['motivo_denuncia']) ?></textarea>
                </div>

                <div class="mb-3">
                    <label><strong>Temas importantes</strong></label><br>
                    <?php
                    $temas = ["Relacoes de Genero", "Autoresponsabilidade", "Violencia Baseada no Genero", "Inteligencia emocional", "Saude do Homem", "Ressocializacao", "Paternidade"];
                    foreach ($temas as $t):
                        $checked = in_array($t, $temas_salvos) ? 'checked' : '';
                    ?>
                        <label class="d-block">
                            <input type="checkbox" name="temas_importantes[]" value="<?= $t ?>" <?= $checked ?>> <?= $t ?>
                        </label>
                    <?php endforeach; ?>
                </div>

                </div>
        </div>

        <div class="mb-5">
            <button type="submit" class="btn btn-success btn-lg">Atualizar Ficha</button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../layout/admin_footer.php'; ?>