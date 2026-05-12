<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../layout/admin_header.php';

auth();
canAny(['admin', 'atendente']);

$participante_id = $_GET['id'] ?? $_GET['participante_id'] ?? null;

if (!$participante_id) die("Participante não informado");

// BUSCA DADOS EXISTENTES
$stmt = $pdo->prepare("SELECT * FROM ficha_inclusao WHERE participante_id = ?");
$stmt->execute([$participante_id]);
$dados = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$dados) die("Ficha não encontrada para edição.");

// Função auxiliar para marcar o SELECT
function selecionar($valor, $opcao) {
    return (trim($valor) == trim($opcao)) ? 'selected' : '';
}
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold text-dark mb-1">📝 Editar Ficha de Inclusão</h3>
                    <p class="text-muted">Participante: <strong><?= htmlspecialchars($dados['nome']) ?></strong></p>
                </div>
                <a href="participantes_detalhes.php?id=<?= $participante_id ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>
            </div>

            <form method="POST" action="ficha_inclusao_salvar.php" class="card shadow-sm border-0">
                <input type="hidden" name="participante_id" value="<?= $participante_id ?>">

                <div class="card-body p-4">
                    
                    <h5 class="text-primary border-bottom pb-2 mb-3">1. Identificação do Caso</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Número do Caso</label>
                            <input type="text" name="numero_caso" class="form-control" value="<?= htmlspecialchars($dados['numero_caso'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Número do Processo</label>
                            <input type="text" name="numero_processo" class="form-control" value="<?= htmlspecialchars($dados['numero_processo'] ?? '') ?>" required>
                        </div>
                    </div>

                    <h5 class="text-primary border-bottom pb-2 mb-3">2. Dados Pessoais</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-8">
                            <label class="form-label fw-bold">Nome Completo</label>
                            <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($dados['nome'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Idade</label>
                            <input type="number" name="idade" class="form-control" value="<?= htmlspecialchars($dados['idade'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Parentesco com a denunciante</label>
                            <input type="text" name="parentesco" class="form-control" value="<?= htmlspecialchars($dados['parentesco'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Naturalidade</label>
                            <input type="text" name="naturalidade" class="form-control" value="<?= htmlspecialchars($dados['naturalidade'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Cor/Raça</label>
                            <select name="cor" class="form-select" required>
                                <option value="">Selecione</option>
                                <?php $cores = ['Branca', 'Preta', 'Parda', 'Indígena', 'Amarela'];
                                foreach ($cores as $c) echo "<option value='$c' " . selecionar($dados['cor'], $c) . ">$c</option>"; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Religião</label>
                            <select name="religiao" id="religiao" class="form-select" required>
                                <option value="">Selecione</option>
                                <?php $religioes = ['Católica', 'Evangélica', 'Espírita', 'Umbanda', 'Candomblé', 'Outra'];
                                foreach ($religioes as $rel) echo "<option value='$rel' " . selecionar($dados['religiao'], $rel) . ">$rel</option>"; ?>
                            </select>
                            <div class="mt-2" id="religiao_outro_div" style="display: <?= ($dados['religiao'] == 'Outra') ? 'block' : 'none' ?>;">
                                <input type="text" name="religiao_outro" class="form-control" placeholder="Qual religião?" value="<?= htmlspecialchars($dados['religiao_outro'] ?? '') ?>">
                            </div>
                        </div>
                    </div>

                    <h5 class="text-primary border-bottom pb-2 mb-3">3. Relacionamento</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Relacionamento atual</label>
                            <select name="relacionamento" id="relacionamento" class="form-select" required>
                                <option value="">Selecione</option>
                                <?php $rels = ['Solteiro', 'Namorando', 'Casado', 'Divorciado', 'Viúvo', 'Outro'];
                                foreach ($rels as $r) echo "<option value='$r' " . selecionar($dados['relacionamento'], $r) . ">$r</option>"; ?>
                            </select>
                            <div class="mt-2" id="relacionamento_outro_div" style="display: <?= ($dados['relacionamento'] == 'Outro') ? 'block' : 'none' ?>;">
                                <input type="text" name="relacionamento_outro" class="form-control" placeholder="Especifique" value="<?= htmlspecialchars($dados['relacionamento_outro'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Relacionamento com</label>
                            <select name="relacao_com" id="relacao_com" class="form-select">
                                <option value="">Selecione</option>
                                <?php $relcom = ['Com a denunciante', 'Com outra pessoa', 'Outro'];
                                foreach ($relcom as $rc) echo "<option value='$rc' " . selecionar($dados['relacao_com'], $rc) . ">$rc</option>"; ?>
                            </select>
                            <div class="mt-2" id="relacao_com_outro_div" style="display: <?= ($dados['relacao_com'] == 'Outro') ? 'block' : 'none' ?>;">
                                <input type="text" name="relacao_com_outro" class="form-control" placeholder="Especifique" value="<?= htmlspecialchars($dados['relacao_com_outro'] ?? '') ?>">
                            </div>
                        </div>
                    </div>

                    <h5 class="text-primary border-bottom pb-2 mb-3">4. Perfil Socioeconômico</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Escolaridade</label>
                            <select name="escolaridade" id="escolaridade" class="form-select" required>
                                <option value="">Selecione</option>
                                <?php $escola = ['Ensino Fundamental Incompleto', 'Ensino Fundamental Completo', 'Ensino Médio Incompleto', 'Ensino Médio Completo', 'Ensino Superior', 'Outro'];
                                foreach ($escola as $e) echo "<option value='$e' " . selecionar($dados['escolaridade'], $e) . ">$e</option>"; ?>
                            </select>
                            <div class="mt-2" id="escolaridade_outro_div" style="display: <?= ($dados['escolaridade'] == 'Outro') ? 'block' : 'none' ?>;">
                                <input type="text" name="escolaridade_outro" class="form-control" placeholder="Qual?" value="<?= htmlspecialchars($dados['escolaridade_outro'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Renda Familiar</label>
                            <select name="renda" id="renda" class="form-select" required>
                                <option value="">Selecione</option>
                                <?php $rendas = ['Não tem', 'Até 1 SM', 'De 1 a 2 SM', 'De 2 a 3 SM', 'Acima de 3 SM', 'Outro'];
                                foreach ($rendas as $rn) echo "<option value='$rn' " . selecionar($dados['renda'], $rn) . ">$rn</option>"; ?>
                            </select>
                            <div class="mt-2" id="renda_outro_div" style="display: <?= ($dados['renda'] == 'Outro') ? 'block' : 'none' ?>;">
                                <input type="text" name="renda_outro" class="form-control" placeholder="Especifique" value="<?= htmlspecialchars($dados['renda_outro'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Trabalho/Ocupação</label>
                            <select name="trabalho" id="trabalho" class="form-select" required>
                                <option value="">Selecione</option>
                                <?php $trab = ['Empregado Carteira Assinada', 'Empregado Sem Carteira Assinada', 'Autônomo', 'Trabalha em Casa', 'Nunca Trabalhou', 'Outro'];
                                foreach ($trab as $t) echo "<option value='$t' " . selecionar($dados['trabalho'], $t) . ">$t</option>"; ?>
                            </select>
                            <div class="mt-2" id="trabalho_outro_div" style="display: <?= ($dados['trabalho'] == 'Outro') ? 'block' : 'none' ?>;">
                                <input type="text" name="trabalho_outro" class="form-control" placeholder="Especifique" value="<?= htmlspecialchars($dados['trabalho_outro'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Profissão</label>
                            <input type="text" name="profissao" class="form-control" value="<?= htmlspecialchars($dados['profissao'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Condição da Moradia</label>
                            <select name="moradia" id="moradia" class="form-select" required>
                                <option value="">Selecione</option>
                                <?php $mora = ['Própria', 'Alugada', 'Cedida', 'Outro'];
                                foreach ($mora as $m) echo "<option value='$m' " . selecionar($dados['moradia'], $m) . ">$m</option>"; ?>
                            </select>
                            <div class="mt-2" id="moradia_outro_div" style="display: <?= ($dados['moradia'] == 'Outro') ? 'block' : 'none' ?>;">
                                <input type="text" name="moradia_outro" class="form-control" placeholder="Especifique" value="<?= htmlspecialchars($dados['moradia_outro'] ?? '') ?>">
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                            <i class="bi bi-check-circle me-2"></i>Atualizar Ficha de Inclusão
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Função genérica para ativar campos "Outros"
    function configurarCampoOutro(selectId, divId) {
        const selectEl = document.getElementById(selectId);
        const divEl = document.getElementById(divId);
        if (!selectEl || !divEl) return;

        selectEl.addEventListener('change', function() {
            const input = divEl.querySelector('input');
            if (this.value === 'Outro' || this.value === 'Outra') {
                divEl.style.display = 'block';
                input.focus();
            } else {
                divEl.style.display = 'none';
                input.value = ''; // Limpa o campo se desmarcar "Outro"
            }
        });
    }

    // Inicializar os campos
    configurarCampoOutro('relacionamento', 'relacionamento_outro_div');
    configurarCampoOutro('relacao_com', 'relacao_com_outro_div');
    configurarCampoOutro('religiao', 'religiao_outro_div');
    configurarCampoOutro('escolaridade', 'escolaridade_outro_div');
    configurarCampoOutro('renda', 'renda_outro_div');
    configurarCampoOutro('trabalho', 'trabalho_outro_div');
    configurarCampoOutro('moradia', 'moradia_outro_div');
</script>

<?php require_once __DIR__ . '/../layout/admin_footer.php'; ?>