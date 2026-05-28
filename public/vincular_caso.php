<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/logs.php';
require_once __DIR__ . '/../layout/admin_header.php';

auth();
canAny(['admin', 'atendente']);

// ==============================
// 📥 RECEBER PARTICIPANTE
// ==============================
$participante_id = $_GET['participante_id'] ?? null;

if (!$participante_id) {
    die("Participante não informado");
}

// ==============================
// 🔍 BUSCAR PARTICIPANTE
// ==============================
$stmt = $pdo->prepare("SELECT * FROM participantes WHERE id = ?");
$stmt->execute([$participante_id]);
$participante = $stmt->fetch();

if (!$participante) {
    die("Participante não encontrado");
}

// ==============================
// 🚫 VALIDAR SE É AUTOR
// ==============================
if ($participante['tipo'] !== 'autor') {
    die("Este participante não é um autor");
}

// ==============================
// 🚫 VERIFICAR SE JÁ TEM CASO
// ==============================
if (!empty($participante['caso_id'])) {
    die("Este autor já está vinculado a um caso");
}

// ==============================
// 👤 DEFINIR AUTOR
// ==============================
$autor = $participante;
$autor_id = $autor['id'];

// ==============================
// 🚫 VERIFICAR FICHA FINAL
// ==============================
$stmt = $pdo->prepare("SELECT id FROM ficha_avaliacao_final WHERE participante_id = ?");
$stmt->execute([$autor_id]);

if (!$stmt->fetch()) {
    echo "<div class='alert alert-warning'>Preencha a ficha final antes de vincular.</div>";
    require_once __DIR__ . '/../../layout/admin_footer.php';
    exit;
}

// ==============================
// 📋 BUSCAR VÍTIMAS DISPONÍVEIS
// ==============================
$stmt = $pdo->query("
    SELECT p.id, p.nome 
    FROM participantes p
    INNER JOIN ficha_inclusao fi ON fi.participante_id = p.id
    WHERE p.tipo = 'vitima'
    AND p.caso_id IS NULL
");

$vitimas = $stmt->fetchAll();
?>

<div class="container mt-4">

    <h2>Vincular Autor a Vítima</h2>

    <div class="card p-3 mb-3">
        <h5>Autor</h5>
        <p><strong>Nome:</strong> <?= $autor['nome'] ?></p>
        <p><strong>CPF:</strong> <?= $autor['cpf'] ?></p>
    </div>

    <?php if (empty($vitimas)): ?>
        <div class="alert alert-warning">
            Nenhuma vítima disponível com ficha de inclusão.
        </div>
    <?php else: ?>

    <form method="POST">

        <div class="mb-3">
            <label class="form-label">Selecione a Vítima</label>
            <select name="vitima_id" class="form-select" required>
                <option value="">Selecione</option>

                <?php foreach ($vitimas as $v): ?>
                    <option value="<?= $v['id'] ?>">
                        <?= $v['nome'] ?> (ID: <?= $v['id'] ?>)
                    </option>
                <?php endforeach; ?>

            </select>
        </div>

        <button class="btn btn-danger">Criar Caso</button>
        <a href="participantes_ver.php?id=<?= $autor_id ?>" class="btn btn-secondary">Voltar</a>

    </form>

    <?php endif; ?>

</div>

<?php
// ==============================
// 💾 PROCESSAR VÍNCULO
// ==============================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $vitima_id = $_POST['vitima_id'] ?? null;

    if (!$vitima_id) {
        echo "<div class='alert alert-danger'>Selecione uma vítima.</div>";
    } else {

        try {
            $pdo->beginTransaction();

            // ==============================
            // 🔍 VALIDAR VÍTIMA
            // ==============================
            $stmt = $pdo->prepare("
                SELECT p.id 
                FROM participantes p
                INNER JOIN ficha_inclusao fi ON fi.participante_id = p.id
                WHERE p.id = ? 
                AND p.tipo = 'vitima'
                AND p.caso_id IS NULL
            ");
            $stmt->execute([$vitima_id]);

            if (!$stmt->fetch()) {
                throw new Exception("Vítima inválida ou já vinculada");
            }

            // ==============================
            // 1️⃣ CRIAR CASO
            // ==============================
            $stmt = $pdo->prepare("
                INSERT INTO casos (status, criado_em)
                VALUES ('ativo', NOW())
            ");
            $stmt->execute();

            $caso_id = $pdo->lastInsertId();

            // ==============================
            // 2️⃣ VINCULAR PARTICIPANTES
            // ==============================
            $stmt = $pdo->prepare("
                UPDATE participantes 
                SET caso_id = ? 
                WHERE id IN (?, ?)
            ");
            $stmt->execute([$caso_id, $autor_id, $vitima_id]);

            // ==============================
            // 🧾 LOG
            // ==============================
            $usuario_id = $_SESSION['usuario']['id'] ?? null;

            if ($usuario_id) {
                registrarLog(
                    $pdo,
                    'CREATE',
                    'casos',
                    $caso_id,
                    "Criou caso vinculando autor $autor_id e vítima $vitima_id",
                    $usuario_id
                );
            }

            $pdo->commit();

            header("Location: caso_detalhes.php?id=$caso_id");
            exit;

        } catch (Exception $e) {
            $pdo->rollBack();
            echo "<div class='alert alert-danger'>Erro: " . $e->getMessage() . "</div>";
        }
    }
}
?>

<?php require_once __DIR__ . '/../layout/admin_footer.php'; ?>