<?php
require_once __DIR__ . "/../../config/conexao.php";
require_once __DIR__ . "/../../config/protect.php";

$id = intval($_GET['id']);

$conn->begin_transaction();

try {

    $stmt = $conn->prepare("
        SELECT * FROM pre_cadastros 
        WHERE id = ? AND status = 'pendente'
    ");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $pre = $stmt->get_result()->fetch_assoc();

    if (!$pre) {
        throw new Exception("Pré-cadastro inválido.");
    }

    $stmt = $conn->prepare("
        INSERT INTO participantes
        (nome, cpf, email, telefone, data_nascimento, endereco)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "ssssss",
        $pre['nome'],
        $pre['cpf'],
        $pre['email'],
        $pre['telefone'],
        $pre['data_nascimento'],
        $pre['endereco']
    );

    $stmt->execute();

    $stmt = $conn->prepare("
        UPDATE pre_cadastros
        SET status = 'aprovado'
        WHERE id = ?
    ");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $conn->commit();

    header("Location: pre_cadastros_lista.php");
    exit;

} catch (Exception $e) {

    $conn->rollback();
    die("Erro: " . $e->getMessage());
}