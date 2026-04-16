<?php
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../layout/admin_header.php';
$id = $_GET['participante_id'] ?? null;
?>

<div class="container mt-4">
    <h3>Ficha de Avaliação Final</h3>

    <form method="POST" action="ficha_final_salvar.php">

        <input type="hidden" name="participante_id" value="<?= $id ?>">

        <label>Sentimento ao receber denúncia</label><br>
        <select name="sentimento_denuncia" class="form-control mb-3">
            <option value="raiva">Raiva</option>
            <option value="medo">Medo</option>
            <option value="injusticado">Injustiçado</option>
            <option value="tristeza">Tristeza</option>
            <option value="tranquilo">Tranquilo</option>
            <option value="nada">Nada</option>
        </select>

        <label>Denúncia foi justa?</label>
        <select name="acha_justa" class="form-control mb-2">
            <option value="sim">Sim</option>
            <option value="nao">Não</option>
        </select>

        <textarea name="motivo_denuncia" class="form-control mb-3" placeholder="Motivo"></textarea>

        <label>Dificuldade para participar?</label>
        <select name="dificuldade_participar" class="form-control mb-2">
            <option value="sim">Sim</option>
            <option value="nao">Não</option>
        </select>

        <textarea name="motivo_dificuldade" class="form-control mb-3"></textarea>

        <label>Avaliação do grupo</label>
        <select name="avaliacao_participacao" class="form-control mb-3">
            <option value="otima">Ótima</option>
            <option value="boa">Boa</option>
            <option value="ruim">Ruim</option>
        </select>

        <label>Temas importantes</label><br>
        <?php
        $temas = [
            "Relações de Gênero",
            "Autoresponsabilidade",
            "Violência Baseada no Gênero",
            "Inteligência emocional",
            "Saúde do Homem",
            "Ressocialização",
            "Paternidade"
        ];
        foreach ($temas as $t):
        ?>
            <input type="checkbox" name="temas_importantes[]" value="<?= $t ?>"> <?= $t ?><br>
        <?php endforeach; ?>

        <br>

        <label>Houve mudança?</label>
        <select name="houve_mudanca" class="form-control mb-2">
            <option value="sim">Sim</option>
            <option value="nao">Não</option>
        </select>

        <textarea name="descricao_mudanca" class="form-control mb-3"></textarea>

        <label>O que mais gostou</label>
        <textarea name="gostou_grupo" class="form-control mb-3"></textarea>

        <label>Como está saindo do grupo</label>
        <textarea name="como_saiu" class="form-control mb-3"></textarea>

        <label>Recomendaria?</label>
        <select name="recomendaria" class="form-control mb-2">
            <option value="sim">Sim</option>
            <option value="nao">Não</option>
        </select>

        <textarea name="motivo_recomendacao" class="form-control mb-3"></textarea>

        <label>Sugestões</label>
        <textarea name="sugestoes" class="form-control mb-3"></textarea>

        <button class="btn btn-primary">Salvar</button>
    </form>
</div>

<?php require_once __DIR__ . '/../../layout/admin_footer.php'; ?>