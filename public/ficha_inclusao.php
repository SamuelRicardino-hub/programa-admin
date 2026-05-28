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
                                <select name="relacionamento" id="relacionamento" class="form-select" required onchange="toggleRelacionamentoEstrito(this.value)">
                                    <option value="">Selecione</option>
                                    <option value="Solteiro">Solteiro</option>
                                    <option value="Namorando">Namorando</option>
                                    <option value="Casado">Casado</option>
                                    <option value="Divorciado">Divorciado</option>
                                    <option value="Viúvo">Viúvo</option>
                                    <option value="Outro">Outro</option>
                                </select>
                            </div>

                            <div id="secao_relacionamento_ativo" style="display:none;" class="row g-3 mt-1">
                                <div class="col-md-6">
                                    <label class="form-label small">Relacionamento com:</label>
                                    <select name="relacao_com" class="form-select">
                                        <option value="">Selecione</option>
                                        <option>Com a denunciante</option>
                                        <option>Com outra pessoa</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small">Como é o relacionamento com a companheira atual?</label>
                                    <select name="relacionamento_parceira_atual" class="form-select">
                                        <option>Ótimo</option>
                                        <option>Bom</option>
                                        <option>Razoável</option>
                                        <option>Ruim</option>
                                    </select>
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

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <div class="section-title">Relações Familiares e Paternidade</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small">N° de Filhos/as</label>
                                <input type="number" name="qtd_filhos" id="qtd_filhos" class="form-control" min="0" value="0" required onchange="togglePaternidade(this.value)">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">N° de pessoas que vivem na mesma casa</label>
                                <input type="number" name="pessoas_na_casa" class="form-control" min="1" required>
                            </div>

                            <div id="secao_filhos" style="display:none;" class="row g-3 mt-1">
                                <div class="col-md-6">
                                    <label class="form-label small">Filhos com a atual companheira?</label>
                                    <input type="text" name="filhos_com_atual" class="form-control" placeholder="Quantos?">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small">Filhos com a denunciante?</label>
                                    <input type="text" name="filhos_com_denunciante" class="form-control" placeholder="Quantos?">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label small">Frequência em que vê os filhos que não moram com você:</label>
                                    <select name="frequencia_ver_filhos" class="form-select">
                                        <option value="">Selecione</option>
                                        <option>Diariamente</option>
                                        <option>Semanalmente</option>
                                        <option>15/15 dias</option>
                                        <option>Mensalmente</option>
                                        <option>Raramente</option>
                                        <option>Medida Protetiva - Proibida visitação</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small">Conversam sobre a criação dos filhos? (Últimos 2 anos)</label>
                                    <select name="conversa_criacao_filhos" class="form-select">
                                        <option value="Nunca">Nunca</option>
                                        <option>Raramente</option>
                                        <option>Algumas vezes</option>
                                        <option>Frequentemente</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small">Auxilia com as lições de casa?</label>
                                    <select name="auxilio_licoes_casa" class="form-select">
                                        <option value="Ainda não estuda">Ainda não estuda</option>
                                        <option>Nunca</option>
                                        <option>Raramente</option>
                                        <option>Algumas vezes</option>
                                        <option>Frequentemente</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label small">Você vai às reuniões de pais da escola?</label>
                                <select name="reunioes_escola" class="form-select">
                                    <option value="Ainda não estuda">Ainda não estuda</option>
                                    <option>Nunca</option>
                                    <option>Raramente</option>
                                    <option>Algumas vezes</option>
                                    <option>Frequentemente</option>
                                </select>
                            </div>

                            <div class="col-md-6 border-top pt-3 mt-4">
                                <label class="form-label small">Divide os trabalhos domésticos?</label>
                                <select name="divisao_domestica" class="form-select">
                                    <option>Moro sozinho</option>
                                    <option>Nunca</option>
                                    <option>Raramente</option>
                                    <option>Algumas vezes</option>
                                    <option>Frequentemente</option>
                                </select>
                            </div>
                            <div class="col-md-6 border-top pt-3 mt-4">
                                <label class="form-label small">Relacionamento com a companheira atual:</label>
                                <select name="relacionamento_parceira_atual" class="form-select">
                                    <option>Não possuo</option>
                                    <option>Ótimo</option>
                                    <option>Bom</option>
                                    <option>Razoável</option>
                                    <option>Ruim</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <div class="section-title">Condições de Saúde e Hábitos</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small">Possui problemas de saúde? Quais?</label>
                                <textarea name="problemas_saude" class="form-control" rows="2" placeholder="Descreva se houver"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">Faz uso de medicação? Qual?</label>
                                <textarea name="medicacao" class="form-control" rows="2" placeholder="Descreva se houver"></textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small">Frequenta bares?</label>
                                <select name="frequencia_bares" class="form-select">
                                    <option>Nunca vai</option>
                                    <option>1 vez ao mês</option>
                                    <option>1 vez na semana</option>
                                    <option>3 vezes na semana</option>
                                    <option>Todo dia</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label small d-block">Costuma beber:</label>
                                <div class="d-flex flex-wrap gap-3 mt-2">
                                    <?php foreach (['Cerveja', 'Cachaça', 'Whisky', 'Vinho', 'Vodka'] as $bebida): ?>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="bebidas[]" value="<?= $bebida ?>" id="b_<?= $bebida ?>">
                                            <label class="form-check-label" for="b_<?= $bebida ?>"><?= $bebida ?></label>
                                        </div>
                                    <?php endforeach; ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="check_bebida_outro" onchange="document.getElementById('bebida_outro_input').style.display = this.checked ? 'block' : 'none'">
                                        <label class="form-check-label">Outro</label>
                                    </div>
                                </div>
                                <input type="text" name="bebida_outro" id="bebida_outro_input" class="form-control form-control-sm mt-2" style="display:none;" placeholder="Qual?">
                            </div>

                            <div class="col-md-12 mt-3">
                                <label class="form-label small d-block">Quais drogas já utilizou:</label>
                                <div class="d-flex flex-wrap gap-3 mt-2">
                                    <?php foreach (['Cigarro', 'Maconha', 'Crack', 'Cola', 'Cocaína', 'Nenhuma'] as $droga): ?>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="drogas[]" value="<?= $droga ?>" id="d_<?= $droga ?>">
                                            <label class="form-check-label" for="d_<?= $droga ?>"><?= $droga ?></label>
                                        </div>
                                    <?php endforeach; ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="check_droga_outro" onchange="document.getElementById('droga_outro_input').style.display = this.checked ? 'block' : 'none'">
                                        <label class="form-check-label">Outra</label>
                                    </div>
                                </div>
                                <input type="text" name="droga_outro" id="droga_outro_input" class="form-control form-control-sm mt-2" style="display:none;" placeholder="Qual?">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <div class="section-title">Histórico de Violência</div>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label small">Durante o último ano você praticou algum tipo de violência?</label>
                                <select name="praticou_violencia_ultimo_ano" id="praticou_violencia" class="form-select" onchange="toggleDetalhamentoViolencia(this.value)">
                                    <option value="Não">Não</option>
                                    <option value="Em um homem">Em um homem</option>
                                    <option value="Em uma mulher">Em uma mulher</option>
                                    <option value="Em ambos">Em ambos</option>
                                </select>
                            </div>

                            <div id="detalhes_violencia_praticada" style="display:none;" class="row g-3 mt-1 bg-light p-3 rounded border mx-0">

                                <div class="col-md-12">
                                    <label class="form-label small d-block fw-bold text-primary">Em quem?</label>
                                    <div class="d-flex flex-wrap gap-3">
                                        <?php
                                        $vitimas = ['Pai', 'Mãe', 'Madrasta', 'Padrasto', 'Filho/a', 'Irmão/irmã', 'Amigo/a', 'Chefe', 'Desconhecido'];
                                        foreach ($vitimas as $v): ?>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="vitimas[]" value="<?= $v ?>" id="v_<?= $v ?>">
                                                <label class="form-check-label small" for="v_<?= $v ?>"><?= $v ?></label>
                                            </div>
                                        <?php endforeach; ?>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="check_vitima_outro" onchange="document.getElementById('vitima_outro_input').style.display = this.checked ? 'block' : 'none'">
                                            <label class="form-check-label small">Outro</label>
                                        </div>
                                    </div>
                                    <input type="text" name="vitima_outro" id="vitima_outro_input" class="form-control form-control-sm mt-2" style="display:none;" placeholder="Especifique quem">
                                </div>

                                <div class="col-md-12 mt-3">
                                    <label class="form-label small d-block fw-bold text-primary">Que tipo de violência você praticou?</label>
                                    <div class="row">
                                        <?php
                                        $tipos_v = [
                                            'Física' => 'Agressão física',
                                            'Sexual' => 'Sexo forçado/agressão aos órgãos genitais/negação de proteção sexual',
                                            'Psicológica' => 'Ameaçar - Gritar - Insultar ou Humilhar - Proibir amizades/estudos/trabalho',
                                            'Moral' => 'Ofensas contra a honra e reputação visando o descrédito da imagem',
                                            'Patrimonial' => 'Reter, subtrair ou destruir bens/valores/objetos pessoais'
                                        ];
                                        foreach ($tipos_v as $titulo => $desc): ?>
                                            <div class="col-md-6 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="tipos_v_praticada[]" value="<?= $titulo ?>" id="tv_<?= $titulo ?>">
                                                    <label class="form-check-label small" for="tv_<?= $titulo ?>">
                                                        <strong><?= $titulo ?></strong> <br>
                                                        <span class="text-muted" style="font-size: 0.75rem;"><?= $desc ?></span>
                                                    </label>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="check_tipo_v_outro" onchange="document.getElementById('tipo_v_outro_input').style.display = this.checked ? 'block' : 'none'">
                                                <label class="form-check-label small">Outro</label>
                                            </div>
                                            <input type="text" name="tipo_v_outro" id="tipo_v_outro_input" class="form-control form-control-sm mt-1" style="display:none;" placeholder="Especifique o tipo">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <div class="section-title">Situação Jurídica e Histórico Penal</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small">Já foi indiciado por violência contra a mulher anteriormente?</label>
                                <select name="indiciado_anteriormente" class="form-select" onchange="toggleIndiciadoAnterior(this.value)">
                                    <option value="Não">Não</option>
                                    <option value="Sim">Sim</option>
                                </select>
                                <div id="campo_tipo_anterior" class="mt-2" style="display:none;">
                                    <input type="text" name="tipo_violencia_anterior" class="form-control form-control-sm border-warning" placeholder="Qual tipo de violência?">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small">Fez uso de drogas/álcool antes de cometer a violência?</label>
                                <select name="uso_drogas_antes_fato" class="form-select">
                                    <option value="Não">Não</option>
                                    <option value="Sim">Sim</option>
                                </select>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label small">Já foi indiciado por outro motivo (exceto Maria da Penha)?</label>
                                <input type="text" name="indiciado_outro_motivo" class="form-control" placeholder="Descreva se houver">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label small">Já esteve preso ou cumpriu medida socioeducativa?</label>
                                <textarea name="historico_prisao" class="form-control" rows="2" placeholder="Quando? Quanto tempo? Motivo?"></textarea>
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
    // Função para mostrar/esconder campos de paternidade
    function togglePaternidade(valor) {
        const secao = document.getElementById('secao_filhos');
        if (parseInt(valor) > 0) {
            secao.style.display = 'flex'; // Use flex para manter o padrão das colunas do Bootstrap
        } else {
            secao.style.display = 'none';
        }
    }

    function toggleRelacionamentoEstrito(valor) {
        const secaoAtiva = document.getElementById('secao_relacionamento_ativo');
        // Só mostra se NÃO for solteiro e se o valor não for vazio
        if (valor !== 'Solteiro' && valor !== '') {
            secaoAtiva.style.display = 'flex';
        } else {
            secaoAtiva.style.display = 'none';
        }
    }

    function toggleDetalhamentoViolencia(valor) {
        const div = document.getElementById('detalhes_violencia_praticada');
        // Se o valor for qualquer um dos "Sim" (homem, mulher ou ambos), mostra. Se for "Não" ou vazio, esconde.
        if (valor !== 'Não' && valor !== '') {
            div.style.display = 'block';
        } else {
            div.style.display = 'none';

            // Opcional: Desmarcar todos os checkboxes se ele mudar para "Não"
            div.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
            div.querySelectorAll('input[type="text"]').forEach(txt => {
                txt.value = '';
                txt.style.display = 'none';
            });
        }
    }

    function toggleAgressaoSofrida(valor) {
        document.getElementById('detalhes_agressao_sofrida').style.display = (valor === 'Sim') ? 'block' : 'none';
    }

    function toggleIndiciadoAnterior(valor) {
        document.getElementById('campo_tipo_anterior').style.display = (valor === 'Sim') ? 'block' : 'none';
    }

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