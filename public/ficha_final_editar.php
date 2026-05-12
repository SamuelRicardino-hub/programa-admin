<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../layout/admin_header.php';

auth();
canAny(['admin', 'atendente']);

$participante_id = $_GET['id'] ?? $_GET['participante_id'] ?? null;

if (!$participante_id) {
    die("Participante não informado");
}

// BUSCA OS DADOS JÁ SALVOS
$stmt = $pdo->prepare("SELECT * FROM ficha_avaliacao_final WHERE participante_id = ?");
$stmt->execute([$participante_id]);
$dados = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$dados) {
    die("Ficha não encontrada. Certifique-se de que a ficha inicial foi criada.");
}

// Transforma a string de temas de volta em array para o checkbox
$temas_salvos = explode(', ', $dados['temas_importantes'] ?? '');
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold text-dark mb-1">📝 Avaliação Final Reflexiva</h3>
                    <p class="text-muted small">Revisão das percepções do participante após o ciclo.</p>
                </div>
                <a href="participantes_detalhes.php?id=<?= $participante_id ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>
            </div>

            <form method="POST" action="ficha_final_salvar.php">
                <input type="hidden" name="participante_id" value="<?= $participante_id ?>">

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 text-primary fw-bold"><i class="bi bi-emoji-expressionless me-2"></i>Sobre a Denúncia</h5>
                    </div>
                    <div class="card-body p-4">
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold d-block mb-3">Como o participante se sentiu ao receber a denúncia?</label>
                            <div class="d-flex flex-wrap gap-3">
                                <?php
                                $opcoes = ['raiva', 'medo', 'injustiçado', 'tristeza', 'tranquilo', 'nada'];
                                foreach ($opcoes as $op):
                                    $checked = ($dados['sentimento_denuncia'] == $op) ? 'checked' : '';
                                ?>
                                    <div class="form-check border rounded px-3 py-2 bg-light shadow-sm" style="min-width: 120px;">
                                        <input class="form-check-input" type="radio" name="sentimento_denuncia" id="sent_<?= $op ?>" value="<?= $op ?>" <?= $checked ?>>
                                        <label class="form-check-label text-capitalize" for="sent_<?= $op ?>">
                                            <?= $op ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">O participante considera a denúncia justa?</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="acha_justa" id="justa_sim" value="sim" <?= ($dados['acha_justa'] == 'sim') ? 'checked' : '' ?> required>
                                <label class="btn btn-outline-success" for="justa_sim">Sim, considera justa</label>

                                <input type="radio" class="btn-check" name="acha_justa" id="justa_nao" value="nao" <?= ($dados['acha_justa'] == 'nao') ? 'checked' : '' ?>>
                                <label class="btn btn-outline-danger" for="justa_nao">Não considera justa</label>
                            </div>
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-bold">Argumentação / Motivo apresentado:</label>
                            <textarea name="motivo_denuncia" class="form-control" rows="4" placeholder="Descreva os argumentos utilizados pelo participante..."><?= htmlspecialchars($dados['motivo_denuncia']) ?></textarea>
                        </div>

                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 text-primary fw-bold"><i class="bi bi-lightbulb me-2"></i>Conteúdo e Absorção</h5>
                    </div>
                    <div class="card-body p-4">
                        <label class="form-label fw-bold mb-3">Quais temas o participante considerou mais relevantes?</label>
                        <div class="row">
                            <?php
                            $temas = [
                                "Relacoes de Genero" => "Relações de Gênero", 
                                "Autoresponsabilidade" => "Autorresponsabilidade", 
                                "Violencia Baseada no Genero" => "Violência Baseada no Gênero", 
                                "Inteligencia emocional" => "Inteligência Emocional", 
                                "Saude do Homem" => "Saúde do Homem", 
                                "Ressocializacao" => "Ressocialização", 
                                "Paternidade" => "Paternidade"
                            ];
                            foreach ($temas as $valor => $label):
                                $checked = in_array($valor, $temas_salvos) ? 'checked' : '';
                            ?>
                                <div class="col-md-6 mb-2">
                                    <div class="form-check form-switch border rounded p-2 px-4 shadow-sm bg-white">
                                        <input class="form-check-input" type="checkbox" name="temas_importantes[]" id="tema_<?= $valor ?>" value="<?= $valor ?>" <?= $checked ?>>
                                        <label class="form-check-label" for="tema_<?= $valor ?>"><?= $label ?></label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="d-grid mb-5">
                    <button type="submit" class="btn btn-primary btn-lg shadow">
                        <i class="bi bi-check-circle-fill me-2"></i>Atualizar Avaliação Final
                    </button>
                    <p class="text-center text-muted mt-2 small">Última atualização registrada no sistema.</p>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/admin_footer.php'; ?>