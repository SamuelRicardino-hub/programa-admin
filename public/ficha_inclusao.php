<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/auth.php';
auth();

$id = $_GET['participante_id'] ?? null;
if (!$id) {
    header("Location: participantes_lista.php");
    exit;
}

$stmt = $pdo->prepare("SELECT nome, numero_processo FROM participantes WHERE id = ?");
$stmt->execute([$id]);
$p = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$p) {
    die("Participante não encontrado.");
}

require_once __DIR__ . '/../layout/admin_header.php';
?>

<style>
    .section-title {
        border-left: 5px solid var(--ser-orange);
        padding-left: 15px;
        margin-bottom: 20px;
        color: var(--ser-blue);
        font-weight: bold;
        text-transform: uppercase;
        font-size: 0.9rem;
    }

    .form-label {
        font-weight: 600;
        color: #495057;
    }

    .card {
        border-radius: 15px;
    }
</style>

<div class="container-fluid py-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-file-earmark-medical me-2 text-primary"></i>Ficha de Inclusão
                    </h3>
                    <p class="text-muted mb-0">Preenchimento de perfil psicossocial: <strong><?= htmlspecialchars($p['nome']) ?></strong></p>
                </div>
                <a href="participantes_detalhes.php?id=<?= $id ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-x-lg me-1"></i> Cancelar
                </a>
            </div>

            <form method="POST" action="ficha_inclusao_salvar.php">
                <input type="hidden" name="participante_id" value="<?= $id ?>">

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <div class="section-title">Dados do Processo</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small">Número do Caso (Interno)</label>
                                <input type="text" name="numero_caso" class="form-control bg-light" placeholder="Ex: CASO-2026-001" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">Número do Processo (TJ)</label>
                                <input type="text" name="numero_processo" class="form-control" value="<?= htmlspecialchars($p['numero_processo']) ?>" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <div class="section-title">Identificação e Perfil</div>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label small">Nome Completo</label>
                                <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($p['nome']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">Grau de parentesco com a denunciante</label>
                                <input type="text" name="parentesco" class="form-control" placeholder="Ex: Ex-companheiro, Esposo..." required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small">Idade</label>
                                <input type="number" name="idade" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small">Cor/Raça</label>
                                <select name="cor" class="form-select" required>
                                    <option value="">Selecione</option>
                                    <option>Branca</option>
                                    <option>Preta</option>
                                    <option>Parda</option>
                                    <option>Indígena</option>
                                    <option>Amarela</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">Naturalidade (Cidade/UF)</label>
                                <input type="text" name="naturalidade" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">Religião</label>
                                <select name="religiao" id="religiao" class="form-select" required>
                                    <option value="">Selecione</option>
                                    <option>Católica</option>
                                    <option>Evangélica</option>
                                    <option>Espírita</option>
                                    <option>Umbanda</option>
                                    <option>Candomblé</option>
                                    <option>Sem Religião</option>
                                    <option>Outra</option>
                                </select>
                                <div class="mt-2 animate__animated animate__fadeIn" id="religiao_outro_div" style="display:none;">
                                    <input type="text" name="religiao_outro" class="form-control form-control-sm border-warning" placeholder="Qual religião?">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <div class="section-title">Vida Social e Relacionamentos</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small">Estado Civil / Relacionamento Atual</label>
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
                                    <input type="text" name="relacionamento_outro" class="form-control form-control-sm border-warning" placeholder="Especifique">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small">Relacionamento com:</label>
                                <select name="relacao_com" id="relacao_com" class="form-select">
                                    <option value="">Selecione</option>
                                    <option>Com a denunciante</option>
                                    <option>Com outra pessoa</option>
                                    <option>Outro</option>
                                </select>
                                <div class="mt-2" id="relacao_com_outro_div" style="display:none;">
                                    <input type="text" name="relacao_com_outro" class="form-control form-control-sm border-warning" placeholder="Qual?">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label small">Escolaridade</label>
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
                                    <input type="text" name="escolaridade_outro" class="form-control form-control-sm border-warning" placeholder="Especifique a escolaridade">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <div class="section-title">Situação Socioeconômica</div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small">Renda Familiar</label>
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
                                    <input type="text" name="renda_outro" class="form-control form-control-sm" placeholder="Especifique">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small">Vínculo de Trabalho</label>
                                <select name="trabalho" id="trabalho" class="form-select" required>
                                    <option value="">Selecione</option>
                                    <option>Empregado (CLT)</option>
                                    <option>Sem Carteira / Informal</option>
                                    <option>Autônomo</option>
                                    <option>Desempregado</option>
                                    <option>Aposentado</option>
                                    <option>Outro</option>
                                </select>
                                <div class="mt-2" id="trabalho_outro_div" style="display:none;">
                                    <input type="text" name="trabalho_outro" class="form-control form-control-sm" placeholder="Qual situação?">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small">Profissão Atual/Última</label>
                                <input type="text" name="profissao" class="form-control" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label small">Condição da Moradia</label>
                                <select name="moradia" id="moradia" class="form-select" required>
                                    <option value="">Selecione</option>
                                    <option>Própria</option>
                                    <option>Alugada</option>
                                    <option>Cedida / Casa de Parentes</option>
                                    <option>Situação de Rua</option>
                                    <option>Outro</option>
                                </select>
                                <div class="mt-2" id="moradia_outro_div" style="display:none;">
                                    <input type="text" name="moradia_outro" class="form-control form-control-sm" placeholder="Especifique a moradia">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row pb-5">
                    <div class="col-md-6 mb-2">
                        <a href="participantes_detalhes.php?id=<?= $id ?>" class="btn btn-light w-100 border">
                            <i class="bi bi-x-circle me-2"></i>Descartar Alterações
                        </a>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-success w-100 shadow-sm">
                            <i class="bi bi-check-all me-2"></i>Finalizar e Salvar Ficha
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    /**
     * Gerencia a exibição de inputs extras quando a opção "Outro" é selecionada
     */
    function setupToggleFields() {
        const configs = [{
                select: 'relacionamento',
                div: 'relacionamento_outro_div'
            },
            {
                select: 'relacao_com',
                div: 'relacao_com_outro_div'
            },
            {
                select: 'religiao',
                div: 'religiao_outro_div'
            },
            {
                select: 'escolaridade',
                div: 'escolaridade_outro_div'
            },
            {
                select: 'renda',
                div: 'renda_outro_div'
            },
            {
                select: 'trabalho',
                div: 'trabalho_outro_div'
            },
            {
                select: 'moradia',
                div: 'moradia_outro_div'
            }
        ];

        configs.forEach(conf => {
            const selectEl = document.getElementById(conf.select);
            const divEl = document.getElementById(conf.div);

            if (selectEl && divEl) {
                const inputEl = divEl.querySelector('input');

                selectEl.addEventListener('change', function() {
                    const isOther = (this.value === 'Outro' || this.value === 'Outra');
                    divEl.style.display = isOther ? 'block' : 'none';
                    inputEl.required = isOther;
                    if (isOther) inputEl.focus();
                });
            }
        });
    }

    document.addEventListener('DOMContentLoaded', setupToggleFields);
</script>

<?php require_once __DIR__ . '/../layout/admin_footer.php'; ?>