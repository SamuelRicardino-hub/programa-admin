<?php
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../layout/admin_header.php';

auth();

$participante_id = $_GET['id'] ?? null;
if (!$participante_id) die("Participante não informado");
?>

<div class="container mt-4">
    <h3>Ficha de Avaliação Final</h3>

    <form method="POST" action="ficha_final_salvar.php">

        <input type="hidden" name="participante_id" value="<?= $participante_id ?>">

        <div class="mb-3">
            <label><strong>Como você se sentiu ao receber a denúncia?</strong></label><br>

            <?php
            $opcoes = ['raiva', 'medo', 'injustiçado', 'tristeza', 'tranquilo', 'nada'];
            foreach ($opcoes as $op):
            ?>
                <label class="me-3">
                    <input type="radio" name="sentimento_denuncia" value="<?= $op ?>"> <?= ucfirst($op) ?>
                </label>
            <?php endforeach; ?>
        </div>

        <div class="mb-3">
            <label><strong>A denúncia foi justa?</strong></label><br>
            <label><input type="radio" name="acha_justa" value="sim"> Sim</label>
            <label><input type="radio" name="acha_justa" value="nao"> Não</label>
        </div>

        <div class="mb-3">
            <label>Motivo</label>
            <textarea name="motivo_denuncia" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label><strong>Foi difícil participar?</strong></label><br>
            <label><input type="radio" name="dificuldade_participar" value="nao"> Não</label>
            <label><input type="radio" name="dificuldade_participar" value="sim"> Sim</label>
        </div>

        <div class="mb-3">
            <label>Motivo</label>
            <textarea name="motivo_dificuldade" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label><strong>Avaliação da participação</strong></label><br>

            <label>
                <input type="radio" name="avaliacao_participacao" value="otima">
                Ótima, aprendi muito e hoje me sinto outro homem
            </label><br>

            <label>
                <input type="radio" name="avaliacao_participacao" value="boa">
                Boa, gostei de participar e aprender
            </label><br>

            <label>
                <input type="radio" name="avaliacao_participacao" value="ruim">
                Ruim, sem importância porque estava contra minha vontade
            </label>
        </div>

        <div class="mb-3">
            <label><strong>Foi bom participar porque:</strong></label>
            <textarea name="pontos_positivos" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label><strong>Foi ruim participar porque:</strong></label>
            <textarea name="pontos_negativos" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label><strong>Temas importantes</strong></label><br>

            <?php
            $temas = [
                "Relacoes de Genero",
                "Autoresponsabilidade",
                "Violencia Baseada no Genero",
                "Inteligencia emocional",
                "Saude do Homem",
                "Ressocializacao",
                "Paternidade"
            ];
            foreach ($temas as $t):
            ?>
                <label class="d-block">
                    <input type="checkbox" name="temas_importantes[]" value="<?= $t ?>"> <?= $t ?>
                </label>
            <?php endforeach; ?>
        </div>

        <div class="mb-3">
            <label><strong>Houve mudança?</strong></label><br>

            <label>
                <input type="radio" name="houve_mudanca" value="sim">
                Sim, mudou completamente
            </label><br>

            <label>
                <input type="radio" name="houve_mudanca" value="nao">
                Não mudou nada
            </label>
        </div>

        <div class="mb-3">
            <label>Explique a mudança</label>
            <textarea name="descricao_mudanca" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label><strong>O que você mais gostou?</strong></label>
            <textarea name="gostou_grupo" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label><strong>Como está saindo do grupo?</strong></label>
            <textarea name="sentimento_inicio" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label><strong>Recomendaria?</strong></label><br>
            <label><input type="radio" name="recomendaria" value="sim"> Sim</label>
            <label><input type="radio" name="recomendaria" value="nao"> Não</label>
        </div>

        <div class="mb-3">
            <label>Motivo</label>
            <textarea name="motivo_recomendacao" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label><strong>Sugestões / Reclamações</strong></label>
            <textarea name="sugestoes" class="form-control"></textarea>
        </div>

        <button class="btn btn-success">Salvar</button>

    </form>
</div>

<?php require_once __DIR__ . '/../../layout/admin_footer.php';
