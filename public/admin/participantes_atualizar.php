<?php
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../config/auth.php';

auth();
canAny(['admin', 'atendente']);

$id = $_POST['id'] ?? null;
$nome = $_POST['nome'] ?? null;
$cpf = $_POST['cpf'] ?? null;
$data_nascimento = $_POST['data_nascimento'] ?? null;
$email = $_POST['email'] ?? null;
$telefone = $_POST['telefone'] ?? null;
$turma_id = $_POST['turma_id'] ?? null;

if (!$id || !$nome || !$email || !$turma_id) {
    die("Dados incompletos");
}


$sql = $pdo->prepare("
    UPDATE participantes
    SET nome = :nome,
        cpf = :cpf,
        data_nascimento = :data_nascimento,
        email = :email,
        telefone = :telefone,
        turma_id = :turma_id
    WHERE id = :id
");

$sql->execute([
    ':nome' => $nome,
    ':cpf' => $cpf,
    ':data_nascimento' => $data_nascimento,
    ':email' => $email,
    ':telefone' => $telefone,
    ':turma_id' => $turma_id,
    ':id' => $id
]);


header("Location: participantes_lista.php");
exit;
