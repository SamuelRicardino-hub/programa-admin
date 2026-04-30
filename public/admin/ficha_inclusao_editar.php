<?php
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../layout/admin_header.php';

auth();

$participante_id = $_GET['id'] ?? $_GET['participante_id'] ?? null;

if (!$participante_id) die("Participante não informado");

// BUSCA DADOS EXISTENTES
$stmt = $pdo->prepare("SELECT * FROM ficha_inclusao WHERE participante_id = ?");
$stmt->execute([$participante_id]);
$dados = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$dados) die("Ficha não encontrada para edição.");

// Função auxiliar para marcar o SELECT
function selecionar($valor, $opcao)
{
    return ($valor == $opcao) ? 'selected' : '';
}
?>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>📝 Editar Ficha de Inclusão</h3>
        <a href="participantes_detalhes.php?id=<?= $participante_id ?>" class="btn btn-secondary btn-sm">Voltar</a>
    </div>

    <form method="POST" action="ficha_inclusao_salvar.php">

        <input type="hidden" name="participante_id" value="<?= $participante_id ?>">

        <div class="mb-3">
            <label>Número do Caso</label>
            <input type="text" name="numero_caso" class="form-control" value="<?= htmlspecialchars($dados['numero_caso'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label>Número do Processo</label>
            <input type="text" name="numero_processo" class="form-control" value="<?= htmlspecialchars($dados['numero_processo'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label>Nome Completo</label>
            <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($dados['nome'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label>Grau de parentesco com a denunciante</label>
            <input type="text" name="parentesco" class="form-control" value="<?= htmlspecialchars($dados['parentesco'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label>Idade</label>
            <input type="number" name="idade" class="form-control" value="<?= htmlspecialchars($dados['idade'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label>Naturalidade</label>
            <input type="text" name="naturalidade" class="form-control" value="<?= htmlspecialchars($dados['naturalidade'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label>Cor</label>
            <select name="cor" id="cor" class="form-control" required>
                <option value="">Selecione</option>
                <?php $cores = ['Branca', 'Preta', 'Parda', 'Indígena', 'Amarela'];
                foreach ($cores as $c) echo "<option " . selecionar($dados['cor'], $c) . ">$c</option>"; ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Relacionamento atual</label>
            <select name="relacionamento" id="relacionamento" class="form-control" required>
                <option value="">Selecione</option>
                <?php $rels = ['Solteiro', 'Namorando', 'Casado', 'Divorciado', 'Viúvo', 'Outro'];
                foreach ($rels as $r) echo "<option " . selecionar($dados['relacionamento'], $r) . ">$r</option>"; ?>
            </select>
        </div>

        <div class="mb-3" id="relacionamento_outro_div" style="display: <?= (($dados['relacionamento'] ?? '') === 'Outro') ? 'block' : 'none' ?>;">
            <label>Qual?</label>
            <input type="text" name="relacionamento_outro" class="form-control" value="<?= htmlspecialchars($dados['relacionamento_outro'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label>Relacionamento com</label>
            <select name="relacao_com" id="relacao_com" class="form-control">
                <option value="">Selecione</option>
                <option <?= selecionar($dados['relacao_com'], 'Com a denunciante') ?>>Com a denunciante</option>
                <option <?= selecionar($dados['relacao_com'], 'Com outra pessoa') ?>>Com outra pessoa</option>
                <option <?= selecionar($dados['relacao_com'], 'Outro') ?>>Outro</option>
            </select>
        </div>

        <div class="mb-3" id="relacao_com_outro_div" style="display: <?= (isset($dados['relacionamento']) && $dados['relacionamento'] === 'Outro') ? 'block' : 'none' ?>";>
            <label>Qual?</label>
            <input type="text" name="relacao_com_outro" class="form-control" value="<?= htmlspecialchars($dados['relacao_com_outro'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label>Religião</label>
            <select name="religiao" id="religiao" class="form-control" required>
                <option value="">Selecione</option>
                <?php $religiao = ['Católica', 'Evangélica', 'Espírita', 'Umbanda', 'Candomblé', 'Outra'];
                foreach ($religiao as $rel) echo "<option " . selecionar($dados['religiao'], $rel) . ">$rel</option>"; ?>
            </select>
        </div>

        <div class="mb-3" id="religiao_outro_div" style="display: <?= ($dados['religiao'] == 'Outra') ? 'block' : 'none' ?>;">
            <label>Qual?</label>
            <input type="text" name="religiao_outro" class="form-control" value="<?= htmlspecialchars($dados['religiao_outro'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label>Escolaridade</label>
            <select name="escolaridade" id="escolaridade" class="form-control" required>
                <option value="">Selecione</option>
                <option <?= selecionar($dados['escolaridade'], 'Ensino Fundamental Incompleto') ?>>Ensino Fundamental Incompleto</option>
                <option <?= selecionar($dados['escolaridade'], 'Ensino Fundamental Completo') ?>>Ensino Fundamental Completo</option>
                <option <?= selecionar($dados['escolaridade'], 'Ensino Médio Incompleto') ?>>Ensino Médio Incompleto</option>
                <option <?= selecionar($dados['escolaridade'], 'Ensino Médio Completo') ?>>Ensino Médio Completo</option>
                <option <?= selecionar($dados['escolaridade'], 'Ensino Superior') ?>>Ensino Superior</option>
                <option <?= selecionar($dados['escolaridade'], 'Outro') ?>>Outro</option>
            </select>
        </div>

        <div class="mb-3" id="escolaridade_outro_div" style="display: <?= ($dados['escolaridade'] == 'Outro') ? 'block' : 'none' ?>;">
            <label>Qual?</label>
            <input type="text" name="escolaridade_outro" class="form-control" value="<?= htmlspecialchars($dados['escolaridade_outro'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label>Renda Familiar</label>
            <select name="renda" id="renda" class="form-control" required>
                <option value="">Selecione</option>
                <option <?= selecionar($dados['renda'], 'Não tem') ?>>Não tem</option>
                <option <?= selecionar($dados['renda'], 'Até 1 SM') ?>>Até 1 SM</option>
                <option <?= selecionar($dados['renda'], 'De 1 a 2 SM') ?>>De 1 a 2 SM</option>
                <option <?= selecionar($dados['renda'], 'De 2 a 3 SM') ?>>De 2 a 3 SM</option>
                <option <?= selecionar($dados['renda'], 'Acima de 3 SM') ?>>Acima de 3 SM</option>
                <option <?= selecionar($dados['renda'], 'Outro') ?>>Outro</option>
            </select>
        </div>

        <div class="mb-3" id="renda_outro_div" style="display: <?= (isset($dados['religiao']) && $dados['religiao'] === 'Outra') ? 'block' : 'none' ?>;">
            <label>Qual?</label>
            <input type="text" name="renda_outro" class="form-control" value="<?= htmlspecialchars($dados['renda_outro'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label>Trabalho/Ocupação</label>
            <select name="trabalho" id="trabalho" class="form-control" required>
                <option value="">Selecione</option>
                <option <?= selecionar($dados['trabalho'], 'Empregado Carteira Assinada') ?>>Empregado Carteira Assinada</option>
                <option <?= selecionar($dados['trabalho'], 'Empregado Sem Carteira Assinada') ?>>Empregado Sem Carteira Assinada</option>
                <option <?= selecionar($dados['trabalho'], 'Autônomo') ?>>Autônomo</option>
                <option <?= selecionar($dados['trabalho'], 'Trabalha em Casa') ?>>Trabalha em Casa</option>
                <option <?= selecionar($dados['trabalho'], 'Nunca Trabalhou') ?>>Nunca Trabalhou</option>
                <option <?= selecionar($dados['trabalho'], 'Outro') ?>>Outro</option>
            </select>
        </div>

        <div class="mb-3" id="trabalho_outro_div" style="display: <?= ($dados['trabalho'] == 'Outro') ? 'block' : 'none' ?>;">
            <label>Qual?</label>
            <input type="text" name="trabalho_outro" class="form-control" value="<?= htmlspecialchars($dados['trabalho_outro'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label>Profissão</label>
            <input type="text" name="profissao" class="form-control" value="<?= htmlspecialchars($dados['profissao'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label>Condição da Moradia</label>
            <select name="moradia" id="moradia" class="form-control" required>
                <option value="">Selecione</option>
                <option <?= selecionar($dados['moradia'], 'Própria') ?>>Própria</option>
                <option <?= selecionar($dados['moradia'], 'Alugada') ?>>Alugada</option>
                <option <?= selecionar($dados['moradia'], 'Cedida') ?>>Cedida</option>
                <option <?= selecionar($dados['moradia'], 'Outro') ?>>Outro</option>
            </select>
        </div>

        <div class="mb-3" id="moradia_outro_div" style="display: <?= (isset($dados['NOME_DO_CAMPO']) && $dados['NOME_DO_CAMPO'] === 'Outro') ? 'block' : 'none' ?>;">
            <label>Qual?</label>
            <input type="text" name="moradia_outro" class="form-control" value="<?= htmlspecialchars($dados['moradia_outro'] ?? '') ?>">
        </div>

        <button type="submit" class="btn btn-primary w-100 mb-4">
            Atualizar Ficha de Inclusão
        </button>

    </form>
</div>

<script>
    function ativarOutro(selectId, divId) {
        const selectEl = document.getElementById(selectId);
        const divEl = document.getElementById(divId);

        selectEl.addEventListener('change', function() {
            const input = divEl.querySelector('input');
            if (this.value === 'Outro' || this.value === 'Outra') {
                divEl.style.display = 'block';
                input.required = true;
            } else {
                divEl.style.display = 'none';
                input.required = false;
            }
        });
    }

    ativarOutro('relacionamento', 'relacionamento_outro_div');
    ativarOutro('relacao_com', 'relacao_com_outro_div');
    ativarOutro('religiao', 'religiao_outro_div');
    ativarOutro('escolaridade', 'escolaridade_outro_div');
    ativarOutro('renda', 'renda_outro_div');
    ativarOutro('trabalho', 'trabalho_outro_div');
    ativarOutro('moradia', 'moradia_outro_div');
</script>

<?php require_once __DIR__ . '/../../layout/admin_footer.php'; ?>