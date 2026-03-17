<?php
require_once __DIR__ .'../config/conexao.php';
require_once __DIR__ . '/../../config/auth.php';

auth();
canAny(['admin', 'atendente']);


$id = $_POST['id'] ?? null;

$nome = $_POST['nome'];
$descricao = $_POST['descricao'];
$responsavel = $_POST['responsavel'];
$data_inicio = $_POST['data_inicio'];
$data_fim = $_POST['data_fim'];
$status = $_POST['status'];

if ($nome === '') {
    header("Location: turmas_lista.php");
    exit;
}

if ($id) {

$sql = $pdo->prepare("
UPDATE turmas SET
nome=?,
descricao=?,
responsavel=?,
data_inicio=?,
data_fim=?,
status=?
WHERE id=?
");

$sql->execute([
$nome,
$descricao,
$responsavel,
$data_inicio,
$data_fim,
$status,
$id
]);

} else {

$sql = $pdo->prepare("
INSERT INTO turmas
(nome, descricao, responsavel, data_inicio, data_fim, status)
VALUES (?, ?, ?, ?, ?, ?)
");

$sql->execute([
$nome,
$descricao,
$responsavel,
$data_inicio,
$data_fim,
$status
]);

}

header("Location: turmas_lista.php");
exit;