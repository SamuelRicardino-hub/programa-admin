<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../layout/admin_header.php';

auth();

$participante_id = $_GET['id'] ?? null;
if (!$participante_id) die("Participante não informado");

// Busca o nome para o cabeçalho
$stmt = $pdo->prepare("SELECT nome FROM participantes WHERE id = ?");
$stmt->execute([$participante_id]);
$p = $stmt->fetch();
?>

<style>
    .form-section { background: #f8f9fa; border-radius: 10px; padding: 20px; margin-bottom: 25px; border-left: 5px solid var(--ser-blue); }
    .question-label { font-weight: 700; color: #2c3e50; display: block; margin-bottom: 12px; }
    .option-card { cursor: pointer; transition: 0.2s; border: 1px solid #dee2e6; border-radius: 8px; padding: 10px 15px; display: inline-block; margin-right: 10px; margin-bottom: 10px; background: white; }
    .option-card:hover { border-color: var(--ser-orange); background: #fff5f0; }
    input[type="radio"]:checked + span, input[type="checkbox"]:checked + span { font-weight: bold; color: var(--ser-blue); }
</style>

<div class="container-fluid py-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-journal-check me-2 text-success"></i>Avaliação de Desfecho
                    </h3>
                    <p class="text-muted mb-0">Encerramento do ciclo: <strong><?= htmlspecialchars($p['nome']) ?></strong></p>
                </div>
                <a href="participantes_detalhes.php?id=<?= $participante_id ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-x-lg me-1"></i> Cancelar
                </a>
            </div>

            <form method="POST" action="ficha_final_salvar.php">
                <input type="hidden" name="participante_id" value="<?= $participante_id ?>">

                <div class="form-section shadow-sm">
                    <h5 class="fw-bold mb-4 text-primary"><i class="bi bi-shield-exclamation me-2"></i>Percepção da Denúncia</h5>
                    
                    <div class="mb-4">
                        <label class="question-label">Como você se sentiu ao receber a denúncia?</label>
                        <div class="d-flex flex-wrap">
                            <?php
                            $opcoes = ['raiva', 'medo', 'injustiçado', 'tristeza', 'tranquilo', 'nada'];
                            foreach ($opcoes as $op):
                            ?>
                                <label class="option-card">
                                    <input type="radio" name="sentimento_denuncia" value="<?= $op ?>" class="me-2" required>
                                    <span><?= ucfirst($op) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="question-label">A denúncia foi justa?</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="acha_justa" id="justa_sim" value="sim" required>
                                <label class="btn btn-outline-success" for="justa_sim">Sim</label>
                                <input type="radio" class="btn-check" name="acha_justa" id="justa_nao" value="nao">
                                <label class="btn btn-outline-danger" for="justa_nao">Não</label>
                            </div>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label class="question-label">Por que?</label>
                            <textarea name="motivo_denuncia" class="form-control" rows="1" placeholder="Explique brevemente..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="form-section shadow-sm">
                    <h5 class="fw-bold mb-4 text-primary"><i class="bi bi-people me-2"></i>Experiência no Grupo</h5>
                    
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="question-label">Foi difícil participar?</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="dificuldade_participar" id="dif_nao" value="nao" required>
                                <label class="btn btn-outline-primary" for="dif_nao">Não</label>
                                <input type="radio" class="btn-check" name="dificuldade_participar" id="dif_sim" value="sim">
                                <label class="btn btn-outline-warning text-dark" for="dif_sim">Sim</label>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <label class="question-label">Qual foi a maior dificuldade?</label>
                            <textarea name="motivo_dificuldade" class="form-control" rows="1"></textarea>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="question-label">Como você avalia sua participação?</label>
                        <div class="list-group">
                            <label class="list-group-item list-group-item-action border-start-0 border-end-0 border-top-0">
                                <input class="form-check-input me-2" type="radio" name="avaliacao_participacao" value="otima" required>
                                <strong>Ótima:</strong> Aprendi muito e hoje me sinto outro homem.
                            </label>
                            <label class="list-group-item list-group-item-action border-start-0 border-end-0 border-top-0">
                                <input class="form-check-input me-2" type="radio" name="avaliacao_participacao" value="boa">
                                <strong>Boa:</strong> Gostei de participar e aprender.
                            </label>
                            <label class="list-group-item list-group-item-action border-0">
                                <input class="form-check-input me-2" type="radio" name="avaliacao_participacao" value="ruim">
                                <strong>Ruim:</strong> Sem importância, estava contra minha vontade.
                            </label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="question-label text-success">Pontos Positivos</label>
                            <textarea name="pontos_positivos" class="form-control" rows="3" placeholder="O que foi bom?"></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="question-label text-danger">Pontos Negativos</label>
                            <textarea name="pontos_negativos" class="form-control" rows="3" placeholder="O que foi ruim?"></textarea>
                        </div>
                    </div>
                </div>

                <div class="form-section shadow-sm">
                    <h5 class="fw-bold mb-4 text-primary"><i class="bi bi-lightbulb me-2"></i>Aprendizado e Mudança</h5>
                    
                    <div class="mb-4">
                        <label class="question-label">Quais temas foram mais importantes para você?</label>
                        <div class="row">
                            <?php
                            $temas = ["Relações de Gênero", "Autoresponsabilidade", "Violência de Gênero", "Inteligência Emocional", "Saúde do Homem", "Ressocialização", "Paternidade"];
                            foreach ($temas as $t): ?>
                                <div class="col-md-4 mb-2">
                                    <label class="option-card w-100 mb-0">
                                        <input type="checkbox" name="temas_importantes[]" value="<?= $t ?>" class="me-2">
                                        <span><?= $t ?></span>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="question-label">Houve mudança real?</label>
                            <select name="houve_mudanca" class="form-select" required>
                                <option value="">Selecione...</option>
                                <option value="sim">Sim, mudou completamente</option>
                                <option value="parcial">Sim, em alguns pontos</option>
                                <option value="nao">Não mudou nada</option>
                            </select>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label class="question-label">Descreva essa mudança (ou a falta dela):</label>
                            <textarea name="descricao_mudanca" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>

                <div class="form-section shadow-sm bg-white border-success">
                    <h5 class="fw-bold mb-4 text-success"><i class="bi bi-check2-all me-2"></i>Considerações Finais</h5>
                    
                    <div class="mb-3">
                        <label class="question-label">O que você mais gostou no grupo?</label>
                        <textarea name="gostou_grupo" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="question-label">Como você se sente saindo do grupo?</label>
                            <input type="text" name="sentimento_inicio" class="form-control" placeholder="Ex: Aliviado, renovado...">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="question-label">Recomendaria o grupo a outros homens?</label>
                            <div class="btn-group w-100">
                                <input type="radio" class="btn-check" name="recomendaria" id="rec_sim" value="sim">
                                <label class="btn btn-outline-primary" for="rec_sim">Sim, recomendaria</label>
                                <input type="radio" class="btn-check" name="recomendaria" id="rec_nao" value="nao">
                                <label class="btn btn-outline-secondary" for="rec_nao">Não recomendaria</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="question-label">Sugestões ou Reclamações</label>
                        <textarea name="sugestoes" class="form-control" rows="2" placeholder="Sua opinião para melhorarmos o projeto..."></textarea>
                    </div>
                </div>

                <div class="d-grid mb-5">
                    <button type="submit" class="btn btn-success btn-lg shadow">
                        <i class="bi bi-save2 me-2"></i>Finalizar e Salvar Avaliação
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/admin_footer.php'; ?>