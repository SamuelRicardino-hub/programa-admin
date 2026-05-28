<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../layout/admin_header.php';

auth();

$participante_id = $_GET['id'] ?? null;
if (!$participante_id) die("Participante não informado");

$stmt = $pdo->prepare("SELECT * FROM ficha_avaliacao_final WHERE participante_id = ?");
$stmt->execute([$participante_id]);
$dados = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$dados) die("Ficha não encontrada. Crie a ficha primeiro.");

$temas_salvos = explode(', ', $dados['temas_importantes'] ?? '');
?>

<div class="container py-4">
    <form method="POST" action="ficha_final_salvar.php">
        <input type="hidden" name="participante_id" value="<?= $participante_id ?>">
        
        <h3 class="mb-4">📝 Editar Ficha de Avaliação Final</h3>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <label class="fw-bold">1. Como você se sentiu/reagiu na época que recebeu a denúncia?</label>
                <div class="d-flex flex-wrap gap-2 mt-2">
                    <?php $sentimentos = ['Raiva', 'Medo', 'Injustiçado', 'Tristeza', 'Tranquilo', 'Nada'];
                    foreach($sentimentos as $s): ?>
                        <label class="btn btn-outline-primary btn-sm">
                            <input type="radio" name="sentimento_denuncia" value="<?= $s ?>" <?= ($dados['sentimento_denuncia'] == $s) ? 'checked' : '' ?>> <?= $s ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="mb-3">
                    <label class="fw-bold">2. Avaliando hoje, acha que foi justa a denúncia? Por qual motivo?</label>
                    <textarea name="motivo_denuncia" class="form-control" rows="2"><?= htmlspecialchars($dados['motivo_denuncia'] ?? '') ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="fw-bold">3. Foi difícil para você participar das reuniões? Por qual motivo?</label>
                    <textarea name="motivo_dificuldade" class="form-control" rows="2"><?= htmlspecialchars($dados['motivo_dificuldade'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <label class="fw-bold">4. Como você avalia sua participação no Grupo Reflexivo?</label>
                <select name="avaliacao_participacao" class="form-select mt-2">
                    <option value="">Selecione...</option>
                    <option value="otima" <?= ($dados['avaliacao_participacao'] == 'otima') ? 'selected' : '' ?>>Ótima, aprendi muito e hoje me sinto outro homem</option>
                    <option value="boa_expressao" <?= ($dados['avaliacao_participacao'] == 'boa_expressao') ? 'selected' : '' ?>>Boa, me expressei e falei o que eu sentia</option>
                    <option value="boa_aprendizado" <?= ($dados['avaliacao_participacao'] == 'boa_aprendizado') ? 'selected' : '' ?>>Boa, gostei de participar e aprender</option>
                    <option value="ruim" <?= ($dados['avaliacao_participacao'] == 'ruim') ? 'selected' : '' ?>>Ruim, sem importância porque estava contra a vontade</option>
                </select>

                <label class="fw-bold mt-3">5. Quando entrou no Grupo, como se sentia sobre as reuniões?</label>
                <select name="sentimento_inicio" class="form-select mt-2">
                    <option value="">Selecione...</option>
                    <option <?= ($dados['sentimento_inicio'] == 'Muito chato') ? 'selected' : '' ?>>Muito chato</option>
                    <option <?= ($dados['sentimento_inicio'] == 'Desconfiado') ? 'selected' : '' ?>>Desconfiado</option>
                    <option <?= ($dados['sentimento_inicio'] == 'Com raiva e injustiçado') ? 'selected' : '' ?>>Com raiva e injustiçado</option>
                    <option <?= ($dados['sentimento_inicio'] == 'Oportunidade') ? 'selected' : '' ?>>Uma oportunidade de repensar minhas atitudes</option>
                </select>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <label class="fw-bold mb-2">6. Que assuntos considerou mais importantes?</label>
                <?php 
                $temas = ["Regras de comportamento", "Lei Maria da Penha", "Álcool e drogas", "Inteligência emocional", "Sexualidade", "Paternidade", "Ciúmes"];
                foreach($temas as $t): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="temas_importantes[]" value="<?= $t ?>" <?= in_array($t, $temas_salvos) ? 'checked' : '' ?>>
                        <label class="form-check-label"><?= $t ?></label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="mb-3">
                    <label class="fw-bold">7. Mudou sua "cabeça" ou modo de ver o mundo/relações?</label>
                    <select name="mudanca_visao_mundo" class="form-select">
                        <option value="Sim" <?= ($dados['mudanca_visao_mundo'] == 'Sim') ? 'selected' : '' ?>>Sim</option>
                        <option value="Não" <?= ($dados['mudanca_visao_mundo'] == 'Não') ? 'selected' : '' ?>>Não</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="fw-bold">8. Esse modo de pensar tem a ver com o Grupo Reflexivo? Explique:</label>
                    <textarea name="relacao_grupo_pensamento" class="form-control"><?= htmlspecialchars($dados['relacao_grupo_pensamento'] ?? '') ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="fw-bold">9. Recomendaria a experiência para outros homens? Por qual motivo?</label>
                    <textarea name="motivo_recomendacao" class="form-control"><?= htmlspecialchars($dados['motivo_recomendacao'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-success btn-lg w-100 mb-5">Atualizar Avaliação Final</button>
    </form>
</div>

<?php require_once __DIR__ . '/../layout/admin_footer.php'; ?>