<?php
require_once __DIR__ . '/../../layout/admin_header.php';
$id = $_GET['participante_id'] ?? null;
?>

<div class="container mt-4">
    <h3>Ficha de Inclusão</h3>

    <form method="POST" action="ficha_inclusao_salvar.php">

        <input type="hidden" name="participante_id" value="<?= $id ?>">

        <div class="mb-3">
            <label>Cor</label>
            <input type="text" name="cor" class="form-control">
        </div>

        <div class="mb-3">
            <label>Situação Civil</label>
            <input type="text" name="situacao_civil" class="form-control">
        </div>

        <div class="mb-3">
            <label>Religião</label>
            <input type="text" name="religiao" class="form-control">
        </div>

        <div class="mb-3">
            <label>Escolaridade</label>
            <input type="text" name="escolaridade" class="form-control">
        </div>

        <div class="mb-3">
            <label>Renda Familiar</label>
            <input type="text" name="renda_familiar" class="form-control">
        </div>

        <div class="mb-3">
            <label>Profissão</label>
            <input type="text" name="profissao" class="form-control">
        </div>

        <div class="mb-3">
            <label>Problemas de Saúde</label>
            <textarea name="problemas_saude" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label>Uso de Álcool</label>
            <input type="text" name="uso_alcool" class="form-control">
        </div>

        <div class="mb-3">
            <label>Drogas Utilizadas</label>
            <textarea name="drogas_utilizadas" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label>Violência Praticada</label>
            <textarea name="violencia_praticada" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label>Violência Sofrida</label>
            <textarea name="violencia_sofrida" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label>Histórico Familiar</label>
            <textarea name="historico_familiar" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label>Situação Jurídica</label>
            <textarea name="situacao_juridica" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label>Expectativa no Grupo</label>
            <textarea name="expectativa_grupo" class="form-control"></textarea>
        </div>

        <button class="btn btn-success">Salvar</button>
    </form>
</div>

<?php require_once __DIR__ . '/../../layout/admin_footer.php'; ?>