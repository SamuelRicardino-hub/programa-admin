<?php
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../config/auth.php';

auth();
canAny(['admin', 'atendente']);

// ==============================
// 🔒 VALIDAR ID
// ==============================
$participante_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$participante_id) {
    header("Location: casos_lista.php");
    exit;
}

// ==============================
// 🔍 BUSCAR VÍTIMA
// ==============================
$stmt = $pdo->prepare("
    SELECT nome, cpf 
    FROM participantes 
    WHERE id = ? AND tipo = 'vitima'
");
$stmt->execute([$participante_id]);

$participante = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$participante) {
    die("Participante não encontrado");
}

require_once __DIR__ . '/../../layout/admin_header.php';
?>

<div class="container mt-4">

    <h3>Ficha de Inclusão</h3>

    <p>
        <strong><?= htmlspecialchars($participante['nome']) ?></strong> 
        (CPF: <?= htmlspecialchars($participante['cpf']) ?>)
    </p>

    <form method="POST" action="ficha_inclusao_salvar.php">

        <input type="hidden" name="participante_id" value="<?= $participante_id ?>">

        <div class="row">

            <h5 class="mt-3">Dados Sociais</h5>

            <div class="col-md-4 mb-3">
                <label>Cor</label>
                <input type="text" name="cor" class="form-control">
            </div>

            <div class="col-md-4 mb-3">
                <label>Estado Civil</label>
                <input type="text" name="situacao_civil" class="form-control">
            </div>

            <div class="col-md-4 mb-3">
                <label>Religião</label>
                <input type="text" name="religiao" class="form-control">
            </div>

            <div class="col-md-4 mb-3">
                <label>Escolaridade</label>
                <input type="text" name="escolaridade" class="form-control">
            </div>

            <div class="col-md-4 mb-3">
                <label>Profissão</label>
                <input type="text" name="profissao" class="form-control">
            </div>

            <div class="col-md-4 mb-3">
                <label>Ocupação</label>
                <input type="text" name="ocupacao" class="form-control">
            </div>

            <hr>

            <h5 class="mt-3">Família e Moradia</h5>

            <div class="col-md-4 mb-3">
                <label>Renda Familiar</label>
                <input type="text" name="renda_familiar" class="form-control">
            </div>

            <div class="col-md-4 mb-3">
                <label>Condição de Moradia</label>
                <input type="text" name="condicao_moradia" class="form-control">
            </div>

            <div class="col-md-2 mb-3">
                <label>Nº Filhos</label>
                <input type="number" name="numero_filhos" class="form-control">
            </div>

            <div class="col-md-2 mb-3">
                <label>Pessoas na casa</label>
                <input type="number" name="numero_pessoas_casa" class="form-control">
            </div>

            <hr>

            <h5 class="mt-3">Saúde</h5>

            <div class="col-md-6 mb-3">
                <label>Problemas de saúde</label>
                <textarea name="problemas_saude" class="form-control"></textarea>
            </div>

            <div class="col-md-6 mb-3">
                <label>Uso de medicação</label>
                <textarea name="uso_medicacao" class="form-control"></textarea>
            </div>

            <hr>

            <h5 class="mt-3">Uso de Substâncias</h5>

            <div class="col-md-4 mb-3">
                <label>Uso de álcool</label>
                <input type="text" name="uso_alcool" class="form-control">
            </div>

            <div class="col-md-4 mb-3">
                <label>Frequência</label>
                <input type="text" name="frequencia_bebida" class="form-control">
            </div>

            <div class="col-md-4 mb-3">
                <label>Drogas utilizadas</label>
                <input type="text" name="drogas_utilizadas" class="form-control">
            </div>

            <hr>

            <h5 class="mt-3">Contexto Social</h5>

            <div class="col-md-6 mb-3">
                <label>Violência praticada</label>
                <textarea name="violencia_praticada" class="form-control"></textarea>
            </div>

            <div class="col-md-6 mb-3">
                <label>Violência sofrida</label>
                <textarea name="violencia_sofrida" class="form-control"></textarea>
            </div>

            <div class="col-md-6 mb-3">
                <label>Histórico familiar</label>
                <textarea name="historico_familiar" class="form-control"></textarea>
            </div>

            <div class="col-md-6 mb-3">
                <label>Situação jurídica</label>
                <textarea name="situacao_juridica" class="form-control"></textarea>
            </div>

            <hr>

            <h5 class="mt-3">Grupo Reflexivo</h5>

            <div class="col-12 mb-3">
                <label>Expectativa com o grupo</label>
                <textarea name="expectativa_grupo" class="form-control"></textarea>
            </div>

            <button class="btn btn-success">Salvar</button>

        </div>
    </form>

</div>

<?php require_once __DIR__ . '/../../layout/admin_footer.php'; ?>