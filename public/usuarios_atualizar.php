<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/logs.php';

auth();
can('admin'); // Garante que apenas o Admin processe esta atualização

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id    = $_POST['id'] ?? null;
    $nome  = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $nivel = $_POST['nivel'] ?? 'atendente';

    if (!$id || empty($nome) || empty($email)) {
        header("Location: usuarios_lista.php?erro=campos_vazios");
        exit;
    }

    try {
        // ========================================================
        // AQUI ENTRA O CÓDIGO SQL: Atualizando SEM o campo de senha
        // ========================================================
        $sql = $pdo->prepare("
            UPDATE usuarios SET
                nome = ?,
                email = ?,
                nivel = ?
            WHERE id = ?
        ");

        $sql->execute([$nome, $email, $nivel, $id]);

        // Registrar no Log de auditoria
        registrarLog($pdo, 'UPDATE', 'usuarios', $id, "Administrador atualizou o perfil do usuário: $nome ($email)");

        header("Location: usuarios_lista.php?msg=atualizado");
        exit;

    } catch (PDOException $e) {
        die("Erro ao atualizar o usuário: " . $e->getMessage());
    }
} else {
    header("Location: usuarios_lista.php");
    exit;
}