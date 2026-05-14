<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../layout/admin_header.php';

auth();

// Pegamos o ID do participante para vincular a ficha
$participante_id = $_GET['participante_id'] ?? $_GET['id'] ?? null;

if (!$participante_id) {
    die("Erro: Participante não identificado para abrir a ficha.");
}

// Opcional: Buscar nome do participante para exibir no topo
$stmt = $pdo->prepare("SELECT nome FROM participantes WHERE id = ?");
$stmt->execute([$participante_id]);
$p = $stmt->fetch();
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold text-dark mb-1">📝Avaliação Final Reflexiva</h3>
                    <p class="text-muted">Participante: <strong><?= htmlspecialchars($p['nome'] ?? 'Não encontrado') ?></strong></p>
                </div>
                <a href="participantes_detalhes.php?id=<?= $participante_id ?>" class="btn btn-outline-secondary">Voltar</a>
            </div>

            <form method="POST" action="ficha_final_salvar.php">
                <input type="hidden" name="participante_id" value="<?= $participante_id ?>">

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 text-primary fw-bold">Sobre a Denúncia</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <label class="form-label fw-bold">1. Como você se sentiu/reagiu na época que recebeu a denúncia?</label>
                            <div class="d-flex flex-wrap gap-2 mt-2">
                                <?php $sentimentos = ['Raiva', 'Medo', 'Injustiçado', 'Tristeza', 'Tranquilo', 'Nada'];
                                foreach ($sentimentos as $s): ?>
                                    <div class="form-check border rounded px-3 py-2 bg-light">
                                        <input class="form-check-input" type="radio" name="sentimento_denuncia" id="s_<?= $s ?>" value="<?= $s ?>" required>
                                        <label class="form-check-label" for="s_<?= $s ?>"><?= $s ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">2. Avaliando hoje, acha que foi justa a denúncia? Por qual motivo?</label>
                            <textarea name="motivo_denuncia" class="form-control" rows="3" placeholder="Descreva o motivo..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 text-primary fw-bold">Experiência no Grupo</h5>
                    </div>
                    <div class="card-body p-4">

                        <div class="mb-4">
                            <label class="form-label fw-bold">3. Foi difícil para você participar das reuniões? Por qual motivo?</label>
                            <textarea name="motivo_dificuldade" class="form-control" rows="2"><?= htmlspecialchars($dados['motivo_dificuldade'] ?? '') ?></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold d-block mb-2">4. Como você avalia sua participação no Grupo Reflexivo?</label>
                            <div class="d-grid gap-2">
                                <?php
                                $opcoes_4 = [
                                    'otima' => 'Ótima, aprendi muito e hoje me sinto outro homem',
                                    'boa_expressao' => 'Boa, me expressei e falei o que eu sentia',
                                    'boa_aprendizado' => 'Boa, gostei de participar e aprender',
                                    'ruim' => 'Ruim, sem importância porque estava contra a vontade'
                                ];
                                foreach ($opcoes_4 as $valor => $label):
                                    $checked = (($dados['avaliacao_participacao'] ?? '') == $valor) ? 'checked' : '';
                                ?>
                                    <div class="form-check border rounded p-2 px-4 bg-light shadow-sm">
                                        <input class="form-check-input" type="radio" name="avaliacao_participacao" id="aval_<?= $valor ?>" value="<?= $valor ?>" <?= $checked ?> required>
                                        <label class="form-check-label w-100" for="aval_<?= $valor ?>">
                                            <?= $label ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold d-block mb-2">5. Quando entrou no Grupo, como se sentia sobre ter que participar?</label>
                            <div class="row g-2">
                                <?php
                                $opcoes_5 = [
                                    'Muito chato' => 'Muito chato',
                                    'Desconfiado' => 'Desconfiado',
                                    'Com raiva e injustiçado' => 'Com raiva e injustiçado',
                                    'Oportunidade' => 'Uma oportunidade de repensar minhas atitudes'
                                ];
                                foreach ($opcoes_5 as $valor => $label):
                                    $checked = (($dados['sentimento_inicio'] ?? '') == $valor) ? 'checked' : '';
                                ?>
                                    <div class="col-md-6">
                                        <div class="form-check border rounded p-2 px-4 bg-light shadow-sm">
                                            <input class="form-check-input" type="radio" name="sentimento_inicio" id="sent_ini_<?= md5($valor) ?>" value="<?= $valor ?>" <?= $checked ?> required>
                                            <label class="form-check-label w-100" for="sent_ini_<?= md5($valor) ?>">
                                                <?= $label ?>
                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 text-primary fw-bold">Conteúdo</h5>
                    </div>
                    <div class="card-body p-4">
                        <label class="form-label fw-bold mb-3">6. Que assuntos considerou mais importantes/ajudaram você?</label>
                        <div class="row">
                            <?php
                            $temas = ["Regras de comportamento", "Lei Maria da Penha", "Álcool e drogas", "Inteligência emocional", "Sexualidade", "Paternidade", "Ciúmes"];
                            foreach ($temas as $t): ?>
                                <div class="col-md-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="temas_importantes[]" value="<?= $t ?>" id="t_<?= $t ?>">
                                        <label class="form-check-label" for="t_<?= $t ?>"><?= $t ?></label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 text-primary fw-bold">Reflexões sobre o Processo</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-success">Vantagens (Por que foi BOM participar?)</label>
                                <textarea name="vantagens_experiencia" class="form-control" rows="3"><?= $dados['vantagens_experiencia'] ?? '' ?></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-danger">Desvantagens (Por que foi RUIM participar?)</label>
                                <textarea name="desvantagens_experiencia" class="form-control" rows="3"><?= $dados['desvantagens_experiencia'] ?? '' ?></textarea>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Houve alguma mudança nos relacionamentos nesses últimos meses? Se SIM, explique:</label>
                            <textarea name="mudanca_relacionamentos" class="form-control" rows="3"><?= $dados['mudanca_relacionamentos'] ?? '' ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">O que você mais gostou no Grupo Reflexivo?</label>
                            <textarea name="o_que_mais_gostou" class="form-control" rows="2"><?= $dados['o_que_mais_gostou'] ?? '' ?></textarea>
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-bold">Você acredita que as mudanças de "costume" entre homens e mulheres melhorou ou dificultou os relacionamentos? Por quê?</label>
                            <textarea name="impacto_mudanca_costumes" class="form-control" rows="3"><?= $dados['impacto_mudanca_costumes'] ?? '' ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 text-primary fw-bold">Conclusão e Mudança</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">7. Você mudou sua "cabeça" ou modo de ver as relações entre as pessoas?</label>
                            <div class="mt-2">
                                <label class="me-3"><input type="radio" name="mudanca_visao_mundo" value="Sim" required> Sim</label>
                                <label><input type="radio" name="mudanca_visao_mundo" value="Não"> Não</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">8. Esse seu pensamento atual tem a ver com o Grupo? Explique:</label>
                            <textarea name="relacao_grupo_pensamento" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-bold">9. Recomendaria a experiência para outros homens? Por quê?</label>
                            <textarea name="motivo_recomendacao" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 mb-5">
                    <button type="submit" class="btn btn-success btn-lg shadow">Finalizar e Salvar Ficha</button>
                    <a href="participantes_detalhes.php?id=<?= $participante_id ?>" class="btn btn-link text-muted">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/admin_footer.php'; ?>