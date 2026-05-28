<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/auth.php';

auth();
canAny(['admin','atendente']);

$turma_id = $_GET['turma_id'] ?? null;

if (!$turma_id) {
    die("Turma não informada");
}

// 📊 Turma
$stmt = $pdo->prepare("SELECT nome FROM turmas WHERE id = ?");
$stmt->execute([$turma_id]);
$turma = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$turma) {
    die("Turma não encontrada");
}

// 👥 Participantes
$stmt = $pdo->prepare("
    SELECT nome, cpf, email, telefone, data_nascimento, bairro
    FROM participantes
    WHERE turma_id = ?
    ORDER BY nome
");
$stmt->execute([$turma_id]);
$dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 📥 Cabeçalho Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=participantes_" . $turma['nome'] . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

// 🧾 Tabela HTML (Excel entende)
echo "<table border='1'>";

// Cabeçalho
echo "<tr style='font-weight:bold; background:#ddd'>
        <th>Nome</th>
        <th>CPF</th>
        <th>Email</th>
        <th>Telefone</th>
        <th>Data Nascimento</th>
        <th>Bairro</th>
      </tr>";

// Dados
foreach ($dados as $d) {
    echo "<tr>
            <td>{$d['nome']}</td>
            <td>{$d['cpf']}</td>
            <td>{$d['email']}</td>
            <td>{$d['telefone']}</td>
            <td>" . date('d/m/Y', strtotime($d['data_nascimento'])) . "</td>
            <td>{$d['bairro']}</td>
          </tr>";
}

echo "</table>";
exit;