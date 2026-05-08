<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/auth.php';
auth();

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: participantes_lista.php");
    exit;
}

// BUSCA DADOS BÁSICOS DO PARTICIPANTE PARA PRÉ-PREENCHER
$stmt = $pdo->prepare("SELECT nome, numero_processo FROM participantes WHERE id = ?");
$stmt->execute([$id]);
$p = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$p) {
    die("Participante não encontrado.");
}

require_once __DIR__ . '/../layout/admin_header.php';
?>

<div class="container mt-4 mb-5">
    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white p-3">
            <h3 class="mb-0">📄 Ficha de Inclusão</h3>
            <small>Participante: <?= htmlspecialchars($p['nome']) ?></small>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="ficha_inclusao_salvar.php">
                <input type="hidden" name="participante_id" value="<?= $id ?>">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Número do Caso</label>
                        <input type="text" name="numero_caso" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Número do Processo</label>
                        <input type="text" name="numero_processo" class="form-control" value="<?= htmlspecialchars($p['numero_processo']) ?>" required>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="fw-bold">Nome Completo</label>
                        <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($p['nome']) ?>" required>
                    </div>

                    <div class="col-md-8 mb-3">
                        <label class="fw-bold">Grau de parentesco com a denunciante</label>
                        <input type="text" name="parentesco" class="form-control" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="fw-bold">Idade</label>
                        <input type="number" name="idade" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Naturalidade</label>
                        <input type="text" name="naturalidade" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Cor</label>
                        <select name="cor" class="form-select" required>
                            <option value="">Selecione</option>
                            <option>Branca</option>
                            <option>Preta</option>
                            <option>Parda</option>
                            <option>Indígena</option>
                            <option>Amarela</option>
                        </select>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Relacionamento atual</label>
                        <select name="relacionamento" id="relacionamento" class="form-select" required>
                            <option value="">Selecione</option>
                            <option>Solteiro</option>
                            <option>Namorando</option>
                            <option>Casado</option>
                            <option>Divorciado</option>
                            <option>Viúvo</option>
                            <option>Outro</option>
                        </select>
                        <div class="mt-2" id="relacionamento_outro_div" style="display:none;">
                            <input type="text" name="relacionamento_outro" class="form-control" placeholder="Qual?">
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Relacionamento com</label>
                        <select name="relacao_com" id="relacao_com" class="form-select">
                            <option value="">Selecione</option>
                            <option>Com a denunciante</option>
                            <option>Com outra pessoa</option>
                            <option>Outro</option>
                        </select>
                        <div class="mt-2" id="relacao_com_outro_div" style="display:none;">
                            <input type="text" name="relacao_com_outro" class="form-control" placeholder="Qual?">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Religião</label>
                        <select name="religiao" id="religiao" class="form-select" required>
                            <option value="">Selecione</option>
                            <option>Católica</option>
                            <option>Evangélica</option>
                            <option>Espírita</option>
                            <option>Umbanda</option>
                            <option>Candomblé</option>
                            <option>Outra</option>
                        </select>
                        <div class="mt-2" id="religiao_outro_div" style="display:none;">
                            <input type="text" name="religiao_outro" class="form-control" placeholder="Qual?">
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Escolaridade</label>
                        <select name="escolaridade" id="escolaridade" class="form-select" required>
                            <option value="">Selecione</option>
                            <option>E. F. Incompleto</option>
                            <option>E. F. Completo</option>
                            <option>E. M. Incompleto</option>
                            <option>E. M. Completo</option>
                            <option>Ensino Superior</option>
                            <option>Outro</option>
                        </select>
                        <div class="mt-2" id="escolaridade_outro_div" style="display:none;">
                            <input type="text" name="escolaridade_outro" class="form-control" placeholder="Qual?">
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="fw-bold">Renda Familiar</label>
                        <select name="renda" id="renda" class="form-select" required>
                            <option value="">Selecione</option>
                            <option>Não tem</option>
                            <option>Até 1 SM</option>
                            <option>De 1 a 2 SM</option>
                            <option>De 2 a 3 SM</option>
                            <option>Acima de 3 SM</option>
                            <option>Outro</option>
                        </select>
                        <div class="mt-2" id="renda_outro_div" style="display:none;">
                            <input type="text" name="renda_outro" class="form-control" placeholder="Qual?">
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="fw-bold">Trabalho/Ocupação</label>
                        <select name="trabalho" id="trabalho" class="form-select" required>
                            <option value="">Selecione</option>
                            <option>Empregado (CLT)</option>
                            <option>Sem Carteira</option>
                            <option>Autônomo</option>
                            <option>Trabalha em Casa</option>
                            <option>Outro</option>
                        </select>
                        <div class="mt-2" id="trabalho_outro_div" style="display:none;">
                            <input type="text" name="trabalho_outro" class="form-control" placeholder="Qual?">
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="fw-bold">Profissão</label>
                        <input type="text" name="profissao" class="form-control" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="fw-bold">Condição da Moradia</label>
                    <select name="moradia" id="moradia" class="form-select" required>
                        <option value="">Selecione</option>
                        <option>Própria</option>
                        <option>Alugada</option>
                        <option>Cedida</option>
                        <option>Outro</option>
                    </select>
                    <div class="mt-2" id="moradia_outro_div" style="display:none;">
                        <input type="text" name="moradia_outro" class="form-control" placeholder="Qual?">
                    </div>
                </div>

                <button type="submit" class="btn btn-success btn-lg w-100 shadow">
                    <i class="bi bi-save"></i> Salvar Ficha de Inclusão
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function ativarOutro(selectId, divId) {
    const select = document.getElementById(selectId);
    if(!select) return;
    
    select.addEventListener('change', function () {
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

ativarOutro('relacionamento', 'relacionamento_outro_div');
ativarOutro('relacao_com', 'relacao_com_outro_div');
ativarOutro('religiao', 'religiao_outro_div');
ativarOutro('escolaridade', 'escolaridade_outro_div');
ativarOutro('renda', 'renda_outro_div');
ativarOutro('trabalho', 'trabalho_outro_div');
ativarOutro('moradia', 'moradia_outro_div');
</script>

<?php require_once __DIR__ . '/../layout/admin_footer.php'; ?>