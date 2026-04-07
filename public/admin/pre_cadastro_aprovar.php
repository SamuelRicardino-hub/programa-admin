<?php
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../config/logs.php';
require_once __DIR__ . '/../../layout/admin_header.php';

auth();
canAny(['admin', 'atendente']);



$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID não informado");
}

// ==============================
// 🔍 BUSCAR PRÉ-CADASTRO
// ==============================
$stmt = $pdo->prepare("SELECT * FROM pre_cadastros WHERE id = ?");
$stmt->execute([$id]);
$pre = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pre) {
    die("Pré-cadastro não encontrado");
}

// ==============================
// 🚫 EVITAR DUPLICIDADE
// ==============================
if ($pre['status'] == 'aprovado') {
    echo "<div class='alert alert-warning'>Este cadastro já foi aprovado.</div>";
    exit;
}

// ==============================
// 📚 BUSCAR TURMAS
// ==============================
$turmas = $pdo->query("SELECT id, nome FROM turmas ORDER BY nome")->fetchAll();

// ==============================
// 💾 PROCESSAR FORMULÁRIO
// ==============================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $tipo = $_POST['tipo'] ?? null;
    $turma_id = $_POST['turma_id'] ?? null;
    $usuario_id = $_SESSION['usuario']['id'] ?? null;

    if (!$tipo || !$turma_id) {
        echo "<div class='alert alert-danger'>Preencha todos os campos.</div>";
    } else {

        try {

            $pdo->beginTransaction();

            // 🚫 Evitar duplicidade por CPF
            $stmt = $pdo->prepare("SELECT id FROM participantes WHERE cpf = ?");
            $stmt->execute([$pre['cpf']]);

            if ($stmt->fetch()) {
                throw new Exception("Participante já cadastrado com esse CPF.");
            }

            // ==============================
            // 👤 CRIAR PARTICIPANTE
            // ==============================
            $stmt = $pdo->prepare("
                INSERT INTO participantes (
                    nome,
                    cpf,
                    data_nascimento,
                    telefone,
                    email,
                    endereco,
                    bairro,
                    tipo,
                    turma_id
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $pre['nome'],
                $pre['cpf'],
                $pre['data_nascimento'],
                $pre['telefone'],
                $pre['email'],
                $pre['endereco'],
                $pre['bairro'],
                $tipo,
                $turma_id
            ]);

            $participante_id = $pdo->lastInsertId();

            // ==============================
            // 🔄 ATUALIZAR STATUS
            // ==============================
            $stmt = $pdo->prepare("
                UPDATE pre_cadastros 
                SET status = 'aprovado'
                WHERE id = ?
            ");
            $stmt->execute([$id]);

            if ($usuario_id) {
                registrarLog(
                    $pdo,
                    'CREATE',
                    'participantes',
                    $participante_id,
                    "Aprovou pré-cadastro ID $id (tipo: $tipo)",
                    $usuario_id
                );
            }
            $pdo->commit();

            echo "<div class='alert alert-success'>Cadastro aprovado com sucesso!</div>";

            echo "<a href='turmas_lista.php' class='btn btn-primary'>Ir para Turmas</a>";
        } catch (Exception $e) {

            $pdo->rollBack();
            echo "<div class='alert alert-danger'>Erro: " . $e->getMessage() . "</div>";
        }
    }
}
?>

<div class="container mt-4">

    <h2>Aprovar Pré-Cadastro</h2>

    <div class="card p-3 mb-3">
        <h5>Dados do Candidato</h5>
        <p><strong>Nome:</strong> <?= $pre['nome'] ?></p>
        <p><strong>CPF:</strong> <?= $pre['cpf'] ?></p>
        <p><strong>Telefone:</strong> <?= $pre['telefone'] ?></p>
        <p><strong>Email:</strong> <?= $pre['email'] ?></p>
    </div>

    <form method="POST">

        <div class="mb-3">
            <label class="form-label">Tipo do Participante</label>
            <select name="tipo" class="form-select" required>
                <option value="">Selecione</option>
                <option value="vitima">Vítima</option>
                <option value="autor">Autor</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Turma</label>
            <select name="turma_id" class="form-select" required>
                <option value="">Selecione</option>
                <?php foreach ($turmas as $t): ?>
                    <option value="<?= $t['id'] ?>">
                        <?= $t['nome'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button class="btn btn-success">Aprovar Cadastro</button>
        <a href="pre_cadastros.php" class="btn btn-secondary">Voltar</a>

    </form>

</div>

<?php require_once __DIR__ . '/../../layout/admin_footer.php' ?>