<?php require_once __DIR__ . '/../../layout/admin_header.php'; ?>

<div class="container mt-4">

    <h3>📄 Ficha de Inclusão</h3>

    <form method="POST" action="ficha_inclusao_salvar.php">

        <input type="hidden" name="participante_id" value="<?= $_GET['id'] ?>">

        <!-- DADOS INICIAIS -->
        <div class="mb-3">
            <label>Número do Caso</label>
            <input type="text" name="numero_caso" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Número do Processo</label>
            <input type="text" name="numero_processo" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Nome Completo</label>
            <input type="text" name="nome" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Grau de parentesco com a denunciante</label>
            <input type="text" name="parentesco" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Idade</label>
            <input type="number" name="idade" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Naturalidade</label>
            <input type="text" name="naturalidade" class="form-control" required>
        </div>

        <!-- COR -->
        <div class="mb-3">
            <label>Cor</label>
            <select name="cor" id="cor" class="form-control" required>
                <option value="">Selecione</option>
                <option>Branca</option>
                <option>Preta</option>
                <option>Parda</option>
                <option>Indígena</option>
                <option>Amarela</option>
            </select>
        </div>

        <!-- RELACIONAMENTO -->
        <div class="mb-3">
            <label>Relacionamento atual</label>
            <select name="relacionamento" id="relacionamento" class="form-control" required>
                <option value="">Selecione</option>
                <option>Solteiro</option>
                <option>Namorando</option>
                <option>Casado</option>
                <option>Divorciado</option>
                <option>Viúvo</option>
                <option>Outro</option>
            </select>
        </div>

        <div class="mb-3" id="relacionamento_outro_div" style="display:none;">
            <label>Qual?</label>
            <input type="text" name="relacionamento_outro" class="form-control">
        </div>

        <!-- COM QUEM SE RELACIONA -->
        <div class="mb-3">
            <label>Relacionamento com</label>
            <select name="relacao_com" id="relacao_com" class="form-control">
                <option value="">Selecione</option>
                <option>Com a denunciante</option>
                <option>Com outra pessoa</option>
                <option>Outro</option>
            </select>
        </div>

        <div class="mb-3" id="relacao_com_outro_div" style="display:none;">
            <label>Qual?</label>
            <input type="text" name="relacao_com_outro" class="form-control">
        </div>

        <!-- RELIGIÃO -->
        <div class="mb-3">
            <label>Religião</label>
            <select name="religiao" id="religiao" class="form-control" required>
                <option value="">Selecione</option>
                <option>Católica</option>
                <option>Evangélica</option>
                <option>Espírita</option>
                <option>Umbanda</option>
                <option>Candomblé</option>
                <option>Outra</option>
            </select>
        </div>

        <div class="mb-3" id="religiao_outro_div" style="display:none;">
            <label>Qual?</label>
            <input type="text" name="religiao_outro" class="form-control">
        </div>

        <!-- ESCOLARIDADE -->
        <div class="mb-3">
            <label>Escolaridade</label>
            <select name="escolaridade" id="escolaridade" class="form-control" required>
                <option value="">Selecione</option>
                <option>Ensino Fundamental Incompleto</option>
                <option>Ensino Fundamental Completo</option>
                <option>Ensino Médio Incompleto</option>
                <option>Ensino Médio Completo</option>
                <option>Ensino Superior</option>
                <option>Outro</option>
            </select>
        </div>

        <div class="mb-3" id="escolaridade_outro_div" style="display:none;">
            <label>Qual?</label>
            <input type="text" name="escolaridade_outro" class="form-control">
        </div>

        <!-- RENDA -->
        <div class="mb-3">
            <label>Renda Familiar</label>
            <select name="renda" id="renda" class="form-control" required>
                <option value="">Selecione</option>
                <option>Não tem</option>
                <option>Até 1 SM</option>
                <option>De 1 a 2 SM</option>
                <option>De 2 a 3 SM</option>
                <option>Acima de 3 SM</option>
                <option>Outro</option>
            </select>
        </div>

        <div class="mb-3" id="renda_outro_div" style="display:none;">
            <label>Qual?</label>
            <input type="text" name="renda_outro" class="form-control">
        </div>

        <!-- TRABALHO -->
        <div class="mb-3">
            <label>Trabalho/Ocupação</label>
            <select name="trabalho" id="trabalho" class="form-control" required>
                <option value="">Selecione</option>
                <option>Empregado Carteira Assinada</option>
                <option>Empregado Sem Carteira Assinada</option>
                <option>Autônomo</option>
                <option>Trabalha em Casa</option>
                <option>Nunca Trabalhou</option>
                <option>Outro</option>
            </select>
        </div>

        <div class="mb-3" id="trabalho_outro_div" style="display:none;">
            <label>Qual?</label>
            <input type="text" name="trabalho_outro" class="form-control">
        </div>

        <!-- PROFISSÃO -->
        <div class="mb-3">
            <label>Profissão</label>
            <input type="text" name="profissao" class="form-control" required>
        </div>

        <!-- MORADIA -->
        <div class="mb-3">
            <label>Condição da Moradia</label>
            <select name="moradia" id="moradia" class="form-control" required>
                <option value="">Selecione</option>
                <option>Própria</option>
                <option>Alugada</option>
                <option>Cedida</option>
                <option>Outro</option>
            </select>
        </div>

        <div class="mb-3" id="moradia_outro_div" style="display:none;">
            <label>Qual?</label>
            <input type="text" name="moradia_outro" class="form-control">
        </div>

        <button type="submit" class="btn btn-success w-100">
            Salvar Ficha
        </button>

    </form>
</div>

<!-- SCRIPT GLOBAL -->
<script>
function ativarOutro(selectId, divId) {
    document.getElementById(selectId).addEventListener('change', function () {
        const div = document.getElementById(divId);
        const input = div.querySelector('input');

        if (this.value === 'Outro' || this.value === 'Outra') {
            div.style.display = 'block';
            input.required = true;
        } else {
            div.style.display = 'none';
            input.required = false;
        }
    });
}

// Ativar todos
ativarOutro('relacionamento', 'relacionamento_outro_div');
ativarOutro('relacao_com', 'relacao_com_outro_div');
ativarOutro('religiao', 'religiao_outro_div');
ativarOutro('escolaridade', 'escolaridade_outro_div');
ativarOutro('renda', 'renda_outro_div');
ativarOutro('trabalho', 'trabalho_outro_div');
ativarOutro('moradia', 'moradia_outro_div');
</script>

<?php require_once __DIR__ . '/../../layout/admin_footer.php'; ?>