<?php
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../config/auth.php';

auth();
canAny(['admin', 'atendente']);

$id = $_GET['id'] ?? null;

if (!$id) {
    die("Participante não informado");
}

require_once __DIR__ . '/../../layout/admin_header.php';
?>

<div class="container mt-4">

    <h3>Ficha de Avaliação Final</h3>

    <form method="POST" action="ficha_final_salvar.php">

        <input type="hidden" name="participante_id" value="<?= $id ?>">

        <hr>

        <!-- SENTIMENTO -->
        <label>Sentimento ao receber a denúncia:</label><br>
        <input type="radio" name="sentimento_denuncia" value="raiva"> Raiva
        <input type="radio" name="sentimento_denuncia" value="medo"> Medo
        <input type="radio" name="sentimento_denuncia" value="injustica"> Injustiçado
        <input type="radio" name="sentimento_denuncia" value="outro"> Outro

        <textarea name="outro_sentimento" class="form-control mt-2" placeholder="Se outro, qual?"></textarea>

        <hr>

        <!-- DENUNCIA -->
        <label>Você acha justa a denúncia?</label><br>
        <input type="radio" name="acha_justa" value="sim"> Sim
        <input type="radio" name="acha_justa" value="nao"> Não

        <textarea name="motivo_denuncia" class="form-control mt-2" placeholder="Explique"></textarea>

        <hr>

        <!-- DIFICULDADE -->
        <label>Teve dificuldade em participar?</label><br>
        <input type="radio" name="dificuldade_participar" value="sim"> Sim
        <input type="radio" name="dificuldade_participar" value="nao"> Não

        <textarea name="motivo_dificuldade" class="form-control mt-2"></textarea>

        <hr>

        <!-- PARTICIPAÇÃO -->
        <label>Como avalia sua participação?</label>
        <input type="text" name="avaliacao_participacao" class="form-control">

        <hr>

        <!-- GRUPO -->
        <label>Pontos positivos:</label>
        <textarea name="pontos_positivos" class="form-control"></textarea>

        <label>Pontos negativos:</label>
        <textarea name="pontos_negativos" class="form-control"></textarea>

        <label>Temas importantes:</label>
        <textarea name="temas_importantes" class="form-control"></textarea>

        <hr>

        <!-- RELACIONAMENTO -->
        <label>Houve mudança nos relacionamentos?</label><br>
        <input type="radio" name="houve_mudanca" value="sim"> Sim
        <input type="radio" name="houve_mudanca" value="nao"> Não

        <textarea name="descricao_mudanca" class="form-control mt-2"></textarea>

        <hr>

        <label>Hoje seus relacionamentos:</label><br>
        <input type="radio" name="impacto_relacionamentos" value="melhorou"> Melhorou
        <input type="radio" name="impacto_relacionamentos" value="dificultou"> Dificultou

        <textarea name="motivo_impacto" class="form-control mt-2"></textarea>

        <hr>

        <label>Mudou seu pensamento?</label><br>
        <input type="radio" name="mudou_pensamento" value="sim"> Sim
        <input type="radio" name="mudou_pensamento" value="nao"> Não

        <textarea name="explicacao_pensamento" class="form-control mt-2"></textarea>

        <hr>

        <label>Recomendaria o grupo?</label><br>
        <input type="radio" name="recomendaria" value="sim"> Sim
        <input type="radio" name="recomendaria" value="nao"> Não

        <textarea name="motivo_recomendacao" class="form-control mt-2"></textarea>

        <br>

        <button class="btn btn-primary">Salvar Avaliação</button>

    </form>

</div>

<?php require_once __DIR__ . '/../../layout/admin_footer.php'; ?>