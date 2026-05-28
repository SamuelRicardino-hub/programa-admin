<?php
require_once __DIR__ . '/../config/logs.php';
// Traz os erros ocultos do servidor para a tela (Ajuda a descobrir o Erro 500)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inicia a sessão apenas se ela já não tiver sido aberta por outro arquivo
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// TESTE DE CAMINHO: Vamos garantir que o PHP ache o arquivo de conexão
$caminho_conexao = __DIR__ . '/../config/conexao.php';

if (!file_exists($caminho_conexao)) {
    die("Erro Crítico: O arquivo de conexão não foi encontrado no caminho: " . $caminho_conexao);
}

require_once $caminho_conexao;

// Evita acesso direto via URL
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../index.php");
    exit;
}

$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

// Verifica campos vazios
if ($email === '' || $senha === '') {
    header("Location: ../index.php?erro=1");
    exit;
}

try {
    // CORRIGIDO: Removida a coluna inexistente 'senate_hash'
    $sql = $pdo->prepare("
        SELECT id, nome, email, senha, nivel 
        FROM usuarios 
        WHERE email = :email 
        LIMIT 1
    ");

    $sql->bindParam(':email', $email);
    $sql->execute();
    $usuario = $sql->fetch(PDO::FETCH_ASSOC);

    registrarLog($pdo, 'LOGIN', 'usuarios', $usuario['id'], "Usuário realizou login com sucesso.");

    if ($usuario && password_verify($senha, $usuario['senha'])) {

        session_regenerate_id(true);

        // Salvando na raiz para a sua nova Sidebar funcionar perfeitamente
        $_SESSION['usuario_id']    = $usuario['id'];
        $_SESSION['usuario_nome']  = $usuario['nome'];
        $_SESSION['usuario_email'] = $usuario['email'];
        $_SESSION['usuario_nivel'] = strtolower(trim($usuario['nivel']));

        // Mantém o formato de array antigo por compatibilidade com outras telas
        $_SESSION['usuario'] = [
            'id'    => $usuario['id'],
            'nome'  => $usuario['nome'],
            'email' => $usuario['email'],
            'nivel' => strtolower(trim($usuario['nivel']))
        ];

        header("Location: dashboard.php");
        exit;
    } else {
        header("Location: ../index.php?erro=1");
        exit;
    }
} catch (PDOException $e) {
    die("Erro no banco de dados: " . $e->getMessage());
}
